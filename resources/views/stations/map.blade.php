<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            แผนที่สถานีชาร์จ (ภาพรวม)
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-0 overflow-hidden">
                <div id="map" class="w-full" style="height: 70vh;"></div>
            </div>
        </div>
    </div>

    <script>
        const API_URL = @json(route('api.stations'));
        const SHOW_BASE_URL = @json(url('/stations')); // ใช้ประกอบลิงก์ /stations/{id}

        function initMap() {
            // default center = Sakon Nakhon
            const center = { lat: 17.1545, lng: 104.1347 };

            const map = new google.maps.Map(document.getElementById('map'), {
                center,
                zoom: 11,
            });

            const info = new google.maps.InfoWindow();
            const bounds = new google.maps.LatLngBounds();

            fetch(API_URL)
                .then(r => r.json())
                .then(items => {
                    if (!Array.isArray(items) || items.length === 0) {
                        // ไม่มีสถานี/ไม่มีพิกัด
                        return;
                    }

                    items.forEach(s => {
                        if (typeof s.lat !== 'number' || typeof s.lng !== 'number') return;

                        const pos = { lat: s.lat, lng: s.lng };
                        const marker = new google.maps.Marker({
                            position: pos,
                            map,
                            title: s.name,
                        });

                        const addrLine = s.address ? `<div class="text-sm">${s.address}</div>` : '';
                        const areaLine = (s.subdistrict || s.district) ? 
                            `<div class="text-xs text-gray-600">${[s.subdistrict, s.district].filter(Boolean).join(' • ')}</div>` : '';
                        const content = `
                            <div style="min-width:220px">
                                <div style="font-weight:600">${s.name}</div>
                                ${addrLine}
                                ${areaLine}
                                <div class="text-xs mt-1">สถานะ: ${s.status ?? '-'}</div>
                                <div class="mt-2">
                                    <a href="${SHOW_BASE_URL}/${s.id}" class="text-blue-600 underline">ดูรายละเอียด</a>
                                </div>
                            </div>
                        `;

                        marker.addListener('click', () => {
                            info.setContent(content);
                            info.open(map, marker);
                        });

                        bounds.extend(pos);
                    });

                    // ปรับ viewport ให้เห็นทุกหมุด
                    map.fitBounds(bounds);
                })
                .catch(err => {
                    console.error('Load stations failed:', err);
                });
        }

        // ให้ callback เรียกได้
        window.initMap = initMap;
    </script>

    {{-- โหลด Maps แบบ async + callback (ใช้ key จาก config/services.php) --}}
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&callback=initMap">
    </script>
</x-app-layout>
