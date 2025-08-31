<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $station->name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 space-y-2">
                <p><strong>ที่อยู่:</strong> {{ $station->address ?? '-' }}</p>
                <p><strong>อำเภอ:</strong> {{ optional($station->district)->name ?? '-' }}</p>
                <p><strong>ตำบล:</strong> {{ optional($station->subdistrict)->name ?? '-' }}</p>
                <p><strong>สถานะ:</strong> {{ optional($station->status)->name ?? '-' }}</p>
                <p><strong>เวลาทำการ:</strong> {{ $station->operating_hours ?? '-' }}</p>
                <p><strong>ละติจูด:</strong> {{ $station->latitude ?? '-' }}</p>
                <p><strong>ลองจิจูด:</strong> {{ $station->longitude ?? '-' }}</p>

                <a href="{{ route('stations.index') }}" class="inline-block mt-4 text-blue-600 underline">
                    ← กลับไปหน้ารายการ
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
