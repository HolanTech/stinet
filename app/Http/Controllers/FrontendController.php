<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Paket;
use App\Models\Invoice;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class FrontendController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengurutkan paket berdasarkan speed terkecil
        $pakets = Paket::orderBy('speed', 'asc')->get();
        return view('frontend.index', compact('pakets'));
    }

    /**
     * Handle the login request.
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            return response()->json(['success' => true, 'redirect' => $this->getRedirectUrl($user)]);
        }

        return response()->json(['success' => false, 'message' => 'These credentials do not match our records.'], 401);
    }

    protected function getRedirectUrl($user)
    {
        switch ($user->role) {
            case 'admin':
                return route('dashboard');
            case 'sales':
                return route('dashboard');
            case 'member':
                return route('home.index');
            default:
                return route('home.index');
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:15',
            'alamat' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => 'member',
            'alamat' => $request->alamat,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return response()->json(['success' => true, 'redirect' => route('home.index')]);
    }

    /**
     * Handle the logout request.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('home.index');
    }
    public function cekTagihan(Request $request)
    {
        $request->validate([
            'no_pelanggan' => 'required',
        ]);

        try {
            $noPelanggan = $request->input('no_pelanggan');
            Log::info('Checking invoice for no_pelanggan:', ['no_pelanggan' => $noPelanggan]);

            $invoice = Invoice::where('no_pelanggan', $noPelanggan)->first();

            if (!$invoice) {
                Log::warning('Invoice not found for no_pelanggan:', ['no_pelanggan' => $noPelanggan]);
                return response()->json([
                    'success' => false,
                    'message' => 'Data tagihan tidak ditemukan.',
                ], 404);
            }

            Log::info('Invoice found:', ['invoice' => $invoice]);

            $data = [
                'customerName' => $invoice->customer->name,
                'customerNumber' => $invoice->no_pelanggan,
                'invoice_number' => $invoice->invoice_number,
                'invoice_date' => $invoice->invoice_date, // Menggunakan 'date' sesuai dengan nama kolom di database
                'status' => $invoice->status,
                'billingAmount' => $invoice->amount,
            ];

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            Log::error('Error in cekTagihan', ['exception' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch data.',
            ], 500);
        }
    }

    /**
     * Handle the cek tagihan request.
     */
    // public function cekTagihan(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'no_pelanggan' => 'required|string|max:15', // Menggunakan no_pelanggan sebagai field validasi
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
    //     }

    //     // Mencari pelanggan berdasarkan no_pelanggan
    //     $customer = Customer::where('no_pelanggan', $request->no_pelanggan)->first();

    //     if (!$customer) {
    //         return response()->json(['success' => false, 'message' => 'Pelanggan tidak ditemukan.']);
    //     }

    //     // Mengambil tagihan terbaru dari pelanggan ini
    //     $invoice = Invoice::where('customer_id', $customer->id)->latest()->first();

    //     if (!$invoice) {
    //         return response()->json(['success' => false, 'message' => 'Tagihan terbaru tidak ditemukan.']);
    //     }

    //     $data = [
    //         'invoice_number' => $invoice->invoice_number,
    //         'customerNumber' => $customer->no_pelanggan, // Menggunakan no_pelanggan
    //         'customerName' => $customer->name,
    //         'invoice_date' => $invoice->invoice_date, // Tambahkan tanggal invoice
    //         'status' => $invoice->status, // Tambahkan status invoice
    //         'billingAmount' => 'Rp ' . number_format($invoice->amount, 0, ',', '.'),
    //     ];

    //     return response()->json(['success' => true, 'data' => $data]);
    // }
}
