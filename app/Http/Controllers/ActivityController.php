<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Departement;
use App\Models\Division;
use App\Models\Member;
use App\Models\Participant;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ActivityController extends Controller
{
    public function index()
    {
        try {
            $activities = Activity::all();
            return view('admin.activity.index', compact('activities'));
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Terjadi kesalahan dengan database. Silakan coba lagi.');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    public function create() // No Request needed here
    {
        try {
            $members = Member::all('nrp','name');
            $departements = Departement::all('id','name');
            $divisions = Division::all('id','name');
            return view('admin.activity.create',compact('members','departements','divisions')); // Return the create view
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    public function store(Request $request)
    {
        $request->validate([ // Validate the incoming request data
            'name' => 'required|string|max:255',
            'nrp' => 'required|string|max:255',
            'owned_by' => 'required|string',
            'category' => 'required|in:rapat,proker,lainnya', // Validate enum values
            'must_attend'=>'required|integer',
        ]);

        try {
            Activity::create($request->all()); // Use mass assignment (make sure $fillable is set in your model)
            return redirect()->route('activity.index')->with('success', 'Kegiatan berhasil ditambahkan.'); // Redirect back to index with a success message
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan dengan database. Silakan coba lagi.'); // Preserve input on error
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan. Silakan coba lagi.'); // Preserve input on error
        }
    }

    public function edit($id) // Get the ID from the route
    {
        try {
            $members = Member::all('nrp','name');
            $departements = Departement::all('id','name');
            $divisions = Division::all('id','name');
            $activity = Activity::find($id);
            if (!$activity) {
                return abort(404); // Handle if activity not found
            }
            return view('admin.activity.edit', compact('activity','members','divisions','departements'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    public function update(Request $request, $id) // Include ID in the route
    {
        $request->validate([ // Validate the incoming request data
            'name' => 'required|string|max:255',
            'nrp' => 'required|string|max:255',
            'owned_by' => 'required|string',
            'category' => 'required|in:rapat,proker,lainnya', // Validate enum values
            'must_attend'=>'required|integer',
        ]);

        try {
            $activity = Activity::find($id);
            if (!$activity) {
                return abort(404);
            }
            $activity->update($request->all()); // Update the activity
            return redirect()->route('activity.index')->with('success', 'Kegiatan berhasil diupdate.'); // Redirect back with success message
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan dengan database. Silakan coba lagi.');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    public function destroy($id) // Include ID in the route
    {
        try {
            $activity = Activity::find($id);
            if (!$activity) {
                return abort(404);
            }
            $activity->delete();
            return redirect()->route('activity.index')->with('success', 'Kegiatan berhasil dihapus.');
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
            $activity = Activity::find($id);
            $participants = Participant::where('act_id', $id)->get();
            $members = Member::all(); // Ambil semua member yang bisa dipilih

            if (!$activity) {
                return abort(404);
            }

            return view('admin.activity.show', compact('activity', 'participants', 'members'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

}
