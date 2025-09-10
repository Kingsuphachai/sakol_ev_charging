<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">แก้ไขสถานีชาร์จ</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-6">
                <form method="POST" action="{{ route('admin.stations.update', $station->id) }}">
                    @csrf
                    @method('PUT')

                    <!-- ชื่อสถานี -->
                    <div class="mb-4">
                        <label class="block font-medium">ชื่อสถานี</label>
                        <input type="text" name="name" value="{{ old('name', $station->name) }}" 
                               class="w-full border rounded p-2" required>
                        @error('name') <p class="text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- ที่อยู่ -->
                    <div class="mb-4">
                        <label class="block font-medium">ที่อยู่</label>
                        <textarea name="address" class="w-full border rounded p-2">{{ old('address', $station->address) }}</textarea>
                    </div>

                    <!-- อำเภอ -->
                    <div class="mb-4">
                        <label class="block font-medium">อำเภอ</label>
                        <select name="district_id" class="w-full border rounded p-2" required>
                            @foreach($districts as $district)
                                <option value="{{ $district->id }}" 
                                    {{ old('district_id', $station->district_id) == $district->id ? 'selected' : '' }}>
                                    {{ $district->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- ตำบล -->
                    <div class="mb-4">
                        <label class="block font-medium">ตำบล</label>
                        <select name="subdistrict_id" class="w-full border rounded p-2">
                            <option value="">-- เลือกตำบล --</option>
                            @foreach($subdistricts as $sub)
                                <option value="{{ $sub->id }}"
                                    {{ old('subdistrict_id', $station->subdistrict_id) == $sub->id ? 'selected' : '' }}>
                                    {{ $sub->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- สถานะ -->
                    <div class="mb-4">
                        <label class="block font-medium">สถานะ</label>
                        <select name="status_id" class="w-full border rounded p-2" required>
                            @foreach($statuses as $status)
                                <option value="{{ $status->id }}"
                                    {{ old('status_id', $station->status_id) == $status->id ? 'selected' : '' }}>
                                    {{ $status->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- เวลาทำการ -->
                    <div class="mb-4">
                        <label class="block font-medium">เวลาทำการ</label>
                        <input type="text" name="operating_hours" 
                               value="{{ old('operating_hours', $station->operating_hours) }}"
                               class="w-full border rounded p-2">
                    </div>

                    <!-- พิกัด -->
                    <div class="mb-4">
                        <label class="block font-medium">พิกัด (Lat, Lng)</label>
                        <div class="flex gap-2">
                            <input type="text" name="latitude" value="{{ old('latitude', $station->latitude) }}" 
                                   placeholder="Latitude" class="w-1/2 border rounded p-2">
                            <input type="text" name="longitude" value="{{ old('longitude', $station->longitude) }}" 
                                   placeholder="Longitude" class="w-1/2 border rounded p-2">
                        </div>
                    </div>

                    <!-- ประเภทหัวชาร์จ -->
                    <div class="mb-4">
                        <label class="block font-medium">ประเภทหัวชาร์จ</label>
                        <div class="flex flex-wrap gap-3">
                            @foreach($chargers as $c)
                                <label>
                                    <input type="checkbox" name="charger_type_ids[]" value="{{ $c->id }}"
                                        {{ in_array($c->id, old('charger_type_ids', $selectedChargers ?? [])) ? 'checked' : '' }}>
                                    {{ $c->name }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            อัปเดต
                        </button>
                        <a href="{{ route('admin.stations.index') }}" 
                           class="ml-3 px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                            ยกเลิก
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
