<?php

namespace App\Http\Controllers;

use App\Models\ChargingStation;
use App\Models\User;
use App\Models\StationStatus;
use App\Models\District;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // หน้า dashboard หลัก (ลิงก์ไป 2 ตาราง)
    public function dashboard()
    {
        $stats = [
            'stations_total' => ChargingStation::count(),
            'stations_visible' => ChargingStation::whereIn('status_id', [1,2])->count(),
            'users_total' => User::count(),
            'admins' => User::where('role_id', 2)->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    // ตารางสถานี + ค้นหา
    public function stations(Request $request)
    {
        $request->validate([
            'q' => ['nullable','string','max:255'],
            'status_id' => ['nullable','integer'],
            'district_id' => ['nullable','integer'],
        ]);

        $query = ChargingStation::with(['status','district'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('q')) {
            $q = trim($request->q);
            $query->where('name','like',"%{$q}%");
        }
        if ($request->filled('status_id') || $request->status_id === '0') {
            $query->where('status_id', $request->status_id);
        }
        if ($request->filled('district_id')) {
            $query->where('district_id', $request->district_id);
        }

        $stations = $query->paginate(15)->withQueryString();

        return view('admin.stations', [
            'stations' => $stations,
            'statuses' => StationStatus::orderBy('id')->get(['id','name']),
            'districts' => District::orderBy('name')->get(['id','name']),
            'filters' => $request->only(['q','status_id','district_id']),
        ]);
    }

    // ตารางผู้ใช้ + ค้นหาเบา ๆ
    public function users(Request $request)
    {
        $request->validate([
            'q' => ['nullable','string','max:255'],
            'role_id' => ['nullable','integer'],
        ]);

        $query = User::query()->orderBy('created_at','desc');

        if ($request->filled('q')) {
            $q = trim($request->q);
            $query->where(function($sub) use ($q) {
                $sub->where('name','like',"%{$q}%")
                    ->orWhere('email','like',"%{$q}%");
            });
        }
        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }

        $users = $query->paginate(15)->withQueryString();

        return view('admin.users', [
            'users' => $users,
            'filters' => $request->only(['q','role_id']),
        ]);
    }
}
