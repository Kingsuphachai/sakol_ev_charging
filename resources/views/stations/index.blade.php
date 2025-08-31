<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            รายการสถานีชาร์จ
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="table-auto w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border px-4 py-2">ชื่อสถานี</th>
                            <th class="border px-4 py-2">อำเภอ</th>
                            <th class="border px-4 py-2">ตำบล</th>
                            <th class="border px-4 py-2">สถานะ</th>
                            <th class="border px-4 py-2">ดูรายละเอียด</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stations as $station)
                            <tr>
                                <td class="border px-4 py-2">{{ $station->name }}</td>
                                <td class="border px-4 py-2">{{ optional($station->district)->name ?? '-' }}</td>
                                <td class="border px-4 py-2">{{ optional($station->subdistrict)->name ?? '-' }}</td>
                                <td class="border px-4 py-2">{{ optional($station->status)->name ?? '-' }}</td>
                                <td class="border px-4 py-2">
                                    <a href="{{ route('stations.show', $station->id) }}" class="text-blue-600 underline">ดูเพิ่มเติม</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">ยังไม่มีข้อมูลสถานี</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $stations->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
