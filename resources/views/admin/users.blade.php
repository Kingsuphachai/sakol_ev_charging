<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">จัดการผู้ใช้</h2>
    </x-slot>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
        <form method="GET" class="bg-white p-4 rounded shadow flex flex-wrap gap-3 items-end">
            <div>
                <label class="block text-sm text-gray-600">ค้นหา (ชื่อ/อีเมล)</label>
                <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" class="border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm text-gray-600">บทบาท</label>
                <select name="role_id" class="border rounded px-3 py-2">
                    <option value="">ทั้งหมด</option>
                    <option value="1" @selected(($filters['role_id'] ?? '')==='1')>user</option>
                    <option value="2" @selected(($filters['role_id'] ?? '')==='2')>admin</option>
                </select>
            </div>
            <div>
                <button class="px-4 py-2 bg-indigo-600 text-white rounded">ค้นหา</button>
            </div>
        </form>

        <div class="bg-white rounded shadow overflow-x-auto">
          <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-3 py-2 text-left">ชื่อ</th>
                <th class="px-3 py-2 text-left">อีเมล</th>
                <th class="px-3 py-2 text-left">บทบาท</th>
                <th class="px-3 py-2 text-left">สร้างเมื่อ</th>
              </tr>
            </thead>
            <tbody>
              @forelse($users as $u)
                <tr class="border-t">
                  <td class="px-3 py-2">{{ $u->name }}</td>
                  <td class="px-3 py-2">{{ $u->email }}</td>
                  <td class="px-3 py-2">{{ $u->role_id == 2 ? 'admin' : 'user' }}</td>
                  <td class="px-3 py-2">{{ $u->created_at }}</td>
                </tr>
              @empty
                <tr><td colspan="4" class="px-3 py-6 text-center text-gray-500">ไม่พบข้อมูล</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <div>{{ $users->links() }}</div>
      </div>
    </div>
</x-app-layout>
