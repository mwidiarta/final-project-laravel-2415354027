@extends('layouts.app')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h3 class="text-2xl font-semibold text-gray-700">Data Subscriptions</h3>
    <button onclick="openModal('addDataModal')" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 shadow-sm">
        + Add Data
    </button>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 ">
    <div class="">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-sm text-gray-500 uppercase tracking-wider">
                    <th class="py-4 px-6 font-medium">No</th>
                    <th class="py-4 px-6 font-medium">Customer</th>
                    <th class="py-4 px-6 font-medium">Service</th>
                    <th class="py-4 px-6 font-medium">Start Date</th>
                    <th class="py-4 px-6 font-medium">End Date</th>
                    <th class="py-4 px-6 font-medium">Status</th>
                    <th class="py-4 px-6 font-medium text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                @forelse ($subscriptions as $index => $sub)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="py-4 px-6">{{ $index + 1 }}</td>
                        <td class="py-4 px-6 font-medium">{{ $sub['customer']['name'] ?? 'Unknown' }}</td>
                        <td class="py-4 px-6">{{ $sub['service']['name'] ?? 'Unknown' }}</td>
                        <td class="py-4 px-6">{{ $sub['start_date'] }}</td>
                        <td class="py-4 px-6">{{ $sub['end_date'] ?? '-' }}</td>
                        <td class="py-4 px-6">
                            @php
                                $statusColors = [
                                    'active' => 'bg-green-100 text-green-700',
                                    'inactive' => 'bg-red-100 text-red-700',
                                    'trial' => 'bg-blue-100 text-blue-700',
                                    'isolir' => 'bg-yellow-100 text-yellow-700',
                                    'dismantle' => 'bg-gray-200 text-gray-700'
                                ];
                                $color = $statusColors[$sub['status']] ?? 'bg-gray-100 text-gray-700';
                            @endphp
                            <span class="{{ $color }} px-2.5 py-1 rounded-full text-xs font-semibold capitalize">
                                {{ $sub['status'] }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-center relative">
                            <button onclick="toggleDropdown('dropdown-{{ $sub['id'] }}', event)" class="dropdown-btn bg-gray-100 hover:bg-gray-200 text-gray-600 px-3 py-1.5 rounded-md text-sm transition">
                                Aksi ▾
                            </button>
                            <div id="dropdown-{{ $sub['id'] }}" class="dropdown-menu hidden absolute right-6 top-12 mt-1 w-40 bg-white border border-gray-100 rounded-lg shadow-lg z-10 py-1 text-left">
                                <button onclick="openEditModal({{ json_encode($sub) }})" class="w-full text-left px-4 py-2 text-blue-600 hover:bg-gray-50 transition">
                                    Edit Data
                                </button>
                                
                                <div class="border-t border-gray-100 my-1"></div>
                                
                                @foreach(['active', 'inactive', 'trial', 'isolir', 'dismantle'] as $st)
                                    @if($sub['status'] !== $st)
                                        <form action="{{ route('subscriptions.updateStatus', $sub['id']) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="{{ $st }}">
                                            <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-50 transition capitalize">Set {{ $st }}</button>
                                        </form>
                                    @endif
                                @endforeach

                                <div class="border-t border-gray-100 my-1"></div>
                                
                                <form action="{{ route('subscriptions.destroy', $sub['id']) }}" method="POST" onsubmit="return confirm('Hapus subscription ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-gray-50 transition">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-gray-500">Belum ada data subscription.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="addDataModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeModal('addDataModal')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-xl shadow-xl sm:my-8 sm:align-middle sm:max-w-lg w-full">
            <form action="{{ route('subscriptions.store') }}" method="POST">
                @csrf
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">Tambah Subscription</h3>
                    <button type="button" onclick="closeModal('addDataModal')" class="text-gray-400 hover:text-gray-500 text-2xl leading-none">&times;</button>
                </div>
                <div class="px-6 py-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Customer</label>
                        <select name="customer_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 outline-none" required>
                            <option value="">-- Pilih Customer --</option>
                            @foreach($customers as $c)
                                <option value="{{ $c['id'] }}" {{ old('customer_id') == $c['id'] ? 'selected' : '' }}>{{ $c['name'] }} ({{ $c['customer_id'] }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Service</label>
                        <select name="service_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 outline-none" required>
                            <option value="">-- Pilih Service --</option>
                            @foreach($services as $s)
                                <option value="{{ $s['id'] }}" {{ old('service_id') == $s['id'] ? 'selected' : '' }}>{{ $s['name'] }} (Rp{{ number_format($s['price'], 0, ',', '.') }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                            <input type="date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 outline-none" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                            <input type="date" name="end_date" value="{{ old('end_date') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 outline-none">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 outline-none">
                            <option value="trial" {{ old('status') == 'trial' ? 'selected' : '' }}>Trial</option>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="isolir" {{ old('status') == 'isolir' ? 'selected' : '' }}>Isolir</option>
                            <option value="dismantle" {{ old('status') == 'dismantle' ? 'selected' : '' }}>Dismantle</option>
                        </select>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3 rounded-b-xl">
                    <button type="button" onclick="closeModal('addDataModal')" class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">Batal</button>
                    <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">Save Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="editDataModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeModal('editDataModal')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-xl shadow-xl sm:my-8 sm:align-middle sm:max-w-lg w-full">
            <form id="editForm" method="POST">
                @csrf @method('PATCH')
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">Edit Subscription</h3>
                    <button type="button" onclick="closeModal('editDataModal')" class="text-gray-400 hover:text-gray-500 text-2xl leading-none">&times;</button>
                </div>
                <div class="px-6 py-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Customer</label>
                        <select name="customer_id" id="edit_customer_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 outline-none" required>
                            @foreach($customers as $c)
                                <option value="{{ $c['id'] }}">{{ $c['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Service</label>
                        <select name="service_id" id="edit_service_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 outline-none" required>
                            @foreach($services as $s)
                                <option value="{{ $s['id'] }}">{{ $s['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                            <input type="date" name="start_date" id="edit_start_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 outline-none" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                            <input type="date" name="end_date" id="edit_end_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 outline-none">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" id="edit_status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 outline-none">
                            <option value="trial">Trial</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="isolir">Isolir</option>
                            <option value="dismantle">Dismantle</option>
                        </select>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3 rounded-b-xl">
                    <button type="button" onclick="closeModal('editDataModal')" class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">Batal</button>
                    <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

    function openEditModal(sub) {
        document.getElementById('editForm').action = `/subscriptions/${sub.id}`;
        document.getElementById('edit_customer_id').value = sub.customer_id;
        document.getElementById('edit_service_id').value = sub.service_id;
        
        // Memotong jam jika ada (YYYY-MM-DD)
        document.getElementById('edit_start_date').value = sub.start_date.split(' ')[0];
        if(sub.end_date) {
            document.getElementById('edit_end_date').value = sub.end_date.split(' ')[0];
        } else {
            document.getElementById('edit_end_date').value = '';
        }
        
        document.getElementById('edit_status').value = sub.status;
        openModal('editDataModal');
    }

    @if(session('open_modal'))
        document.addEventListener("DOMContentLoaded", function() { openModal('{{ session('open_modal') }}'); });
    @endif

    function toggleDropdown(id, event) {
        event.stopPropagation();
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            if(menu.id !== id) menu.classList.add('hidden');
        });
        document.getElementById(id).classList.toggle('hidden');
    }

    window.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown-btn')) {
            document.querySelectorAll('.dropdown-menu').forEach(menu => menu.classList.add('hidden'));
        }
    });
</script>
@endpush