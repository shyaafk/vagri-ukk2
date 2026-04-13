<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
public function index(Request $request)
{
    $query = DB::table('tb_user');

    if ($request->role) {
        $query->where('role', $request->role);
    }

    $perPage = $request->per_page ?? 5;

    $users = $query->paginate($perPage)->withQueryString();

    return view('users', compact('users'));
}

public function store(Request $request)
{
    DB::table('tb_user')->insert([
        'nama_lengkap' => $request->nama_lengkap,
        'username' => $request->username,
        'password' => Hash::make($request->password),
        'role' => $request->role,
        'status' => $request->status,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return redirect('/users');
}


public function update(Request $request, $id)
{
    DB::table('tb_user')
        ->where('id_user', $id)
        ->update([
            'nama_lengkap' => $request->nama_lengkap,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => $request->status,
            'updated_at' => now(),
        ]);

    return redirect('/users');
}


    public function delete($id)
    {
        DB::table('tb_user')->where('id_user', $id)->delete();
        return redirect('/users');
    }
}