@extends('layouts.app')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h3 class="text-2xl font-semibold text-gray-700">Data Customers</h3>
    <button onclick="openModal('addDataModal')" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 shadow-sm">
        + Add Data
    </button>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-sm text-gray-500 uppercase tracking-wider">
                    <th class="py-4 px-6 font-medium">No</th>
                    <th class="py-4 px-6 font-medium">Customer ID</th>
                    <th class="py-4 px-6 font-medium">Name</th>
                    <th class="py-4 px-6 font-medium">Email</th>
                    <th class="py-4 px-6 font-medium">Phone</th>
                    <th class="py-4 px-6 font-medium">Status</th>
                    <th class="py-4 px-6 font-medium text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                @forelse ($customers as $index => $customer)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="py-4 px-6">{{ $index + 1 }}</td>
                        <td class="py-4 px-6 font-medium">{{ $customer['customer_id'] }}</td>
                        <td class="py-4 px-6">{{ $customer['name'] }}</td>
                        <td class="py-4 px-6">{{ $customer['email'] ?? '-' }}</td>
                        <td class="py-4 px-6">{{ $customer['phone'] ?? '-' }}</td>
                        <td class="py-4 px-6">
                            @if ($customer['status'])
                                <span class="bg-green-100 text-green-700 px-2.5 py-1 rounded-full text-xs font-semibold">Active</span>
                            @else
                                <span class="bg-red-100 text-red-700 px-2.5 py-1 rounded-full text-xs font-semibold">Inactive</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-center relative">
                            <button onclick="toggleDropdown('dropdown-{{ $customer['id'] }}', event)" class="dropdown-btn bg-gray-100 hover:bg-gray-200 text-gray-600 px-3 py-1.5 rounded-md text-sm transition">
                                Aksi ▾
                            </button>
                            
                            <div id="dropdown-{{ $customer['id'] }}" class="dropdown-menu hidden absolute right-6 top-12 mt-1 w-40 bg-white border border-gray-100 rounded-lg shadow-lg z-10 py-1 text-left">
                                <button onclick="openEditModal({{ json_encode($customer) }})" class="w-full text-left px-4 py-2 text-blue-600 hover:bg-gray-50 transition">
                                    Edit Data
                                </button>
                                
                                @if ($customer['status'])
                                    <form action="{{ route('customers.deactivate', $customer['id']) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="w-full text-left px-4 py-2 text-yellow-600 hover:bg-gray-50 transition">Deactivate</button>
                                    </form>
                                @else
                                    <form action="{{ route('customers.activate', $customer['id']) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="w-full text-left px-4 py-2 text-green-600 hover:bg-gray-50 transition">Activate</button>
                                    </form>
                                @endif

                                <div class="border-t border-gray-100 my-1"></div>
                                
                                <form action="{{ route('customers.destroy', $customer['id']) }}" method="POST" onsubmit="return confirm('Hapus customer ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-gray-50 transition">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-gray-500">Belum ada data customer.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="addDataModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true" onclick="closeModal('addDataModal')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-xl shadow-xl sm:my-8 sm:align-middle sm:max-w-lg w-full">
            <form action="{{ route('customers.store') }}" method="POST">
                @csrf
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900" id="modal-title">Tambah Customer</h3>
                    <button type="button" onclick="closeModal('addDataModal')" class="text-gray-400 hover:text-gray-500 text-2xl leading-none">&times;</button>
                </div>
                
                <div class="px-6 py-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Customer ID</label>
                        <input type="text" name="customer_id" value="{{ old('customer_id') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none">
                        {{-- @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror --}}
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                        <textarea name="address" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none">{{ old('address') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none">
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
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

<div id="editDataModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true" onclick="closeModal('editDataModal')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-xl shadow-xl sm:my-8 sm:align-middle sm:max-w-lg w-full">
            <form id="editForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">Edit Customer</h3>
                    <button type="button" onclick="closeModal('editDataModal')" class="text-gray-400 hover:text-gray-500 text-2xl leading-none">&times;</button>
                </div>
                
                <div class="px-6 py-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Customer ID</label>
                        <input type="text" name="customer_id" id="edit_customer_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input type="text" name="name" id="edit_name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" id="edit_email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <input type="text" name="phone" id="edit_phone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                        <textarea name="address" id="edit_address" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" id="edit_status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
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
    // --- Logika Modal ---
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }
    
    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }

    function openEditModal(customer) {
        document.getElementById('editForm').action = `/customers/${customer.id}`;
        
        document.getElementById('edit_customer_id').value = customer.customer_id;
        document.getElementById('edit_name').value = customer.name;
        document.getElementById('edit_email').value = customer.email || '';
        document.getElementById('edit_phone').value = customer.phone || '';
        document.getElementById('edit_address').value = customer.address || '';
        document.getElementById('edit_status').value = customer.status ? 'active' : 'inactive';
        
        openModal('editDataModal');
    }

    // Membuka kembali modal otomatis saat error validasi (422)
    @if(session('open_modal'))
        document.addEventListener("DOMContentLoaded", function() {
            openModal('{{ session('open_modal') }}');
        });
    @endif

    // --- Logika Dropdown ---
    function toggleDropdown(id, event) {
        event.stopPropagation(); // Mencegah window.click langsung menutup dropdown ini
        
        // Tutup semua dropdown lain terlebih dahulu
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            if(menu.id !== id) menu.classList.add('hidden');
        });
        
        // Toggle dropdown yang diklik
        document.getElementById(id).classList.toggle('hidden');
    }

    // Menutup dropdown jika user mengklik sembarang tempat di luar dropdown
    window.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown-btn')) {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.classList.add('hidden');
            });
        }
    });
</script>
@endpush