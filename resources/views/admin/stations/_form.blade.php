@php
    $editing = isset($station);
@endphp

<div class="grid md:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm mb-1">ชื่อสถานี *</label>
        <input type="text" name="name" required class="w-full border rounded px-3 py-2"
               value="{{ old('name', $editing ? $station->name : '') }}">
        @error('name') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
    </div>

    <div>
        <label class="block text-sm mb-1">สถานะ *</label>
        <select name="status_id" class="w-full border rounded px-3 py-2">
            @foreach($statuses as $s)
                <option value="{{ $s->id }}" @selected(old('status_id', $editing ? $station->status_id : '') == $s->id)>{{ $s->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm mb-1">ที่อยู่</label>
        <textarea name="address" class="w-full border rounded px-3 py-2" rows="2">{{ old('address', $editing ? $station->address : '') }}</textarea>
    </div>

    <div>
        <label class="block text-sm mb-1">อำเภอ *</label>
        <select name="district_id" class="w-full border rounded px-3 py-2">
            @foreach($districts as $d)
                <option value="{{ $d->id }}" @selected(old('district_id', $editing ? $station->district_id : '') == $d->id)>{{ $d->name }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm mb-1">ตำบล</label>
        <select name="subdistrict_id" class="w-full border rounded px-3 py-2">
            <option value="">— เลือก —</option>
            @foreach($subdistricts as $sd)
                <option value="{{ $sd->id }}"
                    data-district="{{ $sd->district_id }}"
                    @selected(old('subdistrict_id', $editing ? $station->subdistrict_id : '') == $sd->id)>
                    {{ $sd->name }}
                </option>
            @endforeach
        </select>
        <small class="text-gray-500">* อาจกรองตามอำเภอด้วย JS ภายหลัง</small>
    </div>

    <div>
        <label class="block text-sm mb-1">Latitude</label>
        <input type="text" name="latitude" class="w-full border rounded px-3 py-2"
               value="{{ old('latitude', $editing ? $station->latitude : '') }}">
    </div>

    <div>
        <label class="block text-sm mb-1">Longitude</label>
        <input type="text" name="longitude" class="w-full border rounded px-3 py-2"
               value="{{ old('longitude', $editing ? $station->longitude : '') }}">
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm mb-1">เวลาทำการ</label>
        <input type="text" name="operating_hours" class="w-full border rounded px-3 py-2"
               value="{{ old('operating_hours', $editing ? $station->operating_hours : '') }}">
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm mb-1">ประเภทหัวชาร์จ</label>
        <div class="flex flex-wrap gap-3">
            @foreach($chargers as $c)
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox" name="charger_type_ids[]"
                           value="{{ $c->id }}"
                           @checked(in_array($c->id, old('charger_type_ids', $selectedChargers ?? [])))>
                    <span>{{ $c->name }}</span>
                </label>
            @endforeach
        </div>
    </div>
</div>
