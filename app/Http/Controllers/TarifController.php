<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TarifController extends Controller
{
    public function index()
    {
        $tarif = DB::table('tb_tarif')->get();
        return view('tarif', compact('tarif'));
    }

    public function store(Request $request)
    {
        // ✅ Validasi: jenis_kendaraan tidak boleh duplikat
        $request->validate([
            'jenis_kendaraan' => 'required|unique:tb_tarif,jenis_kendaraan',
            'tarif_per_jam'   => 'required|numeric|min:0',
        ], [
            'jenis_kendaraan.unique' => 'Jenis kendaraan ini sudah ada!',
        ]);

        DB::table('tb_tarif')->insert([
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'tarif_per_jam'   => $request->tarif_per_jam,
            'created_at'      => now()
        ]);

        return redirect('/tarif');
    }

    public function update(Request $request, $id)
    {
        // ✅ Validasi: unique tapi ignore id yang sedang diedit
        $request->validate([
            'jenis_kendaraan' => 'required|unique:tb_tarif,jenis_kendaraan,' . $id . ',id_tarif',
            'tarif_per_jam'   => 'required|numeric|min:0',
        ], [
            'jenis_kendaraan.unique' => 'Jenis kendaraan ini sudah ada!',
        ]);

        DB::table('tb_tarif')
            ->where('id_tarif', $id)
            ->update([
                'jenis_kendaraan' => $request->jenis_kendaraan,
                'tarif_per_jam'   => $request->tarif_per_jam
            ]);

        return redirect('/tarif');
    }

    public function delete($id)
    {
        DB::table('tb_tarif')->where('id_tarif', $id)->delete();
        return redirect('/tarif');
    }
}