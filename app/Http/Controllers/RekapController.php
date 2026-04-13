<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RekapController extends Controller
{
    public function index(Request $request)
    {
        $tipe = $request->tipe ?? 'harian';
        $tanggal = $request->tanggal ?? date('Y-m-d');

        $query = DB::table('tb_transaksi')
            ->join('tb_kendaraan','tb_transaksi.id_kendaraan','=','tb_kendaraan.id_kendaraan')
            ->where('status','selesai');

        if($tipe == 'harian'){
            $query->whereDate('waktu_masuk', $tanggal);
        }

        elseif($tipe == 'mingguan'){
            $start = date('Y-m-d', strtotime($tanggal . ' -6 days'));
            $end   = $tanggal;

            $query->whereBetween('waktu_masuk', [$start, $end]);
        }

        elseif($tipe == 'bulanan'){
            $bulan = date('m', strtotime($tanggal));
            $tahun = date('Y', strtotime($tanggal));

            $query->whereMonth('waktu_masuk', $bulan)
                ->whereYear('waktu_masuk', $tahun);
        }

        $data = $query->select(
            'tb_transaksi.*',
            'tb_kendaraan.plat_nomor'
        )->orderBy('waktu_masuk','desc')->get();

        $total = $data->sum('biaya_total');

        return view('rekap', compact('data','total','tanggal','tipe'));
    }

    public function cetak(Request $request)
    {
        $tipe = $request->tipe ?? 'harian';
        $tanggal = $request->tanggal;

        $query = DB::table('tb_transaksi')
            ->join('tb_kendaraan','tb_transaksi.id_kendaraan','=','tb_kendaraan.id_kendaraan')
            ->where('status','selesai');

        if($tipe == 'harian'){
            $query->whereDate('waktu_masuk', $tanggal);
        }

        elseif($tipe == 'mingguan'){
            $start = date('Y-m-d', strtotime($tanggal . ' -6 days'));
            $end   = $tanggal;

            $query->whereBetween('waktu_masuk', [$start, $end]);
        }

        elseif($tipe == 'bulanan'){
            $bulan = date('m', strtotime($tanggal));
            $tahun = date('Y', strtotime($tanggal));

            $query->whereMonth('waktu_masuk', $bulan)
                ->whereYear('waktu_masuk', $tahun);
        }

        $data = $query->select(
            'tb_transaksi.*',
            'tb_kendaraan.plat_nomor'
        )->get();

        $total = $data->sum('biaya_total');

        return view('rekap.cetak', compact('data','tanggal','total','tipe'));
    }
}