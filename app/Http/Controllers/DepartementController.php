<?php

namespace App\Http\Controllers;

use App\Models\Departement;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class DepartementController extends Controller
{
    public function index()
    {
        try {
            $departements = Departement::all();
            return view('admin.departement.index', compact('departements'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Terjadi kesalahan dengan database. Silakan coba lagi.');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    public function create() // Removed Request $request as it's not used
    {
        try {
            return view('admin.departement.create');
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
                'id' => 'required|string|unique:departements,id', // Assuming 'id' is the primary key
                'name' => 'required|string',
            ]);

            Departement::create($validatedData);

            return redirect()->route('departement.index')->with('success', 'Departement berhasil ditambahkan!'); // Corrected route name

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

    public function edit($id) // Added $id parameter
    {
        try {
            $departement = Departement::find($id); // Find the specific departement
            return view('admin.departement.edit', compact('departement')); // Pass the departement to the view
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Terjadi kesalahan dengan database. Silakan coba lagi.');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string',
                'id' => 'sometimes|required|string|unique:departements,id,' . $id,
            ]);

            Departement::where('id', $id)->update($validatedData);

            return redirect()->route('departement.index')->with('success', 'Departement berhasil diupdate!');

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

    public function destroy($id) // Added $id parameter
    {
        try {
            Departement::destroy($id); // Delete the specific departement
            return redirect()->route('departement.index')->with('success', 'Departement berhasil dihapus!'); // Corrected route name
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Terjadi kesalahan dengan database. Silakan coba lagi.');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }
}
