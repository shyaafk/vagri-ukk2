<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function index()
    {
        $area = DB::table('tb_area_parkir')->get();

        $transaksi = DB::table('tb_transaksi')
            ->join('tb_kendaraan', 'tb_transaksi.id_kendaraan', '=', 'tb_kendaraan.id_kendaraan')
            ->join('tb_area_parkir', 'tb_transaksi.id_area', '=', 'tb_area_parkir.id_area')
            ->select(
                'tb_transaksi.*',
                'tb_kendaraan.plat_nomor',
                'tb_kendaraan.pemilik',
                'tb_kendaraan.jenis_kendaraan',
                'tb_area_parkir.nama_area'
            )
            ->orderBy('waktu_masuk', 'desc')
            ->get();

        // 🔥 HANYA DATA ADMIN
        $kendaraan = DB::table('tb_kendaraan')
            ->join('tb_user','tb_kendaraan.id_user','=','tb_user.id_user')
            ->where('tb_user.role','admin')
            ->select('tb_kendaraan.*')
            ->get();

        return view('transaksi', compact('area','transaksi','kendaraan'));
    }

    public function masuk(Request $request)
    {
        DB::beginTransaction();

        try {

            // 🔥 CEK AREA DULU (INI YANG PALING PENTING)
            $area = DB::table('tb_area_parkir')
                ->where('id_area', $request->area)
                ->lockForUpdate()
                ->first();

            if (!$area) {
                throw new \Exception('Area tidak ditemukan');
            }

            if ($area->terisi >= $area->kapasitas) {
                throw new \Exception('🚫 Area parkir sudah penuh!');
            }

            // =========================
            // MODE PILIH ATAU MANUAL
            // =========================
            if($request->id_kendaraan){

                // 🔹 MODE PILIH (DARI ADMIN)
                $kendaraan = DB::table('tb_kendaraan')
                    ->where('id_kendaraan',$request->id_kendaraan)
                    ->first();

                if(!$kendaraan){
                    throw new \Exception('Kendaraan tidak ditemukan');
                }

                $kendaraanId = $kendaraan->id_kendaraan;
                $jenis = $kendaraan->jenis_kendaraan;
                $plat = $kendaraan->plat_nomor;

            } else {

                // 🔹 MODE MANUAL (TIDAK MASUK LIST ADMIN)
                $request->validate([
                    'plat' => 'required',
                    'warna' => 'required',
                    'pemilik' => 'required',
                    'jenis' => 'required',
                ]);

                $kendaraanId = DB::table('tb_kendaraan')->insertGetId([
                    'plat_nomor' => strtoupper($request->plat),
                    'jenis_kendaraan' => $request->jenis,
                    'warna' => $request->warna,
                    'pemilik' => $request->pemilik,
                    'id_user' => session('id_user'), // petugas
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $jenis = $request->jenis;
                $plat = strtoupper($request->plat);
            }

            // =========================
            // TARIF
            // =========================
            $tarif = DB::table('tb_tarif')
                ->where('jenis_kendaraan',$jenis)
                ->first();

            if(!$tarif){
                throw new \Exception('Tarif tidak ditemukan');
            }

            // =========================
            // INSERT TRANSAKSI
            // =========================
            DB::table('tb_transaksi')->insert([
                'id_kendaraan' => $kendaraanId,
                'id_area' => $request->area,
                'id_user' => session('id_user'),
                'id_tarif' => $tarif->id_tarif,
                'waktu_masuk' => now(),
                'status' => 'parkir',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // =========================
            // UPDATE AREA (NAIK)
            // =========================
            DB::table('tb_area_parkir')
                ->where('id_area',$request->area)
                ->increment('terisi');

            // =========================
            // LOG
            // =========================
            DB::table('tb_log_aktivitas')->insert([
                'id_user' => session('id_user'),
                'aktivitas' => 'Kendaraan masuk - Plat: '.$plat,
                'waktu_aktivitas' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return redirect('/transaksi')->with('success','Berhasil parkir');

        } catch(\Exception $e){

            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    public function keluar($id)
    {
        $transaksi = DB::table('tb_transaksi')
            ->where('id_transaksi', $id)
            ->first();

        if (!$transaksi) {
            return back();
        }

        $waktu_keluar = now();

        $durasi = max(1, ceil((strtotime($waktu_keluar) - strtotime($transaksi->waktu_masuk)) / 3600));

        $tarif = DB::table('tb_tarif')
            ->where('id_tarif', $transaksi->id_tarif)
            ->first();

        $biaya = $durasi * $tarif->tarif_per_jam;

        DB::table('tb_transaksi')
            ->where('id_transaksi', $id)
            ->update([
                'waktu_keluar' => $waktu_keluar,
                'durasi_jam' => $durasi,
                'biaya_total' => $biaya,
                'status' => 'selesai',
                'updated_at' => now(),
            ]);

        // 🔥 TURUNKAN SLOT
        DB::table('tb_area_parkir')
            ->where('id_area', $transaksi->id_area)
            ->decrement('terisi');

        DB::table('tb_log_aktivitas')->insert([
            'id_user' => session('id_user'),
            'aktivitas' => 'Kendaraan keluar - ID Transaksi: ' . $id,
            'waktu_aktivitas' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/transaksi');
    }

    public function cetak($id)
    {
        $data = DB::table('tb_transaksi')
            ->join('tb_kendaraan', 'tb_transaksi.id_kendaraan', '=', 'tb_kendaraan.id_kendaraan')
            ->join('tb_area_parkir', 'tb_transaksi.id_area', '=', 'tb_area_parkir.id_area')
            ->join('tb_tarif', 'tb_transaksi.id_tarif', '=', 'tb_tarif.id_tarif')
            ->select(
                'tb_transaksi.*',
                'tb_kendaraan.plat_nomor',
                'tb_kendaraan.jenis_kendaraan',
                'tb_kendaraan.pemilik',
                'tb_area_parkir.nama_area',
                'tb_tarif.tarif_per_jam'
            )
            ->where('id_transaksi', $id)
            ->first();

        if (!$data || $data->status != 'selesai') {
            abort(403);
        }

        return view('transaksi.struk', compact('data'));
    }
}