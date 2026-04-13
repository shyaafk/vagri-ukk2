<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OwnerController extends Controller
{
    public function dashboard()
    {
        $hariIni = now()->toDateString();     // lebih clean dari date()
        $bulanIni = now()->format('Y-m');

        // Pendapatan Hari Ini
        $pendapatanHari = DB::table('tb_transaksi')
            ->whereDate('waktu_masuk', $hariIni)
            ->where('status', 'selesai')
            ->sum('biaya_total');

        // Pendapatan Bulan Ini
        $pendapatanBulan = DB::table('tb_transaksi')
            ->where('status', 'selesai')
            ->whereMonth('waktu_masuk', now()->month)
            ->whereYear('waktu_masuk', now()->year)
            ->sum('biaya_total');

        // Jumlah Kendaraan Hari Ini
        $kendaraanHari = DB::table('tb_transaksi')
            ->whereDate('waktu_masuk', $hariIni)
            ->count();

        // Total Transaksi Bulan Ini
        $transaksiBulan = DB::table('tb_transaksi')
            ->whereMonth('waktu_masuk', now()->month)
            ->whereYear('waktu_masuk', now()->year)
            ->count();

        // Area Paling Ramai Hari Ini
        $areaTeramai = DB::table('tb_transaksi')
            ->join('tb_area_parkir', 'tb_transaksi.id_area', '=', 'tb_area_parkir.id_area')
            ->select('tb_area_parkir.nama_area', DB::raw('COUNT(*) as total'))
            ->whereDate('tb_transaksi.waktu_masuk', $hariIni)
            ->groupBy('tb_area_parkir.nama_area')
            ->orderByDesc('total')
            ->first();

        return view('owner_dashboard', compact(
            'pendapatanHari',
            'pendapatanBulan',
            'kendaraanHari',
            'transaksiBulan', // 🔥 INI YANG HARUS SAMA DI BLADE
            'areaTeramai'
        ));
    }

    public function rekap(Request $request)
    {
        $tanggal = $request->tanggal ?? now()->toDateString();

        $data = DB::table('tb_transaksi')
            ->whereDate('waktu_masuk', $tanggal)
            ->where('status', 'selesai')
            ->orderByDesc('waktu_masuk')
            ->get();

        $total = DB::table('tb_transaksi')
            ->whereDate('waktu_masuk', $tanggal)
            ->where('status', 'selesai')
            ->sum('biaya_total');

        return view('rekap.index', compact('data', 'total', 'tanggal'));
    }

    public function cetakRekap(Request $request)
    {
        $tanggal = $request->tanggal ?? now()->toDateString();

        $data = DB::table('tb_transaksi')
            ->whereDate('waktu_masuk', $tanggal)
            ->where('status', 'selesai')
            ->orderByDesc('waktu_masuk')
            ->get();

        $total = DB::table('tb_transaksi')
            ->whereDate('waktu_masuk', $tanggal)
            ->where('status', 'selesai')
            ->sum('biaya_total');

        return view('rekap.cetak', compact('data', 'total', 'tanggal'));
    }

    public function rekapBulanan(Request $request)
    {
        $bulan = $request->bulan ?? now()->format('Y-m');

        $data = DB::table('tb_transaksi')
            ->where('status', 'selesai')
            ->whereMonth('waktu_masuk', substr($bulan, 5, 2))
            ->whereYear('waktu_masuk', substr($bulan, 0, 4))
            ->orderByDesc('waktu_masuk')
            ->get();

        $total = DB::table('tb_transaksi')
            ->where('status', 'selesai')
            ->whereMonth('waktu_masuk', substr($bulan, 5, 2))
            ->whereYear('waktu_masuk', substr($bulan, 0, 4))
            ->sum('biaya_total');

        return view('rekap.bulanan', compact('data', 'total', 'bulan'));
    }
}