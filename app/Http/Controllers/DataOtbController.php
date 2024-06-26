<?php

namespace App\Http\Controllers;

use App\Models\DataOtb;
use Illuminate\Http\Request;

class DataOtbController extends Controller
{
    public function site()
    {
        $site1s = DataOtb::pluck('site1')->unique();
        $site2s = DataOtb::pluck('site2')->unique();

        // Gabungkan kedua koleksi dan ambil nilai uniknya lagi untuk menghilangkan duplikasi antara site1 dan site2
        $allSites = $site1s->merge($site2s)->unique()->sort();

        return view('data_otb.site', compact('allSites'));
    }

    public function index(Request $request)
    {
        // dd($request->all());
        $site1 = $request->query('site1');
        $site2 = $request->query('site2');

        $site1Data = DataOtb::where('site1', $site1)->orWhere('site2', $site1)->first();
        $site2Data = DataOtb::where('site1', $site2)->orWhere('site2', $site2)->first();

        $site1Lat = $site1Lon = $site2Lat = $site2Lon = null;

        // Cek jika SITE1 sudah ada di database
        if ($site1Data) {
            // Asumsikan data adalah nama kolom yang berisi JSON
            $jsonData = json_decode($site1Data->data, true);
            // Asumsikan baris kedua ([1]) berisi latitude dan longitude untuk SITE1
            $site1Lat = $jsonData[1][0]; // Latitude SITE1
            $site1Lon = $jsonData[1][1]; // Longitude SITE1
        }

        if ($site2Data) {
            $jsonData = json_decode($site2Data->data, true);
            // Asumsikan baris kedua ([2]) berisi latitude dan longitude untuk SITE1
            $site2Lat = $jsonData[1][3]; // Latitude SITE1
            $site2Lon = $jsonData[1][4];
        }
        // Contoh pencarian data, sesuaikan dengan struktur database Anda
        $dataOtb = null;
        if ($site1 && $site2) {
            $dataOtb = DataOtb::where('site1', $site1)->where('site2', $site2)->first();
        }

        if ($dataOtb) {
            // Jika data ditemukan
            $jsonData = $dataOtb->data ?: json_encode([[]]);
            $jsonMergeCells = $dataOtb->merge_cells ?: json_encode([]);
        } else {
            // Jika tidak ditemukan, siapkan Handsontable dengan template default dan konfigurasi merge cells
            $jsonData = json_encode([
                [" $site1", "", "<>", "$site2", "", "JARAK", "JUMLAH SPLICE", "TOTAL LOSS", "AVERAGE LOSS", ""],
                [" $site1Lat", " $site1Lon", "", "  $site2Lat", "  $site2Lon", "", "", "dB", "dB/Km", ""],
                ["Tube", "No", "CORE", "Direction", "", "CUSTOMER", "Distance (km)", "Commulate Loss", "Remark", ""],
                ["", "", "", "OTB FAR END", "STATUS CORE", "", "", "", "", ""],
                // Tambahkan lebih banyak baris kosong sesuai kebutuhan
            ]);
            $jsonMergeCells = json_encode([
                // Konfigurasi merge cells sesuai permintaan
                ["row" => 2, "col" => 0, "rowspan" => 2, "colspan" => 1],
                ["row" => 2, "col" => 1, "rowspan" => 2, "colspan" => 1],
                ["row" => 2, "col" => 2, "rowspan" => 2, "colspan" => 1],
                ["row" => 2, "col" => 3, "rowspan" => 1, "colspan" => 2],
                ["row" => 2, "col" => 5, "rowspan" => 2, "colspan" => 1],
                ["row" => 2, "col" => 6, "rowspan" => 2, "colspan" => 1],
                ["row" => 2, "col" => 7, "rowspan" => 2, "colspan" => 1],
                ["row" => 2, "col" => 8, "rowspan" => 2, "colspan" => 1],
                ["row" => 4, "col" => 0, "rowspan" => 6, "colspan" => 1],
                ["row" => 10, "col" => 0, "rowspan" => 6, "colspan" => 1],
                ["row" => 0, "col" => 0, "rowspan" => 1, "colspan" => 2],
                ["row" => 0, "col" => 3, "rowspan" => 1, "colspan" => 2],
                // Tambahkan konfigurasi merge cells lainnya sesuai kebutuhan
            ]);
        }
        // dd($jsonData);
        return view('data_otb.index', compact('jsonData', 'jsonMergeCells', 'site1', 'site2'));
    }





    public function showMap(Request $request)
    {
        $site1 = $request->query('site1');
        $site2 = $request->query('site2');

        $dataOtb = DataOtb::where('site1', $site1)->where('site2', $site2)->first();

        $jsonData = $dataOtb ? json_decode($dataOtb->data, true) : [];
        $siteA = null;
        $siteB = null;

        if (!empty($jsonData) && count($jsonData) > 1) {
            $siteA = [
                'lat' => $jsonData[1][0],
                'lng' => $jsonData[1][1],
                'name' => $jsonData[0][0] // Mengambil nama SITE A dari JSON
            ];
            $siteB = [
                'lat' => $jsonData[1][3],
                'lng' => $jsonData[1][4],
                'name' => $jsonData[0][3] // Mengambil nama SITE B dari JSON
            ];
        }

        return view('data_otb.maps', compact('siteA', 'siteB'));
    }
    public function showAllMap(Request $request)
    {
        $dataOtbs = DataOtb::all();

        $sitePairs = []; // Akan menyimpan pasangan site

        foreach ($dataOtbs as $dataOtb) {
            $jsonData = json_decode($dataOtb->data, true);
            if (!empty($jsonData) && count($jsonData) > 1) {
                $sitePairs[] = [
                    'siteA' => [
                        'lat' => $jsonData[1][0],
                        'lng' => $jsonData[1][1],
                        'name' => $jsonData[0][0], // Nama SITE A
                    ],
                    'siteB' => [
                        'lat' => $jsonData[1][3],
                        'lng' => $jsonData[1][4],
                        'name' => $jsonData[0][3], // Nama SITE B
                    ]
                ];
            }
        }
        // Uncomment line below if you want to dump and die to check the structure of $sitePairs
        // dd($sitePairs);

        return view('data_otb.allsites', compact('sitePairs'));
    }





    public function store(Request $request)
    {
        $request->validate([
            'data' => 'required',
            'mergeCells' => 'nullable',
            'site1' => 'required',
            'site2' => 'required',
            // 'cellMeta' => 'required'
        ]);

        // Gunakan updateOrCreate untuk mengecek dan memutuskan apakah perlu update atau create
        // Kunci array pertama menentukan kondisi pencarian (kondisi "where")
        // Kunci array kedua menentukan nilai yang akan diupdate atau ditambahkan
        $dataOtb = DataOtb::updateOrCreate(
            [
                'site1' => $request->input('site1'),
                'site2' => $request->input('site2')
            ],
            [
                'data' => json_encode($request->input('data')),
                'merge_cells' => json_encode($request->input('mergeCells')),
                // 'cellMeta' => json_encode($request->input('cellMeta')),
            ]
        );

        return response()->json(['message' => 'Data berhasil disimpan']);
    }
}
