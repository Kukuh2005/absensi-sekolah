<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function index()
    {
        $user = User::all();

        return view('auth.login', compact('user'));
    }
    
    public function daftar()
    {
        $user = User::all();
        return view('auth.daftar', compact('user'));
    }

    public function store(Request $request)
    {
        try{
            $request->validate([
                'username' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string',
                'level' => 'required|string',
            ]);
    
            $user = new User;
            $user->username = $request->username;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->level = $request->level;
            $user->save();
    
    
            return redirect('login')->with('sukses', 'Berhasil Daftar, Silahkan Login!');
        }catch(\Exception $e){
            return redirect('daftar')->with('status', 'Tidak Berhasil Daftar. Pesan Kesalahan: '.$e->getMessage());
        }
    }

    public function login()
    {
        return view('auth.login');
    }

    public function postlogin(Request $request): RedirectResponse
    {
        if(Auth::attempt($request->only('email', 'password'))){
            $user = Auth::user();

            return redirect('dashboard');
        }
        else {
            return back()->with('gagal', 'Email atau Password salah!');
        }
    }

    public function logout()
    {
        Auth::logout();

        return redirect('/login');
    }

    public function forgotPw()
    {
        return view('auth.forgotPassword');
    }
}
