<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('รายการสถานีชาร์จในจังหวัดสกลนคร') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- ฟอร์มค้นหา / ฟิลเตอร์ -->
            <form method="GET" action="{{ route('stations.index') }}" class="mb-6 flex gap-4">
                <input type="text" name="q" value="{{ request('q') }}"
                       placeholder="ค้นหาชื่อสถานี..."
                       class="border-gray-300 rounded-md shadow-sm">

                <select name="district_id" class="border-gray-300 rounded-md shadow-sm">
                    <option value="">-- เลือกอำเภอ --</option>
                    @foreach ($districts as $district)
                        <option value="{{ $district->id }}" {{ request('district_id') == $district->id ? 'selected' : '' }}>
                            {{ $district->name }}
                        </option>
                    @endforeach
                </select>

                <select name="status_id" class="border-gray-300 rounded-md shadow-sm">
                    <option value="">-- เลือกสถานะ --</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status->id }}" {{ request('status_id') == $status->id ? 'selected' : '' }}>
                            {{ $status->name }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    ค้นหา
                </button>
            </form>

            <!-- ตารางรายการสถานี -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">ชื่อสถานี</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">อำเภอ</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">สถานะ</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($stations as $station)
                            <tr>
                                <td class="px-6 py-4">{{ $station->name }}</td>
                                <td class="px-6 py-4">{{ $station->district->name ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $station->status->name ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('stations.show', $station->id) }}"
                                       class="text-blue-600 hover:text-blue-900">ดูรายละเอียด</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                    ไม่พบสถานีชาร์จ
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- pagination -->
            <div class="mt-4">
                {{ $stations->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
