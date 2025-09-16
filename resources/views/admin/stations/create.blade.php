<x-app-layout>
  <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">เพิ่มสถานีชาร์จ</h2></x-slot>

  <div class="py-6 max-w-4xl mx-auto sm:px-6 lg:px-8">
    <form method="POST" action="{{ route('admin.stations.store') }}" class="bg-white shadow sm:rounded-lg p-6 space-y-4">
      @csrf
      @include('admin.stations._form', ['station' => null])
      <div class="flex gap-2">
        <button class="px-4 py-2 border rounded">บันทึก</button>
        <a href="{{ route('admin.stations.index') }}" class="px-4 py-2 border rounded">ยกเลิก</a>
      </div>
    </form>
  </div>
</x-app-layout>
