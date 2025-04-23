<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;
use DB;
use Carbon\Carbon;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        try {
            $search = $request->input('search');

            // Ambil data member, jika ada pencarian, filter berdasarkan nama
            $members = Member::when(!empty($search), function ($query) use ($search) {
                $trimmedSearch = trim($search);
                return $query->whereRaw('LOWER(name) LIKE ?', [strtolower("%{$trimmedSearch}%")]); // Case-insensitive untuk MySQL
            })
            ->paginate(10);

            return view('client.index', compact('search', 'members'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    public function show(Request $request)
    {
        $member = Member::find($request->id); // Fetch the member by ID
        if (!$member) {
            return abort(404);
        }

        // Fetch all required data
        $attendances = \DB::table('attendances')->where('nrp', $member->nrp)->get(); // Fetch attendances for the member
        $participants = \DB::table('participants')->get()->keyBy('id');
        $volunteers = \DB::table('volunteers')->get()->keyBy('id');
        $activities = \DB::table('activities')->pluck('name', 'id'); // Map activity names by their IDs

        $monthlyPerformances = [];
        $totalAttendance = 0;
        $totalMustAttend = 0;
        $totalParticipation = 0;
        $totalEvents = 0;

        for ($month = 1; $month <= 12; $month++) {
            // Filter attendances for the specific month
            $monthlyAttendance = $attendances->filter(function ($attendance) use ($month) {
                try {
                    $attendanceMonth = Carbon::parse($attendance->created_at)->month;
                    return $attendanceMonth == $month;
                } catch (\Exception $e) {
                    return false;
                }
            });

            // Filter attendances with status 'hadir'
            $monthlyAttendanceFiltered = $monthlyAttendance->filter(function ($attendance) {
                return strtolower($attendance->status) === 'hadir';
            });

            // Get activity names for the member
            $activityNames = $monthlyAttendanceFiltered
                ->map(function ($attendance) use ($participants, $volunteers, $activities) {
                    $participantId = $attendance->participant_of;
                    $volunteerId = $attendance->volunteer_of;

                    $actId = $participants->get($participantId)->act_id ?? $volunteers->get($volunteerId)->act_id ?? null;
                    return $actId ? $activities->get($actId) : null;
                })
                ->filter() // Remove null values
                ->unique() // Ensure unique activity names
                ->values(); // Reset the keys

            // Calculate attendance and must attend counts
            $attendanceCount = $monthlyAttendanceFiltered->count();
            $mustAttendCount = $monthlyAttendance->count();

            // Update totals
            $totalAttendance += $attendanceCount;
            $totalMustAttend += $mustAttendCount;
            $totalParticipation += $activityNames->count();
            $totalEvents += $mustAttendCount;

            // Calculate attendance percentage
            $attendancePercentage = $mustAttendCount > 0
                ? ($attendanceCount / $mustAttendCount) * 100
                : 0;

            $monthlyPerformances[] = [
                'attendance_percentage' => $attendancePercentage,
                'attendance' => $attendanceCount,
                'must_attend' => $mustAttendCount,
                'activity_names' => $activityNames,
            ];
        }

        $performances = [
            'total_attendance' => $totalAttendance,
            'total_must_attend' => $totalMustAttend,
            'total_participation' => $totalParticipation,
            'total_event' => $totalEvents,
        ];

        return view('client.show', compact('member', 'monthlyPerformances', 'performances'));
    }
}
