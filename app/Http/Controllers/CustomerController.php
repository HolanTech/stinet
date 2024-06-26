<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Paket;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::with('paket')->get();
        // dd($customers);
        return view("customer.index", compact("customers"));
    }

    public function member()
    {
        $members = User::leftJoin('customers', 'users.id', '=', 'customers.user_id')
            ->whereNull('customers.user_id')
            ->where('users.id', '!=', 1)
            ->get(['users.*']);
        $pakets = Paket::all();
        return view("customer.member", compact("members", 'pakets'));
    }

    public function create()
    {
        $pakets = Paket::all();
        return view("customer.create", compact('pakets'));
    }


    public function show($id)
    {
        $customer = User::findOrFail($id);
        $pakets = Paket::all();
        $data = [
            'id' => $customer->id,
            'name' => $customer->name,
            'phone' => $customer->phone,
            'email' => $customer->email,
            'alamat' => $customer->alamat,
        ];
        return view('customer.create', compact('data', 'pakets'));
    }


    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'name' => 'required|string|max:255',
            'no_hp' => 'required|string|max:15',
            'email' => 'required|string|email|max:255',
            'address' => 'required|string',
            'paket' => 'required|exists:pakets,id',
            'date' => 'required|date',
            'lokasi' => 'required|string',
            'merk_ont' => 'required|string',
            'sn_ont' => 'required|string',
            'dw' => 'required|string',
            'ktp' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'tempat' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'id' => 'nullable|exists:users,id', // If provided, ID of an existing user
        ]);

        // Handle user creation or updating
        if ($request->id) {
            $user = User::findOrFail($request->id);
            $user->name = $request->name;
            $user->phone = $request->no_hp;
            $user->email = $request->email;
            $user->alamat = $request->address;
        } else {
            // Create a new user if no ID is provided
            $user = new User();
            $user->name = $request->name;
            $user->phone = $request->no_hp;
            $user->email = $request->email;
            $user->alamat = $request->address;
            $user->role = 'member';
            $user->password = Hash::make('123456'); // Default password
        }

        // Save the user
        $user->save();

        // Handle file uploads
        $ktpPath = $request->file('ktp')->store('ktp', 'public');
        $tempatPath = $request->file('tempat')->store('tempat', 'public');

        // Fetch the paket details
        $paket = Paket::find($request->paket);

        // Calculate the tagihan (assuming paket has 'harga' and 'diskon' fields)
        $harga = $paket->harga;
        $diskon = $paket->diskon;
        $tagihan = $harga * (1 - ($diskon / 100));

        // Save customer data
        $customer = new Customer();
        $customer->name = $request->name;
        $customer->no_hp = $request->no_hp;
        $customer->email = $request->email;
        $customer->address = $request->address;
        $customer->paket = $paket->speed;
        $customer->tagihan = $tagihan;
        $customer->date = $request->date;
        $customer->lokasi = $request->lokasi;
        $customer->merk_ont = $request->merk_ont;
        $customer->sn_ont = $request->sn_ont;
        $customer->dw = $request->dw;
        $customer->ktp = $ktpPath;
        $customer->tempat = $tempatPath;
        $customer->user_id = $user->id; // Link to the user
        $customer->status = '1';
        $customer->save();

        // Generate and save the customer number
        $customer->no_pelanggan = 'STI' . str_pad($customer->id, 7, '0', STR_PAD_LEFT);
        $customer->save();

        // Redirect back with a success message
        return redirect()->route('customer.index')->with('success', 'Pelanggan baru berhasil ditambahkan dengan Id Pelanggan ' . $customer->no_pelanggan);
    }



    public function changeStatus(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);
        $newStatus = $request->status;
        $customer->status = $newStatus;
        $customer->save();

        return response()->json([
            'message' => 'Status updated successfully!',
            'newStatus' => $newStatus,
        ]);
    }

    public function showMap()
    {
        $customers = Customer::all();
        return view('customer.map', compact('customers'));
    }

    // Fungsi untuk mengedit data pelanggan
    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        $pakets = Paket::all();
        return view('customer.edit', compact('customer', 'pakets'));
    }

    public function update(Request $request, $id)
    {
        // Validate the incoming request
        $request->validate([
            'name' => 'required|string|max:255',
            'no_hp' => 'required|string|max:15',
            'email' => 'required|string|email|max:255',
            'address' => 'required|string',
            'paket' => 'required|exists:pakets,id', // Validate paket_id
            'date' => 'required|date',
            'lokasi' => 'required|string',
            'merk_ont' => 'required|string',
            'sn_ont' => 'required|string',
            'dw' => 'required|string',
            'ktp' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'tempat' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        // Find the customer and the associated user
        $customer = Customer::findOrFail($id);
        $user = User::findOrFail($customer->user_id);

        // Update user information
        $user->name = $request->name;
        $user->phone = $request->no_hp;
        $user->email = $request->email;
        $user->alamat = $request->address;
        $user->save();

        // Handle file uploads if they exist
        if ($request->hasFile('ktp')) {
            $ktpPath = $request->file('ktp')->store('ktp', 'public');
            $customer->ktp = $ktpPath;
        }

        if ($request->hasFile('tempat')) {
            $tempatPath = $request->file('tempat')->store('tempat', 'public');
            $customer->tempat = $tempatPath;
        }

        // Retrieve paket information from the database
        $paket = Paket::findOrFail($request->paket);

        // Calculate the bill based on the paket
        $harga = $paket->harga;
        $diskon = $paket->diskon;
        $tagihan = $harga - ($harga * $diskon / 100);

        // Update customer information
        $customer->name = $request->name;
        $customer->no_hp = $request->no_hp;
        $customer->email = $request->email;
        $customer->address = $request->address;
        $customer->paket = $paket->speed; // Save speed from paket
        $customer->tagihan = $tagihan; // Update tagihan
        $customer->date = $request->date;
        $customer->lokasi = $request->lokasi;
        $customer->merk_ont = $request->merk_ont;
        $customer->sn_ont = $request->sn_ont;
        $customer->dw = $request->dw;
        $customer->save();

        // Redirect back with a success message
        return redirect()->route('customer.index')->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    // Fungsi untuk menghapus data pelanggan
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return redirect()->route('customer.index')->with('success', 'Data pelanggan berhasil dihapus.');
    }
}
