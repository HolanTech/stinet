<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use Illuminate\Http\Request;

class PaketController extends Controller
{
    public function index()
    {
        $pakets = Paket::all();
        return view('paket.index', compact('pakets'));
    }

    public function create()
    {
        return view('paket.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipe' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'diskon' => 'required|numeric',
            'speed' => 'required|string|max:255',
            'qty' => 'required|integer',
            'image' => 'required|image|max:2048'
        ]);

        $paket = new Paket($request->all());
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('paket_images', 'public');
            $paket->image = $imagePath;
        }
        $paket->save();

        return redirect()->route('paket.index')->with('success', 'Paket created successfully.');
    }

    public function edit(Paket $paket)
    {
        return view('paket.edit', compact('paket'));
    }

    public function update(Request $request, Paket $paket)
    {
        $request->validate([
            'tipe' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'diskon' => 'required|numeric',
            'speed' => 'required|string|max:255',
            'qty' => 'required|integer',
            'image' => 'nullable|image|max:2048'
        ]);

        $paket->fill($request->all());
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('paket_images', 'public');
            $paket->image = $imagePath;
        }
        $paket->save();

        return redirect()->route('paket.index')->with('success', 'Paket updated successfully.');
    }

    public function destroy(Paket $paket)
    {
        $paket->delete();
        return redirect()->route('paket.index')->with('success', 'Paket deleted successfully.');
    }
}
