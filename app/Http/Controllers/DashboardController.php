<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Activity;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Models\Participant;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Ringkasan Statistik
        $stats = DB::table('attendances')
            ->selectRaw("
                COUNT(*) as total_attendance,
                SUM(CASE WHEN status = 'hadir' THEN 1 ELSE 0 END) as total_hadir
            ")
            ->first();

        $totalAttendance = $stats->total_attendance;
        $totalHadir = $stats->total_hadir;
        $averageAttendance = $totalAttendance > 0 ? ($totalHadir / $totalAttendance) * 100 : 0;

        $totalActivity = Activity::count();
        $totalParticipation = Participant::count();
        $totalMember = Member::count(); // Add this line to calculate total members
        
        // Grafik Partisipasi dan Kehadiran (Data Real)
        $year = date('Y'); // Tahun sekarang
        
        // Fetch all required data in batch queries
        $participantStats = Participant::selectRaw('MONTH(created_at) as month, COUNT(*) as total_participation')
        ->whereYear('created_at', $year)
        ->groupBy('month')
        ->get()
        ->keyBy('month');
        
        $attendanceStats = Attendance::selectRaw("MONTH(created_at) as month, 
        COUNT(*) as total_attendance, 
        SUM(CASE WHEN status = 'hadir' THEN 1 ELSE 0 END) as total_hadir")
        ->whereYear('created_at', $year)
        ->groupBy('month')
        ->get()
        ->keyBy('month');
        
        $activityStats = Activity::selectRaw('MONTH(created_at) as month, COUNT(*) as total_activities')
        ->whereYear('created_at', $year)
        ->groupBy('month')
        ->get()
        ->keyBy('month');
        
        // Process data for each month
        $participationData = [];
        $attendanceData = [];
        $labels = [];

        for ($month = 1; $month <= 12; $month++) {
            $totalPartisipasiBulanIni = $participantStats[$month]->total_participation ?? 0;
            $totalAttendanceBulanIni = $attendanceStats[$month]->total_attendance ?? 0;
            $totalHadirBulanIni = $attendanceStats[$month]->total_hadir ?? 0;
            $totalActivitiesBulanIni = $activityStats[$month]->total_activities ?? 0;

            $participationPercentage = $totalActivitiesBulanIni > 0 
                ? ($totalPartisipasiBulanIni / $totalActivitiesBulanIni) * 100 
                : 0;

            $attendancePercentage = $totalAttendanceBulanIni > 0 
                ? ($totalHadirBulanIni / $totalAttendanceBulanIni) * 100 
                : 0;

            $participationData[] = round($participationPercentage, 2);
            $attendanceData[] = round($attendancePercentage, 2);
            $labels[] = date("F", mktime(0, 0, 0, $month, 10)); // Nama bulan
        }
        
        // Kegiatan Terbaru (Ambil 5 data terbaru)
        $recentActivities = Activity::orderBy('created_at', 'desc')->take(5)->get();
        
        // Member dengan Partisipasi Tertinggi (Ambil 5 data teratas)
        $topMembers = DB::table('participants')
            ->select('nrp', DB::raw('count(*) as total_participation'))
            ->groupBy('nrp')
            ->orderBy('total_participation', 'desc')
            ->take(5)
            ->get();

        // Fetch all related members in one query
        $memberNrps = $topMembers->pluck('nrp'); // Extract nrp from topMembers
        $members = Member::whereIn('nrp', $memberNrps)->get()->keyBy('nrp'); // Fetch members and index by nrp

        // Add member names to topMembers
        foreach ($topMembers as $key => $member) {
            $member->name = $members[$member->nrp]->name ?? 'Unknown';
        }

        // Kegiatan yang Paling Banyak Diikuti (Ambil 5 data teratas)
        $topActivities = DB::table('participants')
            ->select('act_id', DB::raw('count(*) as total_participants'))
            ->groupBy('act_id')
            ->orderBy('total_participants', 'desc')
            ->take(5)
            ->get();

        // Fetch all related activities in one query
        $activityIds = $topActivities->pluck('act_id'); // Extract act_id from topActivities
        $activities = Activity::whereIn('id', $activityIds)->get()->keyBy('id'); // Fetch activities and index by id

        // Add activity names to topActivities
        foreach ($topActivities as $key => $activity) {
            $activity->name = $activities[$activity->act_id]->name ?? 'Unknown';
        }

        return view('admin.index', compact(
            'totalMember',
            'totalActivity',
            'totalParticipation',
            'averageAttendance',
            'participationData',
            'attendanceData',
            'recentActivities',
            'topMembers',
            'topActivities',
            'labels'
        ));
    }
}
