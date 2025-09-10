<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChargingStation;
use App\Models\District;
use App\Models\Subdistrict;
use App\Models\ChargerType;
use App\Models\StationStatus;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StationController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'q' => ['nullable','string','max:255'],
            'district_id' => ['nullable','integer'],
            'status_id' => ['nullable','integer'],
        ]);

        $q = ChargingStation::query()
            ->with(['status','district','subdistrict','chargers']);

        if ($request->filled('q')) {
            $q->where('name','like','%'.$request->q.'%');
        }
        if ($request->filled('district_id')) {
            $q->where('district_id', $request->district_id);
        }
        if ($request->filled('status_id') || $request->status_id === '0') {
            $q->where('status_id', $request->status_id);
        }

        $stations = $q->orderBy('id','desc')->paginate(15)->withQueryString();

        return view('admin.stations.index', [
            'stations' => $stations,
            'districts' => District::orderBy('name')->get(['id','name']),
            'statuses'  => StationStatus::orderBy('id')->get(['id','name']),
            'filters'   => $request->only('q','district_id','status_id'),
        ]);
    }

    public function create()
    {
        return view('admin.stations.create', [
            'districts'    => District::orderBy('name')->get(['id','name']),
            'subdistricts' => Subdistrict::orderBy('name')->get(['id','name','district_id']),
            'statuses'     => StationStatus::orderBy('id')->get(['id','name']),
            'chargers'     => ChargerType::orderBy('name')->get(['id','name']),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'            => ['required','string','max:255'],
            'address'         => ['nullable','string'],
            'district_id'     => ['required','integer', Rule::exists('districts','id')],
            'subdistrict_id'  => ['nullable','integer', Rule::exists('subdistricts','id')],
            'status_id'       => ['required','integer', Rule::exists('station_statuses','id')],
            'latitude'        => ['nullable','numeric'],
            'longitude'       => ['nullable','numeric'],
            'operating_hours' => ['nullable','string','max:100'],
            'charger_type_ids'=> ['array'],
            'charger_type_ids.*' => [Rule::exists('charger_types','id')],
        ]);

        $station = ChargingStation::create([
            'name'            => $data['name'],
            'address'         => $data['address'] ?? null,
            'district_id'     => $data['district_id'],
            'subdistrict_id'  => $data['subdistrict_id'] ?? null,
            'status_id'       => $data['status_id'],
            'latitude'        => $data['latitude'] ?? null,
            'longitude'       => $data['longitude'] ?? null,
            'operating_hours' => $data['operating_hours'] ?? null,
            'created_by'      => auth()->id(),
        ]);

        if (!empty($data['charger_type_ids'])) {
            $station->chargers()->sync($data['charger_type_ids']);
        }

        return redirect()->route('admin.stations.index')->with('success','เพิ่มสถานีเรียบร้อย');
    }

    public function edit(ChargingStation $station)
    {
        $station->load('chargers');

        return view('admin.stations.edit', [
            'station'      => $station,
            'districts'    => District::orderBy('name')->get(['id','name']),
            'subdistricts' => Subdistrict::orderBy('name')->get(['id','name','district_id']),
            'statuses'     => StationStatus::orderBy('id')->get(['id','name']),
            'chargers'     => ChargerType::orderBy('name')->get(['id','name']),
            'selectedChargers' => $station->chargers->pluck('id')->all(),
        ]);
    }

    public function update(Request $request, ChargingStation $station)
    {
        $data = $request->validate([
            'name'            => ['required','string','max:255'],
            'address'         => ['nullable','string'],
            'district_id'     => ['required','integer', Rule::exists('districts','id')],
            'subdistrict_id'  => ['nullable','integer', Rule::exists('subdistricts','id')],
            'status_id'       => ['required','integer', Rule::exists('station_statuses','id')],
            'latitude'        => ['nullable','numeric'],
            'longitude'       => ['nullable','numeric'],
            'operating_hours' => ['nullable','string','max:100'],
            'charger_type_ids'=> ['array'],
            'charger_type_ids.*' => [Rule::exists('charger_types','id')],
        ]);

        $station->update($data);
        $station->chargers()->sync($data['charger_type_ids'] ?? []);

        return redirect()->route('admin.stations.index')->with('success','อัปเดตสถานีเรียบร้อย');
    }

    public function destroy(ChargingStation $station)
    {
        $station->chargers()->detach();
        $station->delete();

        return back()->with('success','ลบสถานีเรียบร้อย');
    }
}
