<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LogController extends Controller
{
    public function index(Request $request)
    {
        // 🔥 VALIDASI (BIAR GAK NGACO)
        $request->validate([
            'tanggal_awal' => 'nullable|date',
            'tanggal_akhir' => 'nullable|date|after_or_equal:tanggal_awal',
            'keyword' => 'nullable|string|max:100'
        ]);

        $query = DB::table('tb_log_aktivitas')
            ->join('tb_user','tb_log_aktivitas.id_user','=','tb_user.id_user')
            ->select('tb_log_aktivitas.*','tb_user.nama_lengkap');

// 🔥 FILTER TANGGAL - pakai whereDate
        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {

            $query->whereRaw('DATE(tb_log_aktivitas.waktu_aktivitas) >= ?', [$request->tanggal_awal])
                ->whereRaw('DATE(tb_log_aktivitas.waktu_aktivitas) <= ?', [$request->tanggal_akhir]);

        } elseif ($request->filled('tanggal_awal')) {

            $query->whereRaw('DATE(tb_log_aktivitas.waktu_aktivitas) >= ?', [$request->tanggal_awal]);

        } elseif ($request->filled('tanggal_akhir')) {

            $query->whereRaw('DATE(tb_log_aktivitas.waktu_aktivitas) <= ?', [$request->tanggal_akhir]);
        }

        // 🔥 FILTER KEYWORD
        if ($request->filled('keyword')) {
            $query->where(function($q) use ($request){
                $q->where('tb_log_aktivitas.aktivitas','like','%'.$request->keyword.'%')
                  ->orWhere('tb_user.nama_lengkap','like','%'.$request->keyword.'%');
            });
        }

        $log = $query->orderBy('tb_log_aktivitas.waktu_aktivitas','desc')
                     ->paginate(10)
                     ->appends($request->query());
        return view('log', compact('log'));
    }
}