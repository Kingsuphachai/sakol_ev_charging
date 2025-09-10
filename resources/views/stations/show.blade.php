<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $station->name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-6 space-y-4">
                <h3 class="text-lg font-semibold text-gray-700">รายละเอียดสถานี</h3>

                <div><strong>ชื่อสถานี:</strong> {{ $station->name }}</div>
                <div><strong>ที่อยู่:</strong> {{ $station->address ?? '-' }}</div>
                <div><strong>สถานะ:</strong> {{ $station->status->name ?? '-' }}</div>
                <div><strong>เวลาทำการ:</strong> {{ $station->operating_hours ?? 'ไม่ระบุ' }}</div>

                <div>
                    <strong>ประเภทหัวชาร์จ:</strong>
                    @forelse ($station->chargers ?? [] as $charger)
                        <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-md text-sm mr-2">
                            {{ $charger->name }}
                        </span>
                    @empty
                        <span class="text-gray-500">-</span>
                    @endforelse
                </div>

                <div><strong>พิกัด:</strong>
                    @if($station->latitude && $station->longitude)
                        {{ $station->latitude }}, {{ $station->longitude }}
                    @else
                        <span class="text-gray-500">ยังไม่ระบุ</span>
                    @endif
                </div>

                {{-- 🗺️ แผนที่ (เฉพาะเมื่อมีพิกัด) --}}
                @if($station->latitude && $station->longitude)
                    <div id="map" style="width: 100%; height: 400px; margin-top: 1rem; border: 1px solid #ddd; border-radius: 8px;"></div>
                @else
                    <div class="mt-4 p-3 border rounded-md bg-gray-50 text-gray-600">
                        ยังไม่มีพิกัดสำหรับแสดงแผนที่
                    </div>
                @endif

                <div class="pt-4">
                    <a href="{{ route('stations.index') }}"
                        class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                        ← กลับไปยังรายการ
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if($station->latitude && $station->longitude)
        <script>
            function initMap() {
                const rawLat = @json($station->latitude);
                const rawLng = @json($station->longitude);
                console.log("Raw lat from Blade:", rawLat, "typeof", typeof rawLat);
                console.log("Raw lng from Blade:", rawLng, "typeof", typeof rawLng);

                const center = {
                    lat: parseFloat(rawLat) || 17.1545,
                    lng: parseFloat(rawLng) || 104.1347,
                };
                console.log("Center used:", center);

                const mapEl = document.getElementById("map");
                console.log("Map element exists?", !!mapEl, "size:", mapEl?.offsetWidth, "x", mapEl?.offsetHeight);

                const map = new google.maps.Map(mapEl, {
                    zoom: 13,
                    center,
                });

                new google.maps.Marker({
                    map,
                    position: center,
                    title: @json($station->name),
                });
            }
            window.initMap = initMap;

        </script>

        <script async defer
            src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&callback=initMap">
            </script>


    @endif
</x-app-layout>