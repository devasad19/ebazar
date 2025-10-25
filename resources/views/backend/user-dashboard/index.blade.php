@extends('apps.dashboard_master')
@section('content')
<div class="flex min-h-screen bg-gray-50">
    <!-- 🟢 Sidebar -->
    @include('backend.patrials.aside')
 
    <!-- 🟡 Main Content Area -->
    <div class="flex-1 flex flex-col p-4">
        <!-- Top Bar -->
    @include('backend.patrials.top_bar')
        <!-- 🎁 Offer Notice Card (Dismissible) -->
        <div id="offerNotice"
            class="bg-gradient-to-r from-green-400 to-green-600 text-white p-5 rounded-2xl shadow-md mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-3 relative transition-all duration-300">

            <div class="flex items-center gap-3">
                
                <p class="font-semibold text-lg leading-snug">
                    🎉 <span class="font-bold">স্পেশাল অফার!</span> 
                    এই সপ্তাহে সব তাজা পণ্যে <span class="underline decoration-white font-bold">১০% ছাড়</span>। 
                    <span class="font-normal block md:inline">অর্ডার করতে এখনই ক্লিক করুন!</span>
                </p>
            </div>

            <div class="flex items-center gap-3">
        <a href="{{ route('user.my_cart') }}" 
        class="bg-white text-green-700 font-semibold px-2 py-2 rounded-lg hover:bg-green-100 transition min-w-[150px] text-center">
            🛒 অর্ডার করুন
        </a>

                <button onclick="closeOfferNotice()" class="text-white hover:text-gray-200 text-2xl font-bold leading-none">&times;</button>
            </div>
        </div>

    <!-- Content Body -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <!-- Example Card -->
            <div class="bg-white rounded-2xl shadow p-5 flex flex-col justify-between">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Orders</h3>
                <p class="text-2xl font-bold text-green-600">12</p>
            </div>

            <div class="bg-white rounded-2xl shadow p-5 flex flex-col justify-between">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Pending Orders</h3>
                <p class="text-2xl font-bold text-yellow-500">3</p>
            </div>

            <div class="bg-white rounded-2xl shadow p-5 flex flex-col justify-between">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Completed Orders</h3>
                <p class="text-2xl font-bold text-green-600">9</p>
            </div>

        </div>

        <!-- Recent Orders Table -->
        <div class="mt-8 bg-white rounded-2xl shadow p-5">
            <h3 class="text-xl font-semibold text-gray-700 mb-4">Recent Orders</h3>
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-green-50 text-gray-700">
                        <th class="py-2 px-4">Order ID</th>
                        <th class="py-2 px-4">Product</th>
                        <th class="py-2 px-4">Quantity</th>
                        <th class="py-2 px-4">Price</th>
                        <th class="py-2 px-4">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b">
                        <td class="py-2 px-4">#123456</td>
                        <td class="py-2 px-4">তাজা বেগুন</td>
                        <td class="py-2 px-4">2</td>
                        <td class="py-2 px-4">৳120</td>
                        <td class="py-2 px-4 text-green-600 font-semibold">Delivered</td>
                    </tr>
                    <tr class="border-b">
                        <td class="py-2 px-4">#123457</td>
                        <td class="py-2 px-4">টমেটো</td>
                        <td class="py-2 px-4">1</td>
                        <td class="py-2 px-4">৳80</td>
                        <td class="py-2 px-4 text-yellow-500 font-semibold">Pending</td>
                    </tr>
                    <tr class="border-b">
                        <td class="py-2 px-4">#123458</td>
                        <td class="py-2 px-4">পটল</td>
                        <td class="py-2 px-4">3</td>
                        <td class="py-2 px-4">৳90</td>
                        <td class="py-2 px-4 text-green-600 font-semibold">Delivered</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script>
function closeOfferNotice() {
    const notice = document.getElementById('offerNotice');
    notice.classList.add('opacity-0', 'translate-y-3');
    setTimeout(() => notice.remove(), 300); // smooth remove
}
</script>
@endsection
