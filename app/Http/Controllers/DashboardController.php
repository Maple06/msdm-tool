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
        $totalMember = Member::count();
        $totalActivity = Activity::count();
        $totalParticipation = Participant::count();  // Menggunakan model Participant
        $totalHadir = Attendance::where('status', 'hadir')->count();
        $totalAttendance = Attendance::count();
        $averageAttendance = $totalAttendance > 0 ? ($totalHadir / $totalAttendance) * 100 : 0;


        // Grafik Partisipasi dan Kehadiran (Data Real)
        $participationData = [];
        $attendanceData = [];
        $labels = []; // Array untuk label bulan

        for ($month = 1; $month <= 12; $month++) {
            $year = date('Y'); // Tahun sekarang
            $totalPartisipasiBulanIni = Participant::whereMonth('created_at', $month)->whereYear('created_at', $year)->count();
            $totalHadirBulanIni = Attendance::where('status', 'hadir')->whereMonth('created_at', $month)->whereYear('created_at', $year)->count();
            $totalAttendanceBulanIni = Attendance::whereMonth('created_at', $month)->whereYear('created_at', $year)->count();
            $participationPercentage = $totalPartisipasiBulanIni > 0 ? ($totalPartisipasiBulanIni / Activity::whereMonth('created_at', $month)->whereYear('created_at', $year)->count())*100 : 0;
            $attendancePercentage = $totalAttendanceBulanIni > 0 ? ($totalHadirBulanIni / $totalAttendanceBulanIni) * 100 : 0;


            $participationData[] = round($participationPercentage,2);
            $attendanceData[] = round($attendancePercentage,2);
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
            foreach ($topMembers as $key => $member) {
                $memberData = Member::where('nrp', $member->nrp)->first();
                $topMembers[$key]->name = $memberData->name;
            }


        // Kegiatan yang Paling Banyak Diikuti (Ambil 5 data teratas)
        $topActivities = DB::table('participants')
            ->select('act_id', DB::raw('count(*) as total_participants'))
            ->groupBy('act_id')
            ->orderBy('total_participants', 'desc')
            ->take(5)
            ->get();

            foreach ($topActivities as $key => $activity) {
                $activityData = Activity::where('id', $activity->act_id)->first();
                $topActivities[$key]->name = $activityData->name;
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
