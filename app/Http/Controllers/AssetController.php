<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssetController extends Controller
{
    /**
     * Tampilkan daftar asset berdasarkan site.
     */
    public function index(Request $request)
    {
        $site = $request->query('site1');
        $asset = Asset::where('site', $site)->first();

        $createNewCanvas = false;
        if ($asset) {
            if (Storage::exists('public/' . $asset->image)) {
                $asset->imageUrl = asset('storage/' . $asset->image);
            } else {
                $createNewCanvas = true;
                $asset = null; // Pastikan $asset null jika gambar tidak ada
            }
            $asset->canvasData = $asset->data_json ? $asset->data_json : null;
        } else {
            $createNewCanvas = true;
        }
        // dd($asset->canvasData);
        return view('assets.index', [
            'asset' => $asset,
            'createNewCanvas' => $createNewCanvas,
            'site' => $site
        ]);
    }




    /**
     * Dapatkan URL gambar berdasarkan site.
     */
    public function getImageBySite(Request $request)
    {
        $site = $request->input('site');
        $asset = Asset::where('site', $site)->first();

        if ($asset) {
            return response()->json(['url' => asset('storage/' . $asset->image)]);
        } else {
            return response()->json(['error' => 'Image not found'], 404);
        }
    }

    /**
     * Simpan asset baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'site' => 'required|string',
            'image' => 'required|string', // Data gambar dalam bentuk base64
            'canvas' => 'nullable|string', // Data JSON sebagai string
        ]);

        $site = $request->input('site');
        $imageData = $request->input('image');
        $jsonData = $request->input('canvas');

        $asset = Asset::updateOrCreate(
            ['site' => $site],
            ['data_json' => $jsonData] // Akan diupdate dengan saveImage nanti
        );

        try {
            // Hapus gambar lama jika ada
            if ($asset->image && Storage::exists('public/' . $asset->image)) {
                Storage::delete('public/' . $asset->image);
            }

            // Simpan gambar baru dan perbarui record di database
            $path = $this->saveImage($imageData);
            $asset->image = $path;
            $asset->save();

            return response()->json(['success' => true, 'message' => 'Asset saved successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while saving the asset.'], 500);
        }
    }


    /**
     * Simpan gambar base64 sebagai file.
     */
    protected function saveImage($base64Image)
    {
        // Decode base64
        $image_data = base64_decode(substr($base64Image, strpos($base64Image, ",") + 1));

        // Tentukan path dan nama file
        $filename = 'assets/' . uniqid() . '.png';

        // Simpan gambar ke storage
        Storage::disk('public')->put($filename, $image_data);

        return $filename; // Path untuk disimpan ke database
    }
}
