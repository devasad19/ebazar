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
            <h2 class="text-2xl font-bold text-green-700 mb-6">📦 সব অর্ডার (লাইভ)</h2>

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
        <!-- Recent Orders -->
            
            <div id="orderBoard" class="grid grid-cols-1 md:grid-cols-1 gap-4"></div>
           
        </section>

 

    </div>
</div>
@endsection

@section('scripts')
<script>
 


function loadOrders() {
    $.get("{{ route('admin.orders.live') }}", function(data){
        $('#orderBoard').empty(); // পুরনো কার্ড ক্লিয়ার

        data.orders.forEach(order => {
            $('#orderBoard').append(renderOrderCard(order));
        });
    });
}
 
setInterval(loadOrders, 5000);
loadOrders();
 


function renderOrderCard(order) {
    // ✅ মোট পরিমাণ হিসাব (আগের মতো)
let totals = {
    'কেজি': [],
    'পিস': [],
    'ডজন': [],
    'লিটার': [],
    'প্যাকেট': [],
    'টাকা': [],
};

// 🔹 Normal products
(order.items || []).forEach(i => {
    const unit = (i.product?.unit || '').trim();
    const qty = parseFloat(i.quantity) || 0;
    const price = parseFloat(i.price) || 0;
    const name = i.product?.name ?? 'অজানা পণ্য';

    if (!unit) return;

    if (unit === 'টাকা') {
        totals[unit].push(`${name} (${price}) ${unit}`);
    } else if (totals.hasOwnProperty(unit)) {
        totals[unit].push(`${name} (${qty}) ${unit}`);
    } else {
        totals[unit] = [`${name} (${qty}) ${unit}`];
    }
});

// 🔹 Custom products
(order.custom_products || []).forEach(i => {
    const unit = (i.unit || '').trim();
    const qty = parseFloat(i.quantity) || 0;
    const price = parseFloat(i.price) || 0;
    const name = i.name ?? 'আরো';

    if (!unit) return;

    if (unit === 'টাকা') {
        totals[unit].push(`${name} (${price}) ${unit}`);
    } else if (totals.hasOwnProperty(unit)) {
        totals[unit].push(`${name} (${qty}) ${unit}`);
    } else {
        totals[unit] = [`${name} (${qty}) ${unit}`];
    }
});

// 🔹 Join সবগুলো সুন্দরভাবে
let totalTextParts = [];

['কেজি', 'পিস', 'ডজন', 'লিটার', 'প্যাকেট', 'টাকা'].forEach(unit => {
    if (totals[unit] && totals[unit].length > 0) {
        totalTextParts.push(totals[unit].join(', '));
    }
});

let totalText = totalTextParts.join(' + ') || '-';


    // ✅ Status অনুযায়ী বাটন
    let buttonHTML = '';
    if (order.status === 'delivered') {
        buttonHTML = `
            <button class="bg-gray-600 text-white font-semibold px-6 py-2 rounded-lg w-full md:w-auto" disabled>
                ✅ ডেলিভারি সম্পন্ন হয়েছে
            </button>
        `;
    } else if (order.status === 'accepted') { 
        buttonHTML = `
            <button class="deliverBtn bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-2 rounded-lg transition w-full md:w-auto" data-id="${order.id}">
                🚚 অর্ডার গৃহীত হয়েছে
            </button>
        `;
    } else {
        buttonHTML = `
            <button class="deliverBtn bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded-lg transition w-full md:w-auto" data-id="${order.id}">
                🚚 অর্ডার পেন্ডিং
            </button>
        `;
    }
 
    
    // ✅ Delivery Info
    let deliveryInfo = '';
    if (order.status === 'delivered' || order.status === 'accepted') {
        const riderName = order.rider?.name ?? '';

        let statusText = '';
        if(order.delivered_status === 'on_time'){
            statusText = `<span class='text-green-600 font-semibold'>সময়ে ডেলিভারি</span>`;
        }else if(order.delivered_status === 'late'){
             statusText = `<span class='text-red-600 font-semibold'>বিলম্বে ডেলিভারি</span>`;
        }

        deliveryInfo = `
            <div class="mt-3 text-sm bg-indigo-100 text-gray-600 border-t p-2 rounded-md flex items-center justify-between">
                <p>🚴 <strong>রাইডারঃ</strong> ${riderName}</p>
                <p>🕓 <strong>ডেলিভারি সময়ঃ</strong> ${order.delivered_at?new Date(order.delivered_at).toLocaleString('bn-BD'): ''}</p>
                <p>${statusText}</p>
            </div>
        `;
    }

    return `
        <div class="relative bg-white p-5 mt-3 rounded-2xl shadow-md hover:shadow-lg border order-item w-full mb-1" data-id="${order.id}">
            {{-- 🔹 Order ID badge (top-left) --}}
    <span class="absolute -top-3 left-5 bg-indigo-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow">
        অর্ডার আইডি: # ${order.id }
    </span>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <h4 class="text-lg font-semibold text-green-700">${order.user?.name ?? 'অজানা ক্রেতা'}</h4>
                                    <p class="text-sm text-gray-600">পিতার নামঃ ${order.user?.father_name ?? '-'}</p>
                    <p>📞 ${order.user?.phone ?? '-'}</p>
                </div>
                <div>
                    <p><strong>পণ্যঃ</strong> ${order.items?.length ?? 0} টি</p>
                    <p><strong>মোটঃ</strong> ৳${order.total_amount}</p>
                                    <p><strong>ঠিকানাঃ</strong> ${order.delivery_address ?? '-'}</p>
                </div>
                <div class="text-right">
                    <p><strong>অর্ডার সময়ঃ</strong> ${new Date(order.created_at).toLocaleString('bn-BD')}</p>
                    ${buttonHTML}
                </div>
            </div>
            <p class="text-sm text-red-500 my-3"><strong>মোট পরিমাণঃ</strong> ${totalText}</p>
            ${deliveryInfo}
        </div>
    `;
}




 
</script>
@endsection