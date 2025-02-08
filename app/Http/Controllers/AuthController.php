<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Exception;

class AuthController extends Controller
{
    public function page(){
        try {
            // $activity = Activity::all()->paginate(10);
            return view('admin.activity.index', compact('activity'));
        } catch (QueryException $e) { // Contoh: Menangani exception database
            Log::error($e->getMessage()); // Catat error ke log
            return back()->with('error', 'Terjadi kesalahan dengan database. Silakan coba lagi.'); // Pesan error ramah pengguna
        } catch (Exception $e) { // Tangani exception umum
            Log::error($e->getMessage()); // Catat error ke log
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.'); // Pesan error ramah pengguna
        }
    }

    public function post(Request $request){
        try {
            // $activity = Activity::all()->paginate(10);
            return view('admin.activity.index', compact('activity'));
        } catch (QueryException $e) { // Contoh: Menangani exception database
            Log::error($e->getMessage()); // Catat error ke log
            return back()->with('error', 'Terjadi kesalahan dengan database. Silakan coba lagi.'); // Pesan error ramah pengguna
        } catch (Exception $e) { // Tangani exception umum
            Log::error($e->getMessage()); // Catat error ke log
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.'); // Pesan error ramah pengguna
        }
    }

    public function logout(Request $request){
        try {
            // $activity = Activity::all()->paginate(10);
            return view('admin.activity.index', compact('activity'));
        } catch (QueryException $e) { // Contoh: Menangani exception database
            Log::error($e->getMessage()); // Catat error ke log
            return back()->with('error', 'Terjadi kesalahan dengan database. Silakan coba lagi.'); // Pesan error ramah pengguna
        } catch (Exception $e) { // Tangani exception umum
            Log::error($e->getMessage()); // Catat error ke log
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.'); // Pesan error ramah pengguna
        }
    }
}
