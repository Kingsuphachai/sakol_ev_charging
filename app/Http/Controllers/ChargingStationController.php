<?php

namespace App\Http\Controllers;

use App\Models\ChargingStation;
use App\Models\District;
use App\Models\Subdistrict;
use App\Models\ChargerType;
use App\Models\StationStatus;
use Illuminate\Http\Request;

class ChargingStationController extends Controller
{
    /**
     * แสดงรายการสถานี + ค้นหา/กรอง
     */
    public function index(Request $request)
    {
        // validate พารามิเตอร์ค้นหาแบบเบา ๆ
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:255'],          // ชื่อสถานี
            'district_id' => ['nullable', 'integer'],
            'subdistrict_id' => ['nullable', 'integer'],
            'status_id' => ['nullable', 'integer'],
            'charger_type_id' => ['nullable', 'integer'],
        ]);

        $query = ChargingStation::query()
            ->with(['status', 'district', 'subdistrict', 'chargers']);

        // ค้นหาตามชื่อสถานี
        if ($request->filled('q')) {
            $q = trim($request->q);
            $query->where('name', 'like', "%{$q}%");
        }

        // กรองตามอำเภอ/ตำบล/สถานะ/ประเภทหัวชาร์จ
        if ($request->filled('district_id')) {
            $query->where('district_id', $request->district_id);
        }

        if ($request->filled('subdistrict_id')) {
            $query->where('subdistrict_id', $request->subdistrict_id);
        }

        if ($request->filled('status_id') || $request->status_id === '0') {
            // รองรับค่า 0 (รอตรวจสอบ) ด้วย
            $query->where('status_id', $request->status_id);
        }

        if ($request->filled('charger_type_id')) {
            // relation ใน model ชื่อ "chargers"
            $query->whereHas('chargers', function ($q) use ($request) {
                $q->where('charger_types.id', $request->charger_type_id);
            });
        }

        $stations = $query->orderBy('name')->paginate(15)->withQueryString();

        // ถ้าอยากเอาไปใช้กับหน้าแผนที่แบบ Ajax/JSON ก็รองรับด้วย
        if ($request->wantsJson()) {
            return response()->json($stations);
        }

        // dropdown data สำหรับฟิลเตอร์ใน view
        $districts = District::orderBy('name')->get(['id','name']);
        $subdistricts = Subdistrict::orderBy('name')->get(['id','name','district_id']);
        $chargerTypes = ChargerType::orderBy('name')->get(['id','name']);
        $statuses = StationStatus::orderBy('id')->get(['id','name','is_visible']);

        return view('stations.index', compact(
            'stations', 'districts', 'subdistricts', 'chargerTypes', 'statuses'
        ));
    }

    /**
     * แสดงรายละเอียดสถานีเดียว
     */
    public function show($id, Request $request)
    {
        $station = ChargingStation::with(['status', 'district', 'subdistrict', 'chargers', 'creator'])
            ->findOrFail($id);

        if ($request->wantsJson()) {
            return response()->json($station);
        }

        return view('stations.show', compact('station'));
    }
}
