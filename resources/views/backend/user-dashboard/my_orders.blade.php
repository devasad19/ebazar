@extends('apps.dashboard_master')

@section('content')
<div class="flex min-h-screen bg-gray-50">

    <!-- Sidebar -->
    @include('backend.patrials.aside')

    <!-- Main Area -->
    <div class="flex-1 flex flex-col">

        @include('backend.patrials.top_bar')

        <!-- Content -->
        <section class="bg-white p-6 rounded-2xl shadow mx-6 my-6">
            <h2 class="text-2xl font-bold text-green-700 mb-6">📦 আমার অর্ডারসমূহ</h2>

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

                <div class="w-full mb-4 grid grid-cols-1 md:grid-cols-1 gap-3">
                    @if($orders->count() > 0)
                        @foreach ($orders as $order)
                            @php
                                // 🧮 মোট পরিমাণ হিসাব
 
 
$totals = [
    'কেজি' => [],
    'পিস' => [],
    'ডজন' => [],
    'লিটার' => [],
    'প্যাকেট' => [],
    'টাকা' => []
];

// 👉 1. Normal products
foreach ($order->items as $i) {
    $name = $i->product->name ?? 'অজানা পণ্য';
    $unit = trim($i->product->unit ?? '');
    $qty = floatval($i->quantity ?? 0);

    if ($unit && array_key_exists($unit, $totals)) {
        // ✅ প্রতিটি পণ্যের সাথেই unit যোগ করছি
        $totals[$unit][] = "{$name} ({$qty} {$unit})";
    }
}

// 👉 2. Custom products (from JSON field)
if (!empty($order->custom_products)) {
    foreach ($order->custom_products as $cp) {
        $name = $cp['name'] ?? 'অজানা পণ্য';
        $qty = floatval($cp['quantity'] ?? 0);
        $price = floatval($cp['price'] ?? 0);
        $unit = trim($cp['unit'] ?? '');

        if ($unit === 'টাকা') {
            $totals['টাকা'][] = "{$name} ({$price} টাকা)";
        } elseif ($unit && array_key_exists($unit, $totals)) {
            $totals[$unit][] = "{$name} ({$qty} {$unit})";
        } else {
            // unit না থাকলে default টাকা ধরা
            $totals['টাকা'][] = "{$name} ({$price} টাকা)";
        }
    }
}

// 👉 3. Final formatted text
$totalTextParts = [];

foreach (['কেজি','পিস','ডজন','লিটার','প্যাকেট','টাকা'] as $unit) {
    if (count($totals[$unit]) > 0) {
        // ✅ এখন আলাদা আলাদা পণ্য, শেষে আর আলাদা unit লাগবে না
        $totalTextParts[] = implode(', ', $totals[$unit]);
    }
}

$totalText = implode(', ', $totalTextParts) ?: '-';
 
 
 


                                // 💰 Rider price difference check
                                $hasPriceDifference = $order->items->contains(function($item) {
                                    return $item->rider_price && $item->rider_price > $item->price;
                                });

                                // 🎨 বাটন HTML condition
                                if ($order->status === 'delivered') {
                                    $buttonHTML = '<button class="bg-gray-600 text-white font-semibold px-6 py-2 rounded-lg w-full md:w-auto" disabled>✅ ডেলিভারি সম্পন্ন হয়েছে</button>';
                                } elseif ($order->status === 'cancelled') {
                                    $buttonHTML = '<button class="bg-gray-600 text-white font-semibold px-6 py-2 rounded-lg w-full md:w-auto" disabled>❌ অর্ডার বাতিল হয়েছে</button>';
                                } elseif ($order->status === 'rider_modified_accepted') {
                                    if ($hasPriceDifference) {
                                        
                                    $buttonHTML = '<button class="acceptPriceBtn bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-6 py-2 rounded-lg transition w-full md:w-auto"  data-id="'.$order->id.'">💰 বর্ধিত মূল্য গ্রহণ করুন </button>
                                     <p class="text-xs p-3 ">এস্টিমেট ডেলিভারি সময়ঃ '.$order->delivery_time.' মিনিট</p>';
                                    } else {
                                        $buttonHTML = '<button class="deliverBtn bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-2 rounded-lg transition w-full md:w-auto" data-id="'.$order->id.'">🚚 ডেলিভারি সম্পন্ন করুন</button>';
                                    }
                                } elseif ($order->status === 'accepted') {
                                     $buttonHTML = '<button class="deliverBtn bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-2 rounded-lg transition w-full md:w-auto" data-id="'.$order->id.'">🚚 অর্ডার গৃহীত হয়েছে </button>
                                     <p class="text-xs p-3 ">এস্টিমেট ডেলিভারি সময়ঃ '.$order->delivery_time.' মিনিট</p> ';
                                } elseif ($order->status === 'pending') {
                                    $buttonHTML = '<button class="acceptOrderBtn bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded-lg transition w-full md:w-auto" data-id="'.$order->id.'">🕓 অর্ডার গ্রহণ করুন</button>';
                                } else {
                                    $buttonHTML = '';
                                }

                                // 📦 ডেলিভারি ইনফো
                                $deliveryInfo = '';
                                if (in_array($order->status, ['delivered', 'accepted'])) {
                                    $riderName = $order->rider->name ?? 'অজানা রাইডার';
                                    $deliveredTime = $order->delivered_at ? \Carbon\Carbon::parse($order->delivered_at)->format('d M Y, h:i A') : '';
                                    $statusText = '';

                                    if ($order->delivered_status === 'on_time') {
                                        $statusText = "<span class='text-green-600 font-semibold'>সময়ে ডেলিভারি</span>";
                                    } elseif ($order->delivered_status === 'late') {
                                        $statusText = "<span class='text-red-600 font-semibold'>বিলম্বে ডেলিভারি</span>";
                                    }

                                    $deliveryInfo = "
                                        <div class='mt-3 text-sm bg-indigo-50 text-gray-700 border-t p-2 rounded-md flex flex-col md:flex-row md:justify-between'>
                                            <p>🚴 <strong>রাইডারঃ</strong> {$riderName}</p>
                                            <p>🕓 <strong>ডেলিভারি সময়ঃ</strong> {$deliveredTime}</p>
                                            <p>{$statusText}</p>
                                        </div>
                                    ";
                                }
                            @endphp

                            <div class="relative bg-white p-5 mt-3 rounded-2xl shadow-md hover:shadow-lg border order-item w-full mb-4" data-id="{{ $order->id }}">
                                {{-- 🔹 Order ID badge --}}
                                <span class="absolute -top-3 left-5 bg-indigo-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow">
                                    অর্ডার আইডি: #{{ $order->id }}
                                </span>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-3">
                                    <div>
                                        <h4 class="text-lg font-semibold text-green-700">{{ $order->user->name ?? 'অজানা ক্রেতা' }}</h4>
                                        <p class="text-sm text-gray-600">পিতার নামঃ {{ $order->user->father_name ?? '-' }}</p>
                                        <p>📞 {{ $order->user->phone ?? '-' }}</p>
                                    </div>

                                    <div>
                                        <p><strong>পণ্যঃ</strong> {{ count($order->items) }} টি</p>
                                        <p><strong>মোটঃ</strong> ৳{{ $order->total_amount }}</p>
                                        <p><strong>ঠিকানাঃ</strong> {{ $order->delivery_address ?? '-' }}</p>
                                    </div>

                                    <div class="text-right">
                                        <p><strong>অর্ডার সময়ঃ</strong> {{ $order->created_at->format('d M Y, h:i A') }}</p>
                                        {!! $buttonHTML !!}
                                    </div>
                                </div>

                                <p class="text-sm text-red-500 my-3"><strong>মোট পরিমাণঃ</strong> {{ $totalText }}</p>

                                {!! $deliveryInfo !!}
                            </div>
                        @endforeach
                    @else
                        <p class="text-gray-600 text-center">📭 কোনো অর্ডার পাওয়া যায়নি।</p>
                    @endif
                </div>
            </div>
        </section>
    </div>
</div>

{{-- 💬 Modal --}}
<!-- Accept / Price Modal -->
<div id="riderAcceptModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-40 p-4">
  <div class="bg-white w-full max-w-2xl rounded-2xl shadow-lg p-6 relative">
    <button id="closeRiderModal" class="absolute top-3 right-3 text-gray-600 hover:text-red-600">&times;</button>

    <h3 class="text-xl font-bold text-green-700 mb-3">অর্ডার বিস্তারিত ও মূল্য আপডেট</h3>

    <div id="riderModalBody" class="space-y-3">
      <!-- rendered by JS -->
      <div id="riderOrderMeta" class="text-sm text-gray-700"></div>

      <div class="overflow-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="text-left text-gray-600">
              <th class="py-2 text-center">#</th>
              <th class="py-2 text-center">পণ্য</th>
              <th class="py-2 text-center"> পণ্যের মূল্য (৳)</th>
              <th class="py-2 text-center"> রাইডার মূল্য (৳)</th>
              <th class="py-2 text-right">Subtotal</th>
            </tr>
          </thead>
          <tbody id="riderModalItems"></tbody>
        </table>
      </div>

      <div class="flex justify-between items-center mt-4">
        <div class="flex items-center gap-3">
           
        </div>
        <div class="text-right">
          <p class="text-sm">মোট: <span id="riderModalTotal" class="font-bold text-green-700">৳0</span></p>
        </div>
      </div>

      <div class="flex gap-3 mt-4">
        <button id="confirmOrderCancell" class="ml-auto bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg">❌ অর্ডার বাতিল</button>
        <button id="confirmAccept" class="ml-auto bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">✅ অর্ডার গ্রহণ করুন</button>
        <button id="cancelAccept" class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg">বাতিল</button>
      </div>
    </div>
  </div>
</div>




@endsection

@section('scripts')
<script>
$(function () {
    let selectedOrderId = null;
    let currentItems = []; // hold items from server

    // Open modal: buttons must have .acceptPriceBtn and data-id attribute
    $(document).on('click', '.acceptPriceBtn', function(e) {
        e.preventDefault();
        selectedOrderId = $(this).data('id');
        if (!selectedOrderId) return;



    $.ajax({
        url: '{{ route('rider.order.details') }}',
        method: 'GET',
        data: {id:selectedOrderId},
        success: function(resp) {
            if (!resp.success) {
                Swal.fire('Error', 'Details not found', 'error');
                return;
            }

            const order = resp.order;
            currentItems = order.items || [];
            renderAcceptModal(order);
            $('#riderAcceptModal').removeClass('hidden').addClass('flex');
        },
        error: function(xhr) {
            console.error(xhr.responseJSON);
            Swal.fire('Error', xhr.responseJSON?.message || 'Server error', 'error');
        }
    });

 
    });

    
    function renderAcceptModal(order) {
        $('#riderOrderMeta').html(`<p><strong>অর্ডার:</strong> #${order.id} — মোট : ৳${order.total_amount}</p>`);
        const $body = $('#riderModalItems').empty();
        let total = 0;

        currentItems.forEach(item => {
            const price = parseFloat(item.price) || 0;
            const rider_price = parseFloat(item.rider_price) || price;
            const usedPrice = rider_price; // default show rider_price in input
            const subtotal = usedPrice * (parseFloat(item.qty) || 0);
            total += subtotal;

            const row = $(`
                <tr data-item-id="${item.id}">
                    <td class="py-2 text-center">${1}</td>
                    <td class="py-2 text-center">${item.product_name} <div class="text-xs text-gray-500">${item.qty} ${item.unit}</div></td>
                    <td class="py-2 text-center">${item.price}</td>
                    <td class="py-2 text-center price-input">${usedPrice}</td>
                    <td class="py-2 text-right subtotal">৳${(subtotal).toFixed(2)}</td>
                </tr>
            `);
            $body.append(row);
        });

        $('#riderModalTotal').text('৳' + total.toFixed(2));
         
    }

    // recalc when price input changes
 
 

 

    // Cancel/close
    $('#closeRiderModal, #cancelAccept').on('click', function(){
        $('#riderAcceptModal').addClass('hidden').removeClass('flex');
        selectedOrderId = null;
        currentItems = [];
    });

    // Confirm Accept -> send AJAX POST with updated item prices
    $('#confirmAccept').on('click', function(){
        if (!selectedOrderId) return;

        // collect items
        const itemsPayload = [];
        $('#riderModalItems tr').each(function(){
            const id = $(this).data('item-id');
            const price = parseFloat($(this).find('.price-input').text()) || 0;
            itemsPayload.push({ id: id, price: price });
        });
 
 
        Swal.fire({
            title: 'আপনি নিশ্চিত?',
            text: 'ওভাররাইট করলে অর্ডারের দাম নতুন দাম অনুযায়ী আপডেট হবে।',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'হ্যাঁ, গ্রহণ করুন',
            cancelButtonText: 'না'
        }).then((result) => {
            if (!result.isConfirmed) return;

            $.ajax({
                url: '{{ route('rider.order.accept') }}',
                method: 'POST',
                data: JSON.stringify({
                    id:selectedOrderId,
                    items: itemsPayload
                }),
                contentType: 'application/json; charset=utf-8',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                    if (res.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'সফল',
                            text: res.message || 'অর্ডার গ্রহণ করা হয়েছে',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        // close modal and update card visually
                        $('#riderAcceptModal').addClass('hidden').removeClass('flex');
                        // update the order card total & status on page (if present)
                        const card = $(`.order-item[data-id="${selectedOrderId}"]`);
                        if (card.length) {
                            card.find('.text-green-700.font-semibold').text('৳' + parseFloat(res.order.total_amount).toFixed(2));
                            card.find('.acceptBtn, .acceptPriceBtn, .deliverBtn, .acceptOrderBtn').remove(); // remove old buttons
                            card.find('.text-right').append(`<button class="deliverBtn bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-2 rounded-lg transition w-full md:w-auto" data-id="${selectedOrderId}">🚚 অর্ডার গৃহীত হয়েছে</button>`);
                        }
                    } else {
                        Swal.fire('Error', res.message || 'Failed', 'error');
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error', xhr.responseJSON?.message || 'Server error', 'error');
                }
            });
        });
    });

    

    // Confirm Accept -> send AJAX POST with updated item prices
    $('#confirmOrderCancell').on('click', function(){
        if (!selectedOrderId) return;

        // collect items
        const itemsPayload = [];
        $('#riderModalItems tr').each(function(){
            const id = $(this).data('item-id');
            const price = parseFloat($(this).find('.price-input').val()) || 0;
            itemsPayload.push({ id: id, price: price });
        });
 
        Swal.fire({
            title: 'আপনি নিশ্চিত?',
            text: 'আপনি অর্ডার করেছিলেন, সেই অর্ডার কোন কারনে বাতিল করছেন।।',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'হ্যাঁ, বাতিল করছি।',
            cancelButtonText: 'না'
        }).then((result) => {
            if (!result.isConfirmed) return;

            $.ajax({
                url: '{{ route('rider.order.cancell') }}',
                method: 'POST',
                data: JSON.stringify({
                    id:selectedOrderId
                }),
                contentType: 'application/json; charset=utf-8',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                    if (res.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'সফল',
                            text: res.message || 'অর্ডার বাতিল করা হয়েছে',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        // close modal and update card visually
                        $('#riderAcceptModal').addClass('hidden').removeClass('flex');
                        // update the order card total & status on page (if present)
                        const card = $(`.order-item[data-id="${selectedOrderId}"]`);
                        if (card.length) {
                            card.find('.text-green-700.font-semibold').text('৳' + parseFloat(res.order.total_amount).toFixed(2));
                            card.find('.acceptBtn, .acceptPriceBtn, .deliverBtn, .acceptOrderBtn').remove(); // remove old buttons
                            card.find('.text-right').append(`<button class="deliverBtn bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-2 rounded-lg transition w-full md:w-auto" data-id="${selectedOrderId}">🚚 অর্ডার গৃহীত হয়েছে</button>`);
                        }
                    } else {
                        Swal.fire('Error', res.message || 'Failed', 'error');
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error', xhr.responseJSON?.message || 'Server error', 'error');
                }
            });
        });
    });



    // OPTIONAL: bind deliverBtn to mark delivered (example)
    $(document).on('click', '.deliverBtn', function(){
        const orderId = $(this).data('id');
        // call your endpoint to set delivered (not provided here)...
        Swal.fire('Info', 'ডেলিভারি সম্পন্ন করার এন্ডপয়েন্ট এখানে কল করবেন', 'info');
    });
});
</script>

@endsection