<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">จัดการสถานีชาร์จ</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-6">
                <div class="mb-4">
                    <a href="{{ route('admin.stations.create') }}" class="bg-gray-100 p-2 border">
                        + เพิ่มสถานี
                    </a>
                </div>

                <table class="w-full border">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="p-2 border">ชื่อสถานี</th>
                            <th class="p-2 border">ที่อยู่</th>
                            <th class="p-2 border">สถานะ</th>
                            <th class="p-2 border">อำเภอ</th>
                            <th class="p-2 border">ตำบล</th>
                            <th class="p-2 border">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stations as $station)
                            <tr>
                                <td class="p-2 border">{{ $station->name }}</td>
                                <td class="p-2 border">{{ $station->address }}</td>
                                <td class="p-2 border">{{ $station->status->name ?? '-' }}</td>
                                <td class="p-2 border">{{ $station->district->name ?? '-' }}</td>
                                <td class="p-2 border">{{ $station->subdistrict->name ?? '-' }}</td>
                                <td class="p-2 border">
                                    {{-- แก้ไข --}}
                                    <a href="{{ route('admin.stations.edit', $station) }}"
                                        class="text-blue-600 hover:underline">
                                        แก้ไข
                                    </a>

                                    <span class="mx-2 text-gray-300">|</span>

                                    {{-- ลบ (ต้องใช้ฟอร์ม + method DELETE) --}}
                                    <form action="{{ route('admin.stations.destroy', $station) }}" method="POST"
                                        class="inline" onsubmit="return confirm('ยืนยันลบสถานีนี้?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">
                                            ลบ
                                        </button>
                                    </form>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center p-3">ไม่มีข้อมูล</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
@if (session('success'))
    <script>
        alert(@json(session('success')));
    </script>
@endif