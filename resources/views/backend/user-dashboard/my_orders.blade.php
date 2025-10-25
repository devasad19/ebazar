@extends('apps.dashboard_master')

@section('content')

<div class="flex min-h-screen bg-gray-50">

    <!-- 🟢 Sidebar -->
    @include('backend.patrials.aside')
    <!-- 🟡 Main Content Area -->
    <div class="flex-1 flex flex-col">

    @include('backend.patrials.top_bar')

        <!-- Content Body --> 
  <section class="bg-gray-50 p-6 rounded-2xl shadow">
    <h2 class="text-3xl font-bold text-green-700 mb-6 text-center">🛒 My Orders</h2>

    @php
        // Sample orders data
        $orders = [
            [
                'id' => '123456',
                'date' => '2025-10-22',
                'status' => 'Delivered',
                'items' => [
                    ['name' => 'বেগুন', 'quantity' => 2, 'price' => 60, 'image' => 'https://via.placeholder.com/50'],
                    ['name' => 'টমেটো', 'quantity' => 1, 'price' => 80, 'image' => 'https://via.placeholder.com/50'],
                ],
                'total' => 200
            ],
            [
                'id' => '123457',
                'date' => '2025-10-21',
                'status' => 'Pending',
                'items' => [
                    ['name' => 'পটল', 'quantity' => 3, 'price' => 30, 'image' => 'https://via.placeholder.com/50'],
                ],
                'total' => 90
            ],
        ];
    @endphp

    <div class="bg-white rounded-2xl shadow p-6">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-green-50 text-gray-700">
                    <th class="py-2 px-4">Order ID</th>
                    <th class="py-2 px-4">Date</th>
                    <th class="py-2 px-4">Status</th>
                    <th class="py-2 px-4">Total</th>
                    <th class="py-2 px-4">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr class="border-b">
                    <td class="py-2 px-4 font-semibold">{{ $order['id'] }}</td>
                    <td class="py-2 px-4">{{ $order['date'] }}</td>
                    <td class="py-2 px-4">
                        @if($order['status'] == 'Delivered')
                            <span class="text-green-600 font-semibold">{{ $order['status'] }}</span>
                        @else
                            <span class="text-yellow-500 font-semibold">{{ $order['status'] }}</span>
                        @endif
                    </td>
                    <td class="py-2 px-4">৳{{ $order['total'] }}</td>
                    <td class="py-2 px-4">
                        <button 
                            class="bg-green-600 text-white px-3 py-1 rounded-lg hover:bg-green-700 transition text-sm"
                            onclick="openOrderModal({{ json_encode($order) }})">
                            View Details
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>

<!-- 🟢 Order Details Modal -->
<div id="orderModal" class="fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center opacity-0 pointer-events-none transition-opacity duration-300 z-50">
    <div class="bg-white w-96 md:w-2/3 rounded-2xl shadow-lg p-5 relative">
        <h3 class="text-xl font-bold text-green-700 mb-4">Order Details</h3>

        <div id="modalContent" class="space-y-3">
            <!-- JS will populate order items here -->
        </div>

        <div class="border-t pt-3 mt-4 flex justify-between items-center">
            <p class="font-bold text-green-700">মোট: <span id="modalTotal">৳0</span></p>
            <button onclick="closeOrderModal()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                Close
            </button>
        </div>
    </div>
</div> 
    </div>
</div>

@endsection

@section('scripts')
<script>
const modal = document.getElementById('orderModal');
const modalContent = document.getElementById('modalContent');
const modalTotal = document.getElementById('modalTotal');

function openOrderModal(order) {
    modalContent.innerHTML = ''; // Clear previous content
    order.items.forEach(item => {
        const div = document.createElement('div');
        div.className = 'flex justify-between items-center border-b pb-2';
        div.innerHTML = `
            <div class="flex items-center gap-2">
                <img src="${item.image}" alt="${item.name}" class="w-12 h-12 object-cover rounded-lg">
                <div>
                    <h4 class="font-semibold text-gray-800">${item.name}</h4>
                    <p class="text-sm text-gray-500">পরিমাণ: ${item.quantity}</p>
                </div>
            </div>
            <p class="text-green-600 font-semibold">৳${item.price * item.quantity}</p>
        `;
        modalContent.appendChild(div);
    });
    modalTotal.innerText = `৳${order.total}`;
    
    modal.classList.remove('opacity-0', 'pointer-events-none');
    modal.classList.add('opacity-100');
}

function closeOrderModal() {
    modal.classList.remove('opacity-100');
    modal.classList.add('opacity-0', 'pointer-events-none');
}
</script>
@endsection
