@extends('apps.dashboard_master')

@section('content')
<div class="flex min-h-screen bg-gray-50">

    <!-- Sidebar -->
    @include('backend.patrials.admin_aside')

    <!-- Main Area -->
    <div class="flex-1 flex flex-col">

        @include('backend.patrials.top_bar')

        <!-- Content -->
        <section class="bg-white p-6 rounded-2xl shadow mx-6 my-6">
            <h2 class="text-2xl font-bold text-green-700 mb-6">📦 সব অর্ডার</h2>

            <!-- Filter -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 gap-4">
                <div>
                    <label for="dateFilter" class="text-gray-600 font-semibold mr-2">Filter by:</label>
                    <select id="dateFilter" class="border px-3 py-2 rounded-lg focus:ring-2 focus:ring-green-400 outline-none">
                        <option value="today">Today</option>
                        <option value="yesterday">Yesterday</option>
                        <option value="last7">Last 7 Days</option>
                        <option value="last15">Last 15 Days</option>
                        <option value="1month">1 Month</option>
                    </select>
                </div>

                <div>
                    <label for="sortBy" class="text-gray-600 font-semibold mr-2">Sort by:</label>
                    <select id="sortBy" class="border px-3 py-2 rounded-lg focus:ring-2 focus:ring-green-400 outline-none">
                        <option value="total_delivered_desc">Total Delivered (High → Low)</option>
                        <option value="total_delivered_asc">Total Delivered (Low → High)</option>
                        <option value="pending_orders_desc">Pending Orders (High → Low)</option>
                        <option value="pending_orders_asc">Pending Orders (Low → High)</option>
                    </select>
                </div>
            </div>
 
            <!-- Orders Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-gray-700 border border-gray-200">
                    <thead class="bg-green-600 text-white">
                        <tr>
                            <th class="px-4 py-3 border">#</th>
                            <th class="px-4 py-3 border">Order ID</th>
                            <th class="px-4 py-3 border">Customer</th>
                            <th class="px-4 py-3 border">Total</th>
                            <th class="px-4 py-3 border">Status</th>
                            <th class="px-4 py-3 border">Date</th>
                            <th class="px-4 py-3 border text-center">Options</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $key => $order)
                        <tr class="hover:bg-gray-50 border-b">
                            <td class="px-4 py-3 border">{{ $key+1 }}</td>
                            <td class="px-4 py-3 border font-semibold text-gray-800">#{{ $order->id }}</td>
                            <td class="px-4 py-3 border">{{ $order->customer_name }}</td>
                            <td class="px-4 py-3 border">৳{{ number_format($order->total, 2) }}</td>
                            <td class="px-4 py-3 border">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold 
                                    {{ $order->status == 'Completed' ? 'bg-green-100 text-green-700' : ($order->status == 'Pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 border">{{ $order->created_at->format('d M, Y') }}</td>
                            <td class="px-4 py-3 border text-center">
                                <button 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-md text-xs"
                                    onclick="showOrderDetails({{ $order->id }})">
                                    Details
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Order Details Modal -->
        <div id="orderModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <div class="bg-white w-full max-w-2xl p-6 rounded-xl shadow-lg relative">
                <h3 class="text-xl font-bold text-green-700 mb-4">🧾 অর্ডার ডিটেইলস</h3>
                <div id="orderDetailsContent">
                    <!-- AJAX data load here -->
                    <p class="text-gray-600">Loading...</p>
                </div>

                <button onclick="closeOrderModal()" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
                    ✖
                </button>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
function showOrderDetails(orderId) {
    const modal = document.getElementById('orderModal');
    const content = document.getElementById('orderDetailsContent');
    modal.classList.remove('hidden');
    content.innerHTML = `<p class="text-gray-600">Loading details...</p>`;

    fetch(`/admin/orders/${orderId}`)
        .then(res => res.json())
        .then(data => {
            content.innerHTML = `
                <div class="space-y-2">
                    <p><strong>Customer:</strong> ${data.customer_name}</p>
                    <p><strong>Phone:</strong> ${data.phone}</p>
                    <p><strong>Address:</strong> ${data.address}</p>
                    <p><strong>Total:</strong> ৳${data.total}</p>
                    <p><strong>Status:</strong> ${data.status}</p>
                </div>
                <h4 class="mt-4 font-semibold text-gray-700">🛒 Items:</h4>
                <ul class="list-disc ml-6">
                    ${data.items.map(item => `<li>${item.name} - ${item.quantity} × ৳${item.price}</li>`).join('')}
                </ul>
            `;
        });
}

function closeOrderModal() {
    document.getElementById('orderModal').classList.add('hidden');
}
</script>
@endsection
