<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;
use DB;

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

            // Hitung performa untuk tiap member
            // $performances = $members->map(function ($member) {
            //     return [
            //         'name' => $member->name,
            //         'performance' => $member->calculatePerformance(),
            //     ];
            // });

            return view('client.index', compact( 'search','members'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    public function show(Request $request)
    {
        $member = Member::find($request->id); // Ambil member berdasarkan ID
        if (!$member) {
            return abort(404);
        }

        $performances = $member->calculatePerformance();

        $monthlyPerformances = [];
        for ($month = 1; $month <= 12; $month++) {
            $monthlyPerformance = $member->calculateMonthlyPerformance($month, date('Y')); // Gunakan tahun текущий
            $monthlyPerformances[] = $monthlyPerformance;
        }

        return view('client.show', compact('member', 'monthlyPerformances','performances'));
    }

}
