<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KendaraanController extends Controller
{
    // =========================
    // TAMPILKAN DATA (FILTER USER)
    // =========================
    public function index()
    {
        $data = DB::table('tb_kendaraan')
            ->join('tb_user','tb_kendaraan.id_user','=','tb_user.id_user')
            ->where('tb_kendaraan.id_user', session('id_user')) // 🔥 FILTER
            ->select('tb_kendaraan.*','tb_user.nama_lengkap')
            ->orderBy('id_kendaraan','desc')
            ->get();

        return view('kendaraan', compact('data'));
    }

    // =========================
    // SIMPAN DATA
    // =========================
    public function store(Request $request)
    {
        $request->validate([
            'plat' => 'required',
            'warna' => 'required',
            'jenis' => 'required',
            'pemilik' => 'required'
        ]);

        DB::table('tb_kendaraan')->insert([
            'plat_nomor' => strtoupper($request->plat), // 🔥 rapihin
            'warna' => $request->warna,
            'jenis_kendaraan' => $request->jenis,
            'pemilik' => $request->pemilik,
            'id_user' => session('id_user'),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect('/kendaraan')->with('success','Data berhasil ditambah');
    }

    // =========================
    // UPDATE DATA (AMAN)
    // =========================
    public function update(Request $request, $id)
    {
        $request->validate([
            'plat' => 'required',
            'warna' => 'required',
            'jenis' => 'required',
            'pemilik' => 'required'
        ]);

        DB::table('tb_kendaraan')
            ->where('id_kendaraan',$id)
            ->where('id_user', session('id_user')) // 🔥 BIAR GAK BISA EDIT ORANG LAIN
            ->update([
                'plat_nomor' => strtoupper($request->plat),
                'warna' => $request->warna,
                'jenis_kendaraan' => $request->jenis,
                'pemilik' => $request->pemilik,
                'updated_at' => now()
            ]);

        return redirect('/kendaraan')->with('success','Data berhasil diupdate');
    }

    // =========================
    // DELETE DATA (AMAN)
    // =========================
    public function delete($id)
    {
        DB::table('tb_kendaraan')
            ->where('id_kendaraan',$id)
            ->where('id_user', session('id_user')) // 🔥 BIAR GAK HAPUS DATA ORANG
            ->delete();

        return redirect('/kendaraan')->with('success','Data berhasil dihapus');
    }
}