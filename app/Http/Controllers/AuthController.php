<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // ambil user berdasarkan username + status
        $user = DB::table('tb_user')
            ->where('username', $request->username)
            ->where('status', 'aktif')
            ->first();

        // cek password
        if ($user && Hash::check($request->password, $user->password)) {

            session([
                'id_user' => $user->id_user,
                'nama_lengkap' => $user->nama_lengkap,
                'role' => $user->role
            ]);

            return redirect('/dashboard');
        }

        return back()->with('error', 'Username atau password salah');
    }
    
    public function logout()
    {
        session()->flush();
        return redirect('/login');
    }
}