<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Departement; // Import model Departement
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Exception;

class DivisionController extends Controller
{
    public function index()
    {
        try {
            $divisions = Division::with('departement')->get(); // Eager load data departemen
            return view('admin.division.index', compact('divisions')); // Ganti nama view menjadi division.index
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
            $departements = Departement::all(); // Ambil semua departemen untuk dropdown
            return view('admin.division.create', compact('departements')); // Ganti nama view menjadi division.create
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
        $request->validate([ // Validasi input
            'name' => 'required|string|max:255',
            'departement_code' => 'required|exists:departements,id', // Pastikan departemen ada di database
        ]);

        try {
            Division::create([
                'name' => $request->name,
                'departement_code' => $request->departement_code,
            ]);

            return redirect()->route('divisions.index')->with('success', 'Divisi berhasil ditambahkan.');
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Terjadi kesalahan dengan database. Silakan coba lagi.');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    public function edit($id)
    {
        try {
            $division = Division::find($id);
            $departements = Departement::all();
            return view('admin.division.edit', compact('division', 'departements')); // Ganti nama view menjadi division.edit
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
        $request->validate([
            'name' => 'required|string|max:255',
            'departement_code' => 'required|exists:departements,id',
        ]);

        try {
            $division = Division::find($id);
            $division->update([
                'name' => $request->name,
                'departement_code' => $request->departement_code,
            ]);

            return redirect()->route('division.index')->with('success', 'Divisi berhasil diupdate.');
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Terjadi kesalahan dengan database. Silakan coba lagi.');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    public function show($id)
    {
        try {
            $division = Division::with('members')->find($id); // Eager load data anggota

            if (!$division) {
                abort(404); // Tampilkan error 404 jika divisi tidak ditemukan
            }

            return view('admin.division.show', compact('division')); // Ganti nama view menjadi division.show
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Terjadi kesalahan dengan database. Silakan coba lagi.');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    public function report($id, Request $request)
    {
        $selectedMonth = $request->input('month', Carbon::now()->format('Y-m'));
        
        $bulan = explode("-", $selectedMonth)[1];

        // Jika ingin output integer
        $bulan_int = (int) $bulan;

        $division = Division::find($id);
        if (!$division) {
            abort(404);
        }

        $members = $division->members;

        $reportData = [];
        foreach ($members as $member) {
            $performances = $member->calculatePerformance(); // Data performa keseluruhan member

            $monthlyPerformances = [];
            for ($month = 1; $month <= 12; $month++) {
                $monthlyPerformance = $member->calculateMonthlyPerformance($month, date('Y'));
                $monthlyPerformances[] = $monthlyPerformance;
            }

            $reportData[] = [
                'member' => $member,
                'performances' => $performances, // Tambahkan data performa keseluruhan
                'monthlyPerformances' => $monthlyPerformances, // Tambahkan data performa bulanan
            ];
        }

        $rerata = 0;

        foreach ($reportData as $memberData) {
            // Pastikan bulan yang diminta ada di dalam array monthlyPerformances
            if (isset($memberData['monthlyPerformances'][$bulan_int - 1]['attendance_percentage'])) {
                $rerata += $memberData['monthlyPerformances'][$bulan_int - 1]['attendance_percentage'];
            } else {
                // Handle kasus jika data tidak ditemukan untuk bulan tersebut
                // Misalnya, log error atau berikan nilai default
                Log::warning('Attendance percentage not found for month ' . $bulan_int);
            }
        }

        return view('admin.division.report', compact('division', 'reportData', 'selectedMonth','rerata'));
    }

    public function generate($id, Request $request)
    {
        $selectedMonth = $request->input('month', Carbon::now()->format('Y-m'));
        $pesan = $request->pesan;
        $bulan = explode("-", $selectedMonth)[1];

        // Jika ingin output integer
        $bulan_int = (int) $bulan;

        $division = Division::find($id);
        if (!$division) {
            abort(404);
        }

        $members = $division->members;

        $reportData = [];
        $attendancePercentages = [];
        foreach ($members as $member) {
            $performances = $member->calculatePerformance(); // Data performa keseluruhan member

            $monthlyPerformances = [];
            for ($month = 1; $month <= 12; $month++) {
                $monthlyPerformance = $member->calculateMonthlyPerformance($month, date('Y'));
                $monthlyPerformances[] = $monthlyPerformance;
            }

            $reportData[] = [
                'member' => $member,
                'performances' => $performances, // Tambahkan data performa keseluruhan
                'monthlyPerformances' => $monthlyPerformances, // Tambahkan data performa bulanan
            ];

        }

        $rerata = 0;

        foreach ($reportData as $memberData) {
            // Pastikan bulan yang diminta ada di dalam array monthlyPerformances
            if (isset($memberData['monthlyPerformances'][$bulan_int - 1]['attendance_percentage'])) {
                $rerata += $memberData['monthlyPerformances'][$bulan_int - 1]['attendance_percentage'];
            } else {
                // Handle kasus jika data tidak ditemukan untuk bulan tersebut
                // Misalnya, log error atau berikan nilai default
                Log::warning('Attendance percentage not found for month ' . $bulan_int);
            }
        }

        return view('admin.division.report-pdf', compact('division', 'reportData', 'selectedMonth','bulan_int','pesan','rerata'));
    }

    public function destroy($id)
    {
        try {
            $division = Division::find($id);
            $division->delete();

            return redirect()->route('divisions.index')->with('success', 'Divisi berhasil dihapus.');
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Terjadi kesalahan dengan database. Silakan coba lagi.');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

}
