@extends('apps.dashboard_master')

@section('content')

<div class="flex min-h-screen bg-gray-50">

    <!-- 🟢 Sidebar -->
    @include('backend.patrials.aside')
    <!-- 🟡 Main Content Area -->
    <div class="flex-1 flex flex-col">

        <!-- Top Bar -->
    @include('backend.patrials.top_bar')

        <!-- Content Body --> 
  <section class="bg-gray-50 p-6 rounded-2xl shadow">
     <h2 class="text-2xl font-bold text-green-700 mb-6">🛒 আমার কার্ট</h2>

    @if(count($cartItems) > 0)
    <div class="overflow-x-auto">
        <table class="w-full bg-white rounded-2xl shadow">
            <thead class="bg-green-100 text-green-700 font-semibold">
                <tr>
                    <th class="p-3 text-left">পণ্য</th>
                    <th class="p-3">দাম</th>
                    <th class="p-3">পরিমাণ</th>
                    <th class="p-3">মোট</th>
                    <th class="p-3">অ্যাকশন</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cartItems as $item)
                <tr class="border-b">
                    <td class="flex items-center gap-3 p-3">
                        <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-16 h-16 object-cover rounded-lg">
                        <span class="font-semibold text-gray-800">{{ $item['name'] }}</span>
                    </td>
                    <td class="text-center text-green-600 font-semibold">৳{{ $item['price'] }}</td>
                    <td class="text-center">
                        <div class="inline-flex items-center border rounded-full overflow-hidden">
                            <button type="button" class="px-3 py-1 bg-indigo-500 text-white" onclick="decreaseQty(this)">-</button>
                            <input type="number" value="{{ $item['quantity'] }}" min="1" class="w-16 text-center border-none focus:ring-0 quantityInput" onchange="updateItemTotal(this)">
                            <button type="button" class="px-3 py-1 bg-indigo-500 text-white" onclick="increaseQty(this)">+</button>
                        </div>
                    </td>
                    <td class="text-center text-green-700 font-semibold itemTotal">৳{{ $item['price'] * $item['quantity'] }}</td>
                    <td class="text-center">
                        <button class="text-red-500 hover:text-red-700 font-bold text-xl" onclick="removeItem(this)">×</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>


        <div class="flex justify-between mt-6 text-lg font-bold text-green-700">
            <span>মোট</span>
            <span id="cartTotal">৳{{ $total }} <span class="text-sm text-gray-600 mb-1">+ ডেলিভারি চার্জ</span></span>
        </div>
        <p class="text-sm text-red-500 mt-3 text-right">ডেলিভারি চার্জঃ আপনার বাড়ি থেকে বাজারের দূরত্ব, ভেন বা অটোরিকশার ভাড়ার সমান।</p>


    <div class="bg-white rounded-2xl shadow p-6">
        <h3 class="text-xl font-semibold text-gray-700 mb-4">ডেলিভারি তথ্য</h3>
        
        <form action="#" method="POST" class="space-y-4">
            @csrf

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
        </form>
    </div>



    @else
    <p class="text-center text-gray-500 py-12">আপনার কার্ট খালি। 🛒</p>
    @endif
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
function removeItem(btn) {
    const row = btn.closest('tr');
    row.remove();
    updateTotal();
}

function showQtyNotification() {
    const notif = document.getElementById('qtyNotification');
    notif.classList.remove('opacity-0');
    notif.classList.add('opacity-100');

    setTimeout(() => {
        notif.classList.remove('opacity-100');
        notif.classList.add('opacity-0');
    }, 1000);
}

function decreaseQty(btn) {
    const input = btn.nextElementSibling;
    if (parseInt(input.value) > 1) {
        input.value = parseInt(input.value) - 1;
        updateItemTotal(input);
        showQtyNotification();
    }
}

function increaseQty(btn) {
    const input = btn.previousElementSibling;
    input.value = parseInt(input.value) + 1;
    updateItemTotal(input);
    showQtyNotification();
}

function updateItemTotal(input) {
    const row = input.closest('tr');
    const price = parseInt(row.querySelector('td:nth-child(2)').innerText.replace('৳',''));
    const qty = parseInt(input.value);
    row.querySelector('.itemTotal').innerText = `৳${price * qty}`;
    updateTotal();
}

function updateTotal() {
    let total = 0;
    document.querySelectorAll('.itemTotal').forEach(el => {
        total += parseInt(el.innerText.replace('৳',''));
    });
    document.getElementById('cartTotal').innerText = `৳${total}`;
}
</script>

<script>
    const newAddressCheckbox = document.getElementById('newAddress');
    const addressInput = document.getElementById('addressInput');
    const phoneInput = document.getElementById('phoneInput');

    newAddressCheckbox.addEventListener('change', function() {
        if(this.checked) {
            addressInput.removeAttribute('readonly');
            phoneInput.removeAttribute('readonly');
            addressInput.focus();
        } else {
            addressInput.setAttribute('readonly', true);
            phoneInput.setAttribute('readonly', true);
        }
    });
</script>

@endsection