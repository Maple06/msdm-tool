<?php

namespace App\Http\Controllers;

use App\Imports\MemberImport;
use App\Models\Member;
use App\Models\Division;
use App\Models\Departement;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class MemberController extends Controller
{
    public function index()
    {
        try {
            $members = Member::all();
            return view('admin.member.index', compact('members'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Terjadi kesalahan dengan database. Silakan coba lagi.');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    public function create()
    {
        try {
            $divisions = Division::all();
            $departements = Departement::all();
            return view('admin.member.create', compact('divisions', 'departements'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Terjadi kesalahan dengan database. Silakan coba lagi.');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nrp' => 'required|string|unique:members,nrp',
                'name' => 'required|string',
                'email' => 'required|email|unique:members,email',
                'phone' => 'required|string',
                'role' => 'required|string',
                'division_code' => 'required|string',
                'departement_code' => 'required|string',
            ]);

            Member::create($validatedData);
            return redirect()->route('member.index')->with('success', 'Member berhasil ditambahkan!');
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan dengan database. Silakan coba lagi.');
        } catch (ValidationException $e) {
            return back()->withInput()->withErrors($e->validator)->with('error', 'Data yang anda masukkan tidak valid. Silakan periksa kembali.');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    public function edit($id)
    {
        try {
            $member = Member::find($id);
            $divisions = Division::all();
            $departements = Departement::all();
            return view('admin.member.edit', compact('member', 'divisions', 'departements'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Terjadi kesalahan dengan database. Silakan coba lagi.');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    public function update(Request $request, $nrp)
    {
        try {
            // Validasi input
            $validatedData = $request->validate([
                'nrp' => 'required|string|unique:members,nrp,' . $nrp . ',nrp',
                'name' => 'required|string',
                'email' => 'required|email|unique:members,email,' . $nrp . ',nrp',
                'phone' => 'required|string',
                'role' => 'required|string',
                'division_code' => 'required|string',
                'departement_code' => 'required|string',
            ]);

            // Update data berdasarkan NRP
            Member::where('nrp', $nrp)->update($validatedData);

            return redirect()->route('member.index')->with('success', 'Member berhasil diupdate!');
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan dengan database. Silakan coba lagi.');
        } catch (ValidationException $e) {
            return back()->withInput()->withErrors($e->validator)->with('error', 'Data yang anda masukkan tidak valid. Silakan periksa kembali.');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    public function destroy(Member $member)
    {
        try {
            $member->delete();
            return redirect()->route('member.index')->with('success', 'Member berhasil dihapus!');
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Terjadi kesalahan dengan database. Silakan coba lagi.');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'files' => 'required|mimes:xlsx,csv',
        ]);

        Excel::import(new MemberImport, $request->file('files'));

        return back()->with('success', 'Data absensi berhasil diimport!');
    }
}
