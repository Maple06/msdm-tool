<?php

namespace App\Http\Controllers;

use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
Use Exception;

class DivisionController extends Controller
{
    public function index(){
        try {
            $activity = Division::all()->paginate(10);
            return view('admin.activity.index', compact('activity'));
        } catch (QueryException $e) { // Contoh: Menangani exception database
            Log::error($e->getMessage()); // Catat error ke log
            return back()->with('error', 'Terjadi kesalahan dengan database. Silakan coba lagi.'); // Pesan error ramah pengguna
        } catch (Exception $e) { // Tangani exception umum
            Log::error($e->getMessage()); // Catat error ke log
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.'); // Pesan error ramah pengguna
        }
    }

    public function create(Request $request){
        try {
            $activity = Division::all()->paginate(10);
            return view('admin.activity.index', compact('activity'));
        } catch (QueryException $e) { // Contoh: Menangani exception database
            Log::error($e->getMessage()); // Catat error ke log
            return back()->with('error', 'Terjadi kesalahan dengan database. Silakan coba lagi.'); // Pesan error ramah pengguna
        } catch (Exception $e) { // Tangani exception umum
            Log::error($e->getMessage()); // Catat error ke log
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.'); // Pesan error ramah pengguna
        }
    }

    public function store(Request $request){
        try {
            $activity = Division::all()->paginate(10);
            return view('admin.activity.index', compact('activity'));
        } catch (QueryException $e) { // Contoh: Menangani exception database
            Log::error($e->getMessage()); // Catat error ke log
            return back()->with('error', 'Terjadi kesalahan dengan database. Silakan coba lagi.'); // Pesan error ramah pengguna
        } catch (Exception $e) { // Tangani exception umum
            Log::error($e->getMessage()); // Catat error ke log
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.'); // Pesan error ramah pengguna
        }
    }

    public function edit(Request $request){
        try {
            $activity = Division::all()->paginate(10);
            return view('admin.activity.index', compact('activity'));
        } catch (QueryException $e) { // Contoh: Menangani exception database
            Log::error($e->getMessage()); // Catat error ke log
            return back()->with('error', 'Terjadi kesalahan dengan database. Silakan coba lagi.'); // Pesan error ramah pengguna
        } catch (Exception $e) { // Tangani exception umum
            Log::error($e->getMessage()); // Catat error ke log
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.'); // Pesan error ramah pengguna
        }
    }

    public function update(Request $request){
        try {
            $activity = Division::all()->paginate(10);
            return view('admin.activity.index', compact('activity'));
        } catch (QueryException $e) { // Contoh: Menangani exception database
            Log::error($e->getMessage()); // Catat error ke log
            return back()->with('error', 'Terjadi kesalahan dengan database. Silakan coba lagi.'); // Pesan error ramah pengguna
        } catch (Exception $e) { // Tangani exception umum
            Log::error($e->getMessage()); // Catat error ke log
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.'); // Pesan error ramah pengguna
        }
    }

    public function destroy(Request $request){
        try {
            $activity = Division::all()->paginate(10);
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
