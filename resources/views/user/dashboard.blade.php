<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            แผนที่สถานีชาร์จ
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-6">
                <div id="map" class="w-full rounded-md border" style="height:70vh;"></div>
            </div>
        </div>
    </div>

    <script>
        const API_URL = @json(route('api.stations'));

        function isValidCoord(v) {
            const n = typeof v === 'string' ? parseFloat(v) : v;
            return Number.isFinite(n) ? n : null;
        }

        function initMap() {
            const fallbackCenter = { lat: 17.1545, lng: 104.1347 }; // Sakon Nakhon
            const map = new google.maps.Map(document.getElementById("map"), {
                center: fallbackCenter,
                zoom: 11,
            });

            const info = new google.maps.InfoWindow();
            const bounds = new google.maps.LatLngBounds();
            let added = 0;

            fetch(API_URL, { headers: { 'Accept': 'application/json' } })
                .then(r => r.json())
                .then(items => {
                    items.forEach(s => {
                        const lat = isValidCoord(s.lat);
                        const lng = isValidCoord(s.lng);
                        if (lat === null || lng === null) return; // ข้ามถ้าไม่มีพิกัด

                        const pos = { lat, lng };

                        const marker = new google.maps.Marker({
                            position: pos,
                            map,
                            title: s.name || 'EV Station',
                        });

                        const html = `
                            <div style="min-width:220px">
                                <strong>${s.name ?? '-'}</strong><br>
                                ${s.address ?? ''}<br>
                                สถานะ: ${s.status ?? '-'}<br>
                                <a href="/stations/${s.id}" class="text-blue-600 underline">ดูรายละเอียด</a>
                            </div>
                        `;

                        marker.addListener("click", () => {
                            info.setContent(html);
                            info.open(map, marker);
                        });

                        bounds.extend(pos);
                        added++;
                    });

                    if (added > 1) {
                        map.fitBounds(bounds);
                    } else if (added === 1) {
                        map.setCenter(bounds.getCenter());
                        map.setZoom(14);
                    } else {
                        // ไม่มีสถานีพิกัดครบ
                        const div = document.createElement('div');
                        div.className = 'px-3 py-2 bg-yellow-50 border border-yellow-200 text-yellow-800 rounded text-sm';
                        div.textContent = 'ยังไม่มีพิกัดสถานีสำหรับแสดงบนแผนที่';
                        map.controls[google.maps.ControlPosition.TOP_CENTER].push(div);
                    }
                })
                .catch(err => {
                    console.error('Load stations failed:', err);
                    const div = document.createElement('div');
                    div.className = 'px-3 py-2 bg-red-50 border border-red-200 text-red-800 rounded text-sm';
                    div.textContent = 'โหลดข้อมูลสถานีไม่สำเร็จ';
                    map.controls[google.maps.ControlPosition.TOP_CENTER].push(div);
                });
        }

        window.initMap = initMap;
    </script>

    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&callback=initMap">
    </script>
</x-app-layout>
