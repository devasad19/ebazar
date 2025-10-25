@extends('apps.dashboard_master')

@section('content')
<div class="flex min-h-screen bg-gray-50">

    <!-- 🟢 Sidebar -->
    @include('backend.patrials.admin_aside')

    <!-- 🟡 Main Content Area -->
    <div class="flex-1 flex flex-col">

        @include('backend.patrials.top_bar')

        <!-- Content Body -->
        <section class="bg-white p-6 rounded-2xl shadow m-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-green-700">🛍️ পণ্য ব্যবস্থাপনা</h2>
                <a href="{{ route('admin.product.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition"
                        data-modal-target="addProductModal">
                    ➕ নতুন পণ্য যোগ করুন
            </a>
            </div>

            <!-- ✅ Product List Table -->
            <div class="overflow-x-auto bg-gray-50 rounded-lg shadow-inner">
                <table class="min-w-full border border-gray-200">
                    <thead class="bg-green-600 text-white">
                        <tr>
                            <th class="py-3 px-4 text-left">#</th>
                            <th class="py-3 px-4 text-left">পণ্যের নাম</th>
                            <th class="py-3 px-4 text-left">বিভাগ</th>
                            <th class="py-3 px-4 text-left">মূল্য</th>
                            <th class="py-3 px-4 text-left">স্ট্যাটাস</th>
                            <th class="py-3 px-4 text-center">অপশন</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($products as $key => $product)
                        <tr class="hover:bg-gray-100 transition">
                            <td class="py-3 px-4">{{ $key + 1 }}</td>
                            <td class="py-3 px-4 font-medium text-gray-700">{{ $product->name }}</td>
                            <td class="py-3 px-4">{{ $product->category->name ?? 'N/A' }}</td>
                            <td class="py-3 px-4">৳{{ number_format($product->price, 2) }}</td>
                            <td class="py-3 px-4">
                                @if($product->status == 'active')
                                    <span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">সক্রিয়</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full">নিষ্ক্রিয়</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center space-x-2">
                                <button class="text-blue-600 hover:text-blue-800" title="View Details" onclick="viewProduct({{ $product->id }})">
                                    👁️
                                </button>
                                <button class="text-yellow-600 hover:text-yellow-800" title="Edit" onclick="openEditModal({{ $product->id }})">
                                    ✏️
                                </button>
                                <button class="text-red-600 hover:text-red-800" title="Delete" onclick="confirmDelete({{ $product->id }})">
                                    🗑️
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- No Product Message -->
                 
                <!-- <div class="text-center py-8 text-gray-500">
                    🚫 কোনো পণ্য পাওয়া যায়নি।
                </div> -->
          
            </div>
        </section>

        <!-- 🔘 Add/Edit Modal Placeholder -->
<!-- 🧩 Add/Edit Product Modal -->
<div id="addProductModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white w-full max-w-lg rounded-2xl shadow-lg p-6 relative">
      <h2 class="text-xl font-bold text-green-700 mb-4">🛒 নতুন পণ্য যোগ করুন</h2>

      <form action="" method="POST">
          @csrf
          <div class="grid grid-cols-1 gap-4">
              <div>
                  <label class="text-gray-600 font-semibold">পণ্যের নাম</label>
                  <input type="text" name="name" class="w-full border px-3 py-2 rounded-lg focus:ring-2 focus:ring-green-400">
              </div>

              <div>
                  <label class="text-gray-600 font-semibold">মূল্য</label>
                  <input type="number" name="price" class="w-full border px-3 py-2 rounded-lg focus:ring-2 focus:ring-green-400">
              </div>

              <div>
                  <label class="text-gray-600 font-semibold">স্ট্যাটাস</label>
                  <select name="status" class="w-full border px-3 py-2 rounded-lg focus:ring-2 focus:ring-green-400">
                      <option value="active">সক্রিয়</option>
                      <option value="inactive">নিষ্ক্রিয়</option>
                  </select>
              </div>
          </div>

          <div class="flex justify-end mt-6 space-x-3">
              <button type="button" onclick="document.getElementById('addProductModal').classList.add('hidden')"
                      class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition">বাতিল</button>
              <button type="submit"
                      class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">সংরক্ষণ</button>
          </div>
      </form>
  </div>
</div>


    </div>
</div>
@endsection

@section('scripts')
<script>
    function openEditModal(id) {
        document.getElementById('addProductModal').classList.remove('hidden');
        // এখানে আপনি AJAX দিয়ে প্রোডাক্ট ডাটা লোড করতে পারেন
    }

    function confirmDelete(id) {
        if (confirm('আপনি কি নিশ্চিতভাবে এই পণ্যটি মুছে ফেলতে চান?')) {
            // এখানে delete request পাঠান
        }
    }

    function viewProduct(id) {
        // প্রোডাক্ট বিস্তারিত দেখানোর জন্য modal খুলবেন
    }
</script>
@endsection
