@extends('apps.dashboard_master')

@section('content')

<div class="flex min-h-screen bg-gray-50">

    <!-- 🟢 Sidebar -->
    @include('backend.patrials.aside')
    <!-- 🟡 Main Content Area -->
    <div class="flex-1 flex flex-col">

        <!-- Top Bar -->
    @include('backend.patrials.top_bar')
        <form action="{{ route('place.order') }}" method="POST" class="space-y-6">
    @csrf
        <!-- Content Body --> 
  <section class="bg-gray-50 p-6 rounded-2xl shadow">
     <h2 class="text-2xl font-bold text-green-700 mb-6">🛒 আমার কার্ট</h2>
        
    @if(count($cartItems) > 0)
        <div class="space-y-3">
            @foreach($cartItems as $cart)
<div class="cart-item flex justify-between items-center border-b pb-3 py-2 gap-4" data-product_id="{{ $cart->product_id }}" data-id="{{ $cart->id }}">
    <!-- Product Info -->
    <div class="flex items-center gap-3 w-1/3">
        <img src="{{ $cart->product->image ? url('uploads/products/'.$cart->product->image) : '' }}"
             class="w-16 h-16 object-cover rounded-lg">
        <h4 class="font-semibold text-gray-800">{{ $cart->product->name }}</h4>
    </div>

    <!-- Quantity Control -->
    <div class="flex items-center justify-center w-1/4">
        <button type="button" 
            class="bg-indigo-500 text-white px-3 py-1 rounded-l-full"
            onclick="changeQty(this, 'decrease')">-</button>
        <input type="number" 
            value="{{ $cart->quantity }}" 
            min="1"
            class="w-16 text-center border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-300 py-1 mx-1 rounded">
        <button type="button" 
            class="bg-indigo-500 text-white px-3 py-1 rounded-r-full"
            onclick="changeQty(this, 'increase')">+</button>
    </div>

    <!-- Price Info -->
    <div class="flex flex-col items-center w-1/4">
        <p class="text-green-600 text-sm">৳{{ $cart->price }} x <span class="itemQty">{{ $cart->quantity }}</span></p>
        <p></p>
    </div>
    
    <!-- Remove Button -->
    <div class="flex3 items-center text-right justify-end w-1/6">
        <p class="text-green-700 font-semibold text-base itemTotal">৳{{ $cart->price * $cart->quantity }}</p>
        <button type="button" class="text-red-500 hover:text-red-700 text-lg font-bold"
            onclick="removeItem(this)">❌</button>
    </div>
</div>
            @endforeach
        </div>

        <!-- ✅ Custom Products Section (now inside form) -->
        <div class="bg-gray-50 rounded-2xl border p-5  mt-3" id="customProductsSection">
            <h3 class="text-lg font-semibold text-gray-700 mb-4 flex justify-between">
                <span>➕ কাস্টম পণ্য যোগ করুন</span>
                <button type="button" id="addCustomProduct"
                    class="bg-green-600 text-white px-3 py-1 rounded-lg hover:bg-green-700 transition">+ নতুন পণ্য</button>
            </h3>
            <div id="customProductList" class="space-y-3 w-full"></div>
                
        </div>

        <input type="hidden" id="baseTotal" value="{{ $total }}">
<input type="hidden" id="customTotal" name="customTotal" value="0">


        <div class="flex justify-between mt-6 text-lg font-bold text-green-700">
            <span>মোট</span>
            <span id="cartTotal">৳{{ $total }} <span class="text-sm text-gray-600 mb-1">+ ডেলিভারি চার্জ</span></span>
        </div>
        <p class="text-sm text-red-500 mt-3 text-right">ডেলিভারি চার্জঃ আপনার বাড়ি থেকে বাজারের দূরত্ব, ভেন বা অটোরিকশার ভাড়ার সমান।</p>


    <div class="bg-white rounded-2xl shadow p-6">
        <h3 class="text-xl font-semibold text-gray-700 mb-4">ডেলিভারি তথ্য</h3>

            @php
                // Example: user profile data
                $userName = Auth::user()->name ?? 'মোঃ আহাদ আলী';
                $fatherName = Auth::user()->father_name ?? 'মোঃ জমশেদ আলী';
                $userAddress = Auth::user()->address ?? 'রামাকানা, দুল্লা, চেচুয়ায় বাজার';
                $userPhone = Auth::user()->phone ?? '০১২৪৭৮৮৫৫৫৫৫';
            @endphp

            <!-- Name -->
            <div class="flex flex-col">
                <label for="name" class="text-sm text-gray-600 mb-1">আপনার নাম</label>
                <input type="text" name="name" id="name" value="{{ $userName }}" 
                    class="w-full border px-3 py-2 rounded-lg focus:ring-2 focus:ring-green-400 outline-none" readonly>
            </div>

            <!-- Father's Name -->
            <div class="flex flex-col">
                <label for="father_name" class="text-sm text-gray-600 mb-1">পিতার নাম</label>
                <input type="text" name="father_name" id="father_name" value="{{ $fatherName }}" 
                    class="w-full border px-3 py-2 rounded-lg focus:ring-2 focus:ring-green-400 outline-none" readonly>
            </div>

            <!-- New Address Checkbox -->
            <div class="flex items-center gap-2">
                <input type="checkbox" id="newAddress" class="form-checkbox h-5 w-5 text-green-600">
                <label for="newAddress" class="text-sm text-gray-700">নতুন ঠিকানা যোগ করুন</label>
            </div>

            <!-- Address -->
            <div class="flex flex-col">
                <label for="addressInput" class="text-sm text-gray-600 mb-1">ঠিকানা</label>
                <input type="text" name="address" id="addressInput" 
                    value="{{ $userAddress }}" 
                    class="w-full border px-3 py-2 rounded-lg focus:ring-2 focus:ring-green-400 outline-none" 
                    placeholder="ঠিকানা" readonly>
            </div>

            <!-- Phone -->
            <div class="flex flex-col">
                <label for="phoneInput" class="text-sm text-gray-600 mb-1">মোবাইল নম্বর</label>
                <input type="tel" name="phone" id="phoneInput" 
                    value="{{ $userPhone }}" 
                    class="w-full border px-3 py-2 rounded-lg focus:ring-2 focus:ring-green-400 outline-none" 
                    placeholder="মোবাইল নম্বর" readonly>
            </div>

            <button type="submit" class="bg-green-600 text-white w-full py-3 rounded-lg hover:bg-green-700 transition font-semibold">
                ✅ অর্ডার কনফার্ম করুন
            </button>
        </div>

        @else
        <p class="text-center text-gray-500 py-12">আপনার কার্ট খালি। 🛒</p>
        @endif

    </form>
</section>

 

<!-- Quantity Updated Notification -->
<div id="qtyNotification" 
     class="fixed bottom-5 right-5 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg opacity-0 transition-opacity duration-300 z-50">
  ✅ Quantity updated
</div>
 
    </div>
</div>

@endsection
@section('scripts')
<script>


function showQtyNotification() {
    const notif = document.getElementById('qtyNotification');
    if (!notif) return;

    notif.classList.remove('opacity-0');
    notif.classList.add('opacity-100');

    // ২ সেকেন্ড পরে আবার fade out হবে
    setTimeout(() => {
        notif.classList.remove('opacity-100');
        notif.classList.add('opacity-0');
    }, 2000);
}

 // ======================
// 🛒 Main Cart Functions
// ======================

// 🔹 Quantity Change
function changeQty(btn, type) {
    const item = btn.closest('.cart-item');
    if (!item) return;

    const input = item.querySelector('input');
    let qty = parseInt(input.value);
    qty = type === 'increase' ? qty + 1 : Math.max(1, qty - 1);
    input.value = qty;

    const itemQty = item.querySelector('.itemQty');
    if (itemQty) itemQty.textContent = qty;

    const id = item.dataset.id;

    $.ajax({
        url: "{{ route('cart.update') }}",
        method: 'POST',
        data: { id, quantity: qty },
        success: function (data) {
            if (data.success) {
                item.querySelector('.itemTotal').innerText = `৳${data.itemTotal}`;
                document.getElementById('baseTotal').value = data.cartTotal; // update hidden baseTotal
                updateGrandTotal();
                showToast('success', 'Updated', 'পরিমাণ আপডেট হয়েছে ✅');
            } else {
                showToast('error', 'Failed', 'আপডেট ব্যর্থ হয়েছে ❌');
            }
        },
        error: function () {
            showToast('error', 'Server Error', 'সার্ভারে সমস্যা হয়েছে!');
        },
    });
}

// 🔹 Remove Item
function removeItem(btn) {
    const item = btn.closest('.cart-item');
    if (!item) return;

    console.log(item);
    
    const id = item.dataset.product_id;
    const routeUrl = `{{ route('cart.remove', ':id') }}`.replace(':id', id);

    swalConfirm('আপনি কি এই পণ্যটি কার্ট থেকে মুছে ফেলতে চান?', function () {
        $.ajax({
            url: routeUrl,
            method: 'POST',
            success: function (data) {
                if (data.success) {
                    item.remove();
                    document.getElementById('baseTotal').value = data.cartTotal;
                    updateGrandTotal();
                    showToast('success', 'Removed', 'পণ্যটি মুছে ফেলা হয়েছে 🗑️');
                } else {
                    showToast('error', 'Failed', 'রিমুভ করা যায়নি!');
                }
            },
            error: function () {
                showToast('error', 'Server Error', 'সার্ভারে সমস্যা হয়েছে!');
            },
        });
    });
}

// 🔹 New Address Toggle
const newAddressCheckbox = document.getElementById('newAddress');
const addressInput = document.getElementById('addressInput');
const phoneInput = document.getElementById('phoneInput');

if (newAddressCheckbox) {
    newAddressCheckbox.addEventListener('change', function () {
        const editable = this.checked;
        [addressInput, phoneInput].forEach((input) => {
            if (editable) {
                input.removeAttribute('readonly');
                input.focus();
            } else {
                input.setAttribute('readonly', true);
            }
        });
    });
}

// ===========================
// 🧩 Custom Product Functions
// ===========================

document.getElementById('addCustomProduct').addEventListener('click', function () {
    const id = Date.now();

    const productHtml = `
        <div class="custom-product border p-4 rounded-2xl bg-gray-50 shadow-sm flex flex-col md:flex-row md:items-center md:gap-3 gap-3" data-id="${id}">
            <input type="text" name="custom_products[${id}][name]" class="cp-name border border-green-300 p-2 rounded-lg w-full focus:ring-2 focus:ring-green-400 outline-none" placeholder="পণ্যের নাম" required>

            <div class="flex flex-col sm:flex-row w-full gap-3">
                <input type="number" name="custom_products[${id}][qty]" class="cp-qty border border-green-300 p-2 rounded-lg w-full sm:w-1/3 focus:ring-2 focus:ring-green-400 outline-none"
                    placeholder="পরিমাণ" step="0.1">

                <select name="custom_products[${id}][unit]" class="cp-unit border border-green-300 p-2 rounded-lg w-full sm:w-1/3 focus:ring-2 focus:ring-green-400 outline-none" required>
                    <option value="কেজি">কেজি</option>
                    <option value="লিটার">লিটার</option>
                    <option value="পিস">পিস</option>
                    <option value="ডজন">ডজন</option>
                    <option value="টাকা">টাকা</option>
                </select>

                <input type="number" name="custom_products[${id}][price]" class="cp-price border border-green-300 p-2 rounded-lg w-full sm:w-1/3 focus:ring-2 focus:ring-green-400 outline-none"
                    placeholder="মূল্য (৳)">
            </div>

            <button type="button" class="text-red-500 hover:text-red-700 font-bold text-2xl self-end md:self-center removeCustom">×</button>
        </div>
    `;

    document.getElementById('customProductList').insertAdjacentHTML('beforeend', productHtml);
    updateCustomTotal();
});

document.addEventListener('click', function (e) {
    if (e.target.classList.contains('removeCustom')) {
        e.target.closest('.custom-product').remove();
        updateCustomTotal();
    }
});

document.addEventListener('input', function (e) {
    if (e.target.closest('.custom-product')) {
        updateCustomTotal();
    }
});

// 🔹 Custom total হিসাব করা
function updateCustomTotal() {
    let customTotal = 0;

    document.querySelectorAll('.custom-product').forEach((el) => {
        const qty = parseFloat(el.querySelector('.cp-qty')?.value || 0);
        const unit = el.querySelector('.cp-unit')?.value;
        const price = parseFloat(el.querySelector('.cp-price')?.value || 0);

        if (unit === 'টাকা') {
            customTotal += price;
        } else if (qty > 0 && price > 0) {
            customTotal += qty * price;
        }
    });

    // Hidden custom total update
    document.getElementById('customTotal').value = customTotal;
    updateGrandTotal();
}

// 🔹 Base + Custom যোগ করে Grand total দেখানো
function updateGrandTotal() {
    const base = parseFloat(document.getElementById('baseTotal').value || 0);
    const custom = parseFloat(document.getElementById('customTotal').value || 0);
    const grand = base + custom;

    document.getElementById('cartTotal').innerText = `৳${grand.toFixed(2)}`;
}


</script>
@endsection