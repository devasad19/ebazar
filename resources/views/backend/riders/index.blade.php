@extends('apps.dashboard_master')
@section('content')
<div class="flex min-h-screen bg-gray-50">
    <!-- 🟢 Sidebar -->
    @include('backend.patrials.rider_aside')
  
    <!-- 🟡 Main Content Area -->
    <div class="flex-1 flex flex-col p-4">
        <!-- Top Bar -->
        <!-- Top Bar -->
        <header class="bg-white shadow-md py-4 px-6 flex justify-between items-center">
            <h1 class="text-xl font-bold text-gray-700">ড্যাশবোর্ড</h1>
            <div class="flex items-center gap-4">
                <span class="text-gray-600 text-sm hidden sm:block">রাইডার</span>
                <img src="{{ $rider->user->photo ? url('uploads/riders/'.$rider->user->photo??'') : 'https://cdn-icons-png.flaticon.com/512/3135/3135715.png' }}" alt="User" class="w-10 h-10 rounded-full object-cover">
            </div>
        </header>

        
        <!-- 🎁 Offer Notice Card (Dismissible) -->
<div class="bg-gray-50 min-h-screen py-10">
    <div class="max-w-7xl mx-auto px-6">

        <!-- Rider Welcome Header -->
        <div class="bg-white shadow rounded-2xl p-6 flex flex-col md:flex-row justify-between items-center mb-8">
            <div>
                <h2 class="text-2xl font-bold text-green-700">স্বাগতম, {{ $rider->name ?? 'রাইডার' }} 👋</h2>
                <p class="text-gray-600 mt-1">আজকের জন্য আপনার কার্যক্রম নিচে দেখুন</p>
            </div>

            <div class="flex items-center gap-4 mt-4 md:mt-0">
                <img src="{{ $rider->user->photo ? asset('uploads/riders/'.$rider->user->photo) : 'https://cdn-icons-png.flaticon.com/512/3135/3135715.png' }}" 
                     class="w-16 h-16 rounded-full border-2 border-green-500 object-cover" 
                     alt="Rider Photo">

                <div>
                    <p class="text-sm text-gray-700"><strong>ফোন:</strong> {{ $rider->user->phone }}</p>
                    <p class="text-sm text-gray-700"><strong>যানবাহন:</strong> {{ $rider->vehicle_type ?? 'নির্দিষ্ট নয়' }}</p>
                    
                </div>
            </div>
        </div>

        <!-- Rider Stats Section -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-10">
            <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
                <h3 class="text-gray-500 text-sm mb-2">✅ সম্পন্ন ডেলিভারি</h3>
                <p class="text-3xl font-bold text-green-600">{{ $rider->total_delivered ?? 0 }}</p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
                <h3 class="text-gray-500 text-sm mb-2">⏱️ সময়মতো ডেলিভারি</h3>
                <p class="text-3xl font-bold text-blue-600">{{ $rider->on_time_delivery ?? 0 }}</p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
                <h3 class="text-gray-500 text-sm mb-2">📦 চলমান অর্ডার</h3>
                <p class="text-3xl font-bold text-yellow-600">{{ $rider->pending_orders ?? 0 }}</p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
                <h3 class="text-gray-500 text-sm mb-2">❌ বাতিল ডেলিভারি</h3>
                <p class="text-3xl font-bold text-red-600">{{ $rider->cancel_delivery ?? 0 }}</p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white p-6 rounded-2xl shadow mb-10">
            <h3 class="text-lg font-semibold text-green-700 mb-4">🚀 দ্রুত কার্যক্রম</h3>
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('rider.orders') }}" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">📦 আমার অর্ডারসমূহ</a>
                <a href="{{ route('rider.products') }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">🛒 আমার পণ্য তালিকা</a>
                <a href="{{ route('rider.earnings') }}" class="px-6 py-3 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">💰 আয় দেখুন</a>
                <a href="{{ route('rider.support') }}" class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">📞 সাপোর্টে যোগাযোগ</a>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="bg-white p-6 rounded-2xl shadow">
            <h3 class="text-lg font-semibold text-green-700 mb-4">📋 সাম্প্রতিক অর্ডার</h3>

            @if($recentOrders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-green-100 text-left">
                                <th class="py-3 px-4 font-semibold text-gray-700">#</th>
                                <th class="py-3 px-4 font-semibold text-gray-700">পণ্য</th>
                                <th class="py-3 px-4 font-semibold text-gray-700">মূল্য</th>
                                <th class="py-3 px-4 font-semibold text-gray-700">স্ট্যাটাস</th>
                                <th class="py-3 px-4 font-semibold text-gray-700">তারিখ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                            <tr class="border-t hover:bg-gray-50">
                                <td class="py-3 px-4">{{ $loop->iteration }}</td>
                                <td class="py-3 px-4">{{ $order->product->name ?? '-' }}</td>
                                <td class="py-3 px-4 text-green-600 font-semibold">৳{{ $order->price }}</td>
                                <td class="py-3 px-4">
                                    <span class="px-3 py-1 rounded-full text-xs 
                                        {{ $order->status === 'delivered' ? 'bg-green-100 text-green-700' : ($order->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-600">{{ $order->created_at->format('d M, Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-600">📭 কোনো অর্ডার পাওয়া যায়নি।</p>
            @endif
        </div>
    </div>
</div>
    </div>
</div>

@endsection
@section('scripts')
 
@endsection
