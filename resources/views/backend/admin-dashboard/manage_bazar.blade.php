@extends('apps.dashboard_master')

@section('content')
<div class="flex min-h-screen bg-gray-50">
    <!-- 🟢 Sidebar -->
    @include('backend.patrials.admin_aside')

    <!-- 🟡 Main Content Area -->
    <div class="flex-1 flex flex-col">
        @include('backend.patrials.top_bar')

        <!-- Content Body -->
        <section class="p-6 bg-gray-50 rounded-2xl shadow">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-green-700">🛒 বাজার তালিকা</h2>
                <button onclick="openAddModal()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-semibold shadow">
                    ➕ নতুন বাজার যোগ করুন
                </button>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto bg-white rounded-2xl shadow">
                <table class="w-full border-collapse text-left">
                    <thead class="bg-green-50 border-b border-gray-200">
                        <tr>
                            <th class="py-3 px-4 text-gray-700 font-semibold">#</th>
                            <th class="py-3 px-4 text-gray-700 font-semibold">বাজারের নাম</th>
                            <th class="py-3 px-4 text-gray-700 font-semibold">স্ট্যাটাস</th>
                            <th class="py-3 px-4 text-gray-700 font-semibold text-right">অপশন</th>
                        </tr>
                    </thead>
                    <tbody id="bazarTable">
                        @foreach($bazars as $index => $bazar)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="py-3 px-4">{{ $index + 1 }}</td>
                            <td class="py-3 px-4">{{ $bazar['name'] }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-1 rounded text-xs font-semibold 
                                    {{ $bazar['status'] == 'Active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                                    {{ $bazar['status'] }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <button onclick="openEditModal('{{ $bazar['id'] }}', '{{ $bazar['name'] }}', '{{ $bazar['status'] }}')" 
                                    class="text-blue-600 hover:text-blue-800 font-semibold text-sm">✏️ Edit</button>
                                    <button onclick="deleteBazar({{ $bazar->id }})" class=" text-red-600 hover:text-red-800 font-semibold text-sm ml-3 px-3 py-1 rounded">🗑️ Delete</button>
                                     
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>

<!-- 🟢 Edit / Add Modal -->
<div id="bazarModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-lg p-6 relative">
        <h3 id="modalTitle" class="text-xl font-bold text-green-700 mb-4">বাজার সম্পাদনা করুন</h3>

        <form id="bazarForm">
            <input type="hidden" id="bazarId" name="id">
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1">বাজারের নাম</label>
                <input type="text" id="bazarName" class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-green-200" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1">স্ট্যাটাস</label>
                <select id="bazarStatus" class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-green-200">
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeModal()" class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300">বাতিল</button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold">সেভ করুন</button>
            </div>
        </form>

        <button onclick="closeModal()" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600">✖</button>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const modal = document.getElementById('bazarModal');
    const title = document.getElementById('modalTitle');
    const bazarForm = document.getElementById('bazarForm');

    function openAddModal() {
        title.innerText = 'নতুন বাজার যোগ করুন';
        document.getElementById('bazarId').value = '';
        document.getElementById('bazarName').value = '';
        document.getElementById('bazarStatus').value = 'Active';
        modal.classList.remove('hidden');
    }

    function openEditModal(id, name, status) {
        title.innerText = 'বাজার সম্পাদনা করুন';
        document.getElementById('bazarId').value = id;
        document.getElementById('bazarName').value = name;
        document.getElementById('bazarStatus').value = status;
        modal.classList.remove('hidden');
    }

    function closeModal() {
        modal.classList.add('hidden');
    }

 
function openModal() {
    $('#bazarModal').removeClass('hidden');
}
function closeModal() {
    $('#bazarModal').addClass('hidden');
}



$('#bazarForm').on('submit', function(e) {
    e.preventDefault();

    const data = {
        id: $('#bazarId').val(),
        name: $('#bazarName').val(),
        status: $('#bazarStatus').val(),
        _token: '{{ csrf_token() }}'
    };

    $.ajax({
        url: '{{ route('admin.bazars.store') }}',
        method: 'POST',
        data: data,
        success: function(response) {
         
            
        showToast('success', 'Success', response.message || '✅ বাজার তথ্য সফলভাবে সংরক্ষণ করা হয়েছে!');
            closeModal();
            setTimeout(() => {
                location.reload();
            }, 2000);
        },
        error: function(xhr) {
            console.error(xhr.responseJSON);
            
        }
    });
});
 

</script>

<script>
function deleteBazar(id) {
    if (!confirm('আপনি কি নিশ্চিত এই বাজারটি মুছে ফেলতে চান?')) return;

    $.ajax({
        url: "{{ route('bazar.delete', '') }}/" + id,
        type: 'GET',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            
            showToast('success', 'Success', response.message || '✅ বাজারটি মুছে ফেলা হয়েছে!');
            setTimeout(() => {
                location.reload();
            }, 2000);
        },
        error: function(xhr) {
            alert('❌ মুছে ফেলা ব্যর্থ হয়েছে!');
        }
    });
}
</script>




@endsection
