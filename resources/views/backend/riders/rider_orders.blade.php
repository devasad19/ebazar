@extends('apps.dashboard_master')

@section('content')
<div class="flex min-h-screen bg-gray-50">

    <!-- Sidebar -->
    @include('backend.patrials.rider_aside')

    <!-- Main Area -->
    <div class="flex-1 flex flex-col">

        @include('backend.patrials.top_bar')

        <!-- Content -->
        <section class="bg-white p-6 rounded-2xl shadow mx-6 my-6">
            <h2 class="text-2xl font-bold text-green-700 mb-6">📦 লাইভ অর্ডার বোর্ড</h2>

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
                

 
    <div id="orderBoard" class="grid grid-cols-1 md:grid-cols-1 gap-6"></div>
 
 

            </div>
        </section>

<!-- ✅ Accept Modal -->
<div id="acceptModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50">
    <div class="bg-white w-full max-w-2xl rounded-lg p-6 shadow-lg relative">
        <button onclick="closeModal()" class="absolute top-2 right-2 text-gray-600 text-xl">✖</button>
        <h3 class="text-xl font-bold mb-4">🧾 Accept Order</h3>

        <div id="modalItems" class="space-y-3"></div>

        <div class="flex justify-between mt-6 border-t pt-4">
            <p class="font-bold text-lg">Total:</p>
            <p id="modalTotal" class="font-bold text-lg text-green-600">৳0</p>
        </div>

        <button id="confirmAccept" class="bg-green-600 text-white px-4 py-2 rounded mt-4 w-full">✅ Confirm Order</button>
    </div>
</div>


    </div>
</div>
@endsection


@section('scripts')
<script>
function loadOrders() {
    $.get("{{ route('rider.orders.pending') }}", function(data){
        data.orders.forEach(order => {
            if (!$(`#orderBoard .order-item[data-id="${order.id}"]`).length) {

                // 🧮 Calculate total by unit
                    // 🧮 Calculate totals with product names (except কেজি)
                    let totals = {
                        'কেজি': 0,
                        'পিস': [],
                        'ডজন': [],
                        'লিটার': [],
                        'প্যাকেট': []
                    };

                    (order.items || []).forEach(i => {
                        const unit = i.product?.unit?.trim();
                        const qty = parseFloat(i.quantity) || 0;
                        const name = i.product?.name ?? 'অজানা পণ্য';

                        if (!unit) return;

                        if (unit === 'কেজি') {
                            totals['কেজি'] += qty; // keep kg total
                        } else if (totals.hasOwnProperty(unit)) {
                            totals[unit].push(`${name} (${qty})`); // save product name + qty
                        }
                    });

                    // Build display string
                    let totalTextParts = [];

                    // কেজি প্রথম
                    if (totals['কেজি'] > 0) {
                        totalTextParts.push(`${totals['কেজি'].toFixed(1)} কেজি`);
                    }

                    // অন্য units
                    ['পিস','ডজন','লিটার','প্যাকেট'].forEach(unit => {
                        if (totals[unit].length > 0) {
                            totalTextParts.push(totals[unit].join(', ') + ` ${unit}`);
                        }
                    });

                    let totalText = totalTextParts.join(' + ') || '-';

                $('#orderBoard').append(`
                    <div class="w-full">
                        <div class="bg-white p-5 rounded-2xl shadow-md hover:shadow-lg transition order-item border border-gray-100 w-full" data-id="${order.id}">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">

                                <!-- 🧍‍♂️ Customer Info -->
                                <div>
                                    <h4 class="text-lg font-semibold text-green-700 mb-1">
                                        ${order.user?.name ?? 'অজানা ক্রেতা'}
                                    </h4>
                                    <p class="text-sm text-gray-600">পিতার নামঃ ${order.user?.father_name ?? '-'}</p>
                                    <p class="text-sm text-gray-600">📞 ${order.user?.phone ?? '-'}</p>
                                </div>

                                <!-- 📦 Order Info -->
                                <div class="text-gray-700">
                                    <p><strong>পণ্যঃ</strong> ${order.items?.length ?? 0} টি</p>
                                    <p><strong>মোটঃ</strong> <span class="text-green-700 font-semibold">৳${order.total_amount}</span></p>
                                    <p><strong>ঠিকানাঃ</strong> ${order.delivery_address ?? '-'}</p>
                                </div>

                                <!-- ⏰ Time & Action -->
                                <div class="flex flex-col justify-between text-left md:text-right">
                                    <p class="text-sm text-gray-500 mb-3">
                                        <strong>অর্ডার সময়ঃ</strong> ${new Date(order.created_at).toLocaleString('bn-BD')}
                                    </p>
                                    <button class="acceptBtn bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded-lg transition w-full md:w-auto">
                                        ✅ গ্রহণ করুন
                                    </button>
                                </div>
                                </div>
                                <p class="text-sm text-red-500 mt-3"><strong>মোট পরিমাণঃ</strong> ${totalText}</p>
                        </div>
                    </div>
                `);
            }
        });
    });
}

setInterval(loadOrders, 5000);
loadOrders();

// 🔹 Open Modal
 // 🔹 Open Accept Modal
$(document).on('click', '.acceptBtn', function() {
    const orderId = $(this).closest('.order-item').data('id');

    $.get("{{ route('rider.orders.pending') }}", function(data){
        const order = data.orders.find(o => o.id == orderId);
        if (!order) return;

        $('#modalItems').html(order.items.map(i => {
            const qty = parseFloat(i.quantity) || 0;
            const price = parseFloat(i.price) || 0;
            return `
                <div class="flex justify-between items-center border p-2 rounded modal-item" data-id="${i.id}">
                    <!-- Product Info (Left) -->
                    <div class="flex items-center gap-2 w-1/3">
                        <img src="${i.product.full_image}" class="w-12 h-12 rounded" alt="${i.product.name}" />
                        <div>
                            <p class="font-semibold">${i.product.name}</p>
                            <p>Qty: <span class="itemQty">${qty}</span> ${i.product.unit}</p>
                        </div>
                    </div>

                    <!-- Price Input (Middle) -->
                    <div class="w-1/3 text-center">
                        <input type="number" value="${price}" class="priceInput border p-1 w-20 rounded mx-auto" data-id="${i.id}" data-qty="${qty}" />
                    </div>

                    <!-- Subtotal (Right) -->
                    <div class="w-1/3 text-right">
                        <span class="itemSubtotal text-green-700 font-semibold">৳${(qty*price).toFixed(2)}</span>
                    </div>
                </div>
            `;
        }).join(''));

        updateModalTotal(); // initial total
        $('#acceptModal').data('id', orderId).removeClass('hidden');
    });
});

// 🔹 Recalculate subtotal & total when price changes
$(document).on('input', '.priceInput', function() {
    const $row = $(this).closest('.modal-item');
    const qty = parseFloat($(this).data('qty')) || 0;
    const price = parseFloat($(this).val()) || 0;

    // Update row subtotal
    $row.find('.itemSubtotal').text(`৳${(qty*price).toFixed(2)}`);

    // Update modal total
    updateModalTotal();
});

// 🔹 Function to recalc total
function updateModalTotal() {
    let total = 0;
    $('#modalItems .modal-item').each(function() {
        const subtotal = parseFloat($(this).find('.itemSubtotal').text().replace('৳','')) || 0;
        total += subtotal;
    });
    $('#modalTotal').text(`৳${total.toFixed(2)}`);
}


// 🔹 Confirm Accept
$('#confirmAccept').on('click', function() {
    const orderId = $('#acceptModal').data('id');
    const items = [];
    $('.priceInput').each(function() {
        items.push({ id: $(this).data('id'), price: $(this).val() });
    });

    const total = $('#modalTotal').text().replace('৳', '');
    $.ajax({
        url: "{{ route('rider.orders.accept') }}",
        method: 'POST',
        data: {
            id: orderId,
            items: items,
            total_amount: total,
            _token: "{{ csrf_token() }}"
        },
        success: function(res) {
            if (res.success) {
                showToast('success', 'Accepted', '✅ Order accepted successfully!');
                $('#acceptModal').addClass('hidden');
                $(`.order-item[data-id="${orderId}"]`).remove();
            }
        }
    });
});

function closeModal() {
    $('#acceptModal').addClass('hidden');
}
</script>
@endsection
