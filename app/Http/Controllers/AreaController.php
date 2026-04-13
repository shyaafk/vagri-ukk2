<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AreaController extends Controller
{
    public function index()
    {
        $area = DB::table('tb_area_parkir')
            ->orderBy('id_area','desc')
            ->get();

        return view('area', compact('area'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_area' => 'required|unique:tb_area_parkir,nama_area', // ✅ unique
            'kapasitas' => 'required|numeric|min:1'
        ], [
            'nama_area.unique' => 'Nama area ini sudah ada!',
        ]);

        DB::table('tb_area_parkir')->insert([
            'nama_area'  => $request->nama_area,
            'kapasitas'  => $request->kapasitas,
            'terisi'     => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/area')->with('success', 'Area berhasil ditambah');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_area' => 'required|unique:tb_area_parkir,nama_area,' . $id . ',id_area', // ✅ unique ignore id sendiri
            'kapasitas'  => 'required|numeric|min:1'
        ], [
            'nama_area.unique' => 'Nama area ini sudah ada!',
        ]);

        $area = DB::table('tb_area_parkir')
            ->where('id_area', $id)
            ->first();

        if (!$area) {
            return back()->with('error', 'Area tidak ditemukan');
        }

        if ($request->kapasitas < $area->terisi) {
            return back()->with('error', 'Kapasitas tidak boleh lebih kecil dari jumlah kendaraan!');
        }

        DB::table('tb_area_parkir')
            ->where('id_area', $id)
            ->update([
                'nama_area'  => $request->nama_area,
                'kapasitas'  => $request->kapasitas,
                'updated_at' => now(),
            ]);

        return redirect('/area')->with('success', 'Area berhasil diupdate');
    }

    public function delete($id)
    {
        $area = DB::table('tb_area_parkir')
            ->where('id_area', $id)
            ->first();

        if (!$area) {
            return back()->with('error', 'Area tidak ditemukan');
        }

        if ($area->terisi > 0) {
            return back()->with('error', 'Area masih digunakan!');
        }

        DB::table('tb_area_parkir')
            ->where('id_area', $id)
            ->delete();

        return redirect('/area')->with('success', 'Area berhasil dihapus');
    }
}