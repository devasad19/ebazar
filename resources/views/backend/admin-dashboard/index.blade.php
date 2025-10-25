@extends('apps.dashboard_master')

@section('content')

<div class="flex min-h-screen bg-gray-50">

    <!-- 🟢 Sidebar -->
    @include('backend.patrials.admin_aside')

    <!-- 🟡 Main Content Area -->
    <div class="flex-1 flex flex-col">

        @include('backend.patrials.top_bar')

        <!-- 🧩 Dashboard Body -->
        <section class="bg-gray-50 p-6 rounded-2xl shadow">

            <h2 class="text-2xl font-bold text-green-700 mb-6">📊 অ্যাডমিন ড্যাশবোর্ড</h2>

            <!-- 🔹 Stats Overview -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white shadow rounded-2xl p-5 text-center border-t-4 border-green-500">
                    <h3 class="text-gray-600 font-semibold">মোট ইউজার</h3>
                    <p class="text-3xl font-bold text-green-700 mt-2">1,245</p>
                </div>

                <div class="bg-white shadow rounded-2xl p-5 text-center border-t-4 border-blue-500">
                    <h3 class="text-gray-600 font-semibold">মোট অর্ডার</h3>
                    <p class="text-3xl font-bold text-blue-700 mt-2">856</p>
                </div>

                <div class="bg-white shadow rounded-2xl p-5 text-center border-t-4 border-yellow-500">
                    <h3 class="text-gray-600 font-semibold">মোট রেভিনিউ</h3>
                    <p class="text-3xl font-bold text-yellow-600 mt-2">৳ 4,25,000</p>
                </div>

                <div class="bg-white shadow rounded-2xl p-5 text-center border-t-4 border-pink-500">
                    <h3 class="text-gray-600 font-semibold">পেন্ডিং অর্ডার</h3>
                    <p class="text-3xl font-bold text-pink-600 mt-2">42</p>
                </div>
            </div>

            <!-- 🔸 Recent Orders -->
            <div class="bg-white shadow rounded-2xl p-5 mb-8">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold text-gray-800">🛒 সাম্প্রতিক অর্ডার</h3>
                    <a href="{{ route('admin.all_orders') }}" class="text-green-600 hover:text-green-800 text-sm font-medium">সব অর্ডার দেখুন →</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="text-left py-3 px-4 text-gray-600 font-semibold">#</th>
                                <th class="text-left py-3 px-4 text-gray-600 font-semibold">কাস্টমার</th>
                                <th class="text-left py-3 px-4 text-gray-600 font-semibold">তারিখ</th>
                                <th class="text-left py-3 px-4 text-gray-600 font-semibold">স্ট্যাটাস</th>
                                <th class="text-left py-3 px-4 text-gray-600 font-semibold">পরিমাণ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-t">
                                <td class="py-3 px-4">#1001</td>
                                <td class="py-3 px-4">আল-আমিন</td>
                                <td class="py-3 px-4">২২ অক্টোবর, ২০২৫</td>
                                <td class="py-3 px-4">
                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">সম্পন্ন</span>
                                </td>
                                <td class="py-3 px-4 font-semibold">৳ 2,500</td>
                            </tr>
                            <tr class="border-t">
                                <td class="py-3 px-4">#1002</td>
                                <td class="py-3 px-4">মেহেদী হাসান</td>
                                <td class="py-3 px-4">২১ অক্টোবর, ২০২৫</td>
                                <td class="py-3 px-4">
                                    <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm">পেন্ডিং</span>
                                </td>
                                <td class="py-3 px-4 font-semibold">৳ 1,200</td>
                            </tr>
                            <tr class="border-t">
                                <td class="py-3 px-4">#1003</td>
                                <td class="py-3 px-4">রিয়াজ উদ্দিন</td>
                                <td class="py-3 px-4">২০ অক্টোবর, ২০২৫</td>
                                <td class="py-3 px-4">
                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm">বাতিল</span>
                                </td>
                                <td class="py-3 px-4 font-semibold">৳ 900</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 🔹 Activity Summary -->
            <div class="bg-white shadow rounded-2xl p-5">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">📈 সাইট সারাংশ</h3>
                <ul class="space-y-2 text-gray-700">
                    <li>✅ আজ নতুন ইউজার: <strong>32</strong></li>
                    <li>🛍️ আজ নতুন অর্ডার: <strong>15</strong></li>
                    <li>💰 আজকের আয়: <strong>৳ 12,500</strong></li>
                    <li>📦 মোট প্রোডাক্ট: <strong>285</strong></li>
                </ul>
            </div>

        </section>

    </div>
</div>

@endsection

@section('scripts')
<script>
    console.log("📊 Admin Dashboard Loaded Successfully");
</script>
@endsection
