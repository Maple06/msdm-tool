<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function page(){
        try {
            return view('auth.login');
        } catch (QueryException $e) { // Contoh: Menangani exception database
            Log::error($e->getMessage()); // Catat error ke log
            return back()->with('error', 'Terjadi kesalahan dengan database. Silakan coba lagi.'); // Pesan error ramah pengguna
        } catch (Exception $e) { // Tangani exception umum
            Log::error($e->getMessage()); // Catat error ke log
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.'); // Pesan error ramah pengguna
        }
    }

    public function post(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required|min:6',
            ]);

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                return redirect()->route('index')->with('success', 'Login berhasil!');
            }

            return back()->with('error', 'Email atau password salah.');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout(); // Logout user
        $request->session()->invalidate(); // Hapus sesi
        $request->session()->regenerateToken(); // Regenerasi token CSRF untuk keamanan

        return redirect()->route('login.page')->with('success', 'Anda telah berhasil logout.');
    }
}
