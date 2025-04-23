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
        $bulan_int = (int) $bulan;

        $participants = \DB::table('participants')->get()->keyBy('id');
        $volunteers = \DB::table('volunteers')->get()->keyBy('id');
        $activities = \DB::table('activities')->pluck('name', 'id');
        
        // Fetch division and members with eager loading
        $division = Division::with('members')->find($id);
        if (!$division) {
            abort(404);
        }

        $memberIds = $division->members->pluck('nrp');

        $attendanceData = \DB::table('attendances')
            ->whereIn('nrp', $memberIds)
            ->get()
            ->groupBy('nrp');

        $reportData = [];
        $rerata = 0;
        $totalMembers = 0;
        
        foreach ($division->members as $member) {
            $monthlyPerformances = [];
            $memberAttendance = $attendanceData->get($member->nrp, collect());

            for ($month = 1; $month <= 12; $month++) {
                $monthlyAttendance = $memberAttendance->filter(function ($attendance) use ($month) {
                    try {
                        $attendanceMonth = Carbon::parse($attendance->created_at)->month;
                        return $attendanceMonth == $month;
                    } catch (\Exception $e) {
                        return false;
                    }
                });

                $activityNames = $monthlyAttendance
                ->map(function ($attendance) use ($participants, $volunteers, $activities) {
                    // Get the participant or volunteer ID
                    $participantId = $attendance->participant_of;
                    $volunteerId = $attendance->volunteer_of;

                    // Retrieve the act_id from participants or volunteers
                    $actId = $participants->get($participantId)->act_id ?? $volunteers->get($volunteerId)->act_id ?? null;

                    // Retrieve the activity name from the activities table
                    return $actId ? $activities->get($actId) : null;
                })
                ->filter() // Remove null values
                ->values();

                $attendanceCount = $monthlyAttendance->filter(function ($attendance) {
                    return strtolower($attendance->status) === 'hadir';
                })->count();

                $mustAttendCount = $monthlyAttendance->count();

                $attendancePercentage = $mustAttendCount > 0
                    ? ($attendanceCount / $mustAttendCount) * 100
                    : 0;

                $monthlyPerformances[] = [
                    'attendance_percentage' => $attendancePercentage,
                    'activityName' => $activityNames,
                    'attendance' => $attendanceCount,
                    'must_attend' => $mustAttendCount,
                    'recommendation' => $attendancePercentage >= 75 ? 'Good' : 'Needs Improvement',
                ];
            }

            $reportData[] = [
                'member' => $member,
                'monthlyPerformances' => $monthlyPerformances,
            ];

            if (isset($monthlyPerformances[$bulan_int - 1]['attendance_percentage'])) {
                $rerata += $monthlyPerformances[$bulan_int - 1]['attendance_percentage'];
                $totalMembers++;
            }
        }

        // Calculate average attendance percentage
        $rerata = $totalMembers > 0 ? $rerata / $totalMembers : 0;

        return view('admin.division.report', compact('division', 'reportData', 'selectedMonth', 'rerata'));
    }

    public function generate($id, Request $request)
    {
        $selectedMonth = $request->input('month', Carbon::now()->format('Y-m'));
        $pesan = $request->pesan;
        $bulan = explode("-", $selectedMonth)[1];
        $bulan_int = (int) $bulan;

        $participants = \DB::table('participants')->get()->keyBy('id');
        $volunteers = \DB::table('volunteers')->get()->keyBy('id');
        $activities = \DB::table('activities')->pluck('name', 'id'); // Map activity names by their IDs

        // Fetch division and members
        $division = Division::with('members')->find($id);
        if (!$division) {
            abort(404);
        }

        $memberIds = $division->members->pluck('nrp');

        $attendanceData = \DB::table('attendances')
            ->whereIn('nrp', $memberIds)
            ->get()
            ->groupBy('nrp');

        $reportData = [];
        $rerata = 0;
        $totalMembers = 0;

        foreach ($division->members as $member) {
            $monthlyPerformances = [];
            $memberAttendance = $attendanceData->get($member->nrp, collect());

            for ($month = 1; $month <= 12; $month++) {
                // Filter attendance by month
                $monthlyAttendance = $memberAttendance->filter(function ($attendance) use ($month) {
                    try {
                        $attendanceMonth = Carbon::parse($attendance->created_at)->month;
                        return $attendanceMonth == $month;
                    } catch (\Exception $e) {
                        return false;
                    }
                });

                $monthlyAttendanceFiltered = $memberAttendance->filter(function ($attendance) use ($month) {
                    try {
                        $attendanceMonth = Carbon::parse($attendance->created_at)->month;
                        return $attendanceMonth == $month && strtolower($attendance->status) === 'hadir';
                    } catch (\Exception $e) {
                        return false;
                    }
                });

                $activityNames = $monthlyAttendanceFiltered
                ->map(function ($attendance) use ($participants, $volunteers, $activities) {
                    // Get the participant or volunteer ID
                    $participantId = $attendance->participant_of;
                    $volunteerId = $attendance->volunteer_of;

                    // Retrieve the act_id from participants or volunteers
                    $actId = $participants->get($participantId)->act_id ?? $volunteers->get($volunteerId)->act_id ?? null;

                    // Retrieve the activity name from the activities table
                    return $actId ? $activities->get($actId) : null;
                })
                ->filter() // Remove null values
                ->values();

                // Calculate attendance percentage
                $attendanceCount = $monthlyAttendance->filter(function ($attendance) {
                    return strtolower($attendance->status) === 'hadir';
                })->count();

                $mustAttendCount = $monthlyAttendance->count();

                $attendancePercentage = $mustAttendCount > 0
                    ? ($attendanceCount / $mustAttendCount) * 100
                    : 0;

                $monthlyPerformances[] = [
                    'attendance_percentage' => $attendancePercentage,
                    'activityName' => $activityNames,
                    'attendance' => $attendanceCount,
                    'must_attend' => $mustAttendCount,
                    'recommendation' => $attendancePercentage >= 90
                        ? 'Great'
                        : ($attendancePercentage >= 75
                            ? 'Good'
                            : 'Needs Improvement'),
                ];
            }

            $reportData[] = [
                'member' => $member,
                'monthlyPerformances' => $monthlyPerformances,
            ];

            // Calculate average attendance for the selected month
            if (isset($monthlyPerformances[$bulan_int - 1]['attendance_percentage'])) {
                $rerata += $monthlyPerformances[$bulan_int - 1]['attendance_percentage'];
                $totalMembers++;
            }
        }

        // Calculate average attendance percentage
        $rerata = $totalMembers > 0 ? $rerata / $totalMembers : 0;

        return view('admin.division.report-pdf', compact('division', 'reportData', 'selectedMonth', 'bulan_int', 'pesan', 'rerata'));
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
