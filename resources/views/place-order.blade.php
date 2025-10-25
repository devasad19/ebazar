@extends('apps.front_master')
@section('content')
<section class="max-w-4xl mx-auto px-6 py-12">
    <h2 class="text-3xl font-bold text-green-700 mb-6 text-center">🛒 Checkout</h2>

    @if(count($cartItems) > 0)
    <div class="bg-white rounded-2xl shadow p-6 mb-6">
        <h3 class="text-xl font-semibold text-gray-700 mb-4">আপনার পণ্যসমূহ</h3>
        <div class="space-y-3">
@foreach($cartItems as $cart)
<div class="cart-item flex justify-between items-center border-b pb-3" data-id="{{ $cart->id }}">
    <div class="flex items-center gap-3">
        <img src="{{ $cart->product->image ? url('uploads/products/'.$cart->product->image) : '' }}"
             class="w-16 h-16 object-cover rounded-lg">
        <div>
            <h4 class="font-semibold text-gray-800">{{ $cart->product->name }}</h4>

            <div class="flex items-center mt-2">
                <button type="button" class="bg-indigo-500 text-white px-3 py-1 rounded-l-full" onclick="changeQty(this, 'decrease')">-</button>
                <input type="number" value="{{ $cart->quantity }}" min="1"
                       class="w-16 text-center border-t border-b border-green-300 focus:outline-none focus:ring-2 focus:ring-green-300 py-1 mx-1">
                <button type="button" class="bg-indigo-500 text-white px-3 py-1 rounded-r-full" onclick="changeQty(this, 'increase')">+</button>
            </div>
        </div>
    </div>

    <div class="flex flex-col items-end">
        <p class="text-green-600 ">৳{{ $cart->price }} X <span class="itemQty">{{ $cart->quantity }}</span> </p>
    </div>

    <div class="flex flex-col items-end">
        <p>Item Total: <span class="text-green-600 font-semibold itemTotal">৳{{ $cart->price * $cart->quantity }}</span></p>
        <button type="button" class="text-red-500 hover:text-red-700 mt-1 text-lg font-bold" onclick="removeItem(this)">❌</button>
    </div>
</div>
@endforeach
 
        </div>
        <div class="flex justify-between mt-6 text-lg font-bold text-green-700">
            <span>মোট</span>
            <span id="cartTotal">৳{{ $total }} <span class="text-sm text-gray-600 mb-1">+ ডেলিভারি চার্জ</span></span>
        </div>
        <p class="text-sm text-red-500 mt-3 text-right">ডেলিভারি চার্জঃ আপনার বাড়ি থেকে বাজারের দূরত্ব, ভেন বা অটোরিকশার ভাড়ার সমান।</p>
    </div>

    <div class="bg-white rounded-2xl shadow p-6">
        <h3 class="text-xl font-semibold text-gray-700 mb-4">ডেলিভারি তথ্য</h3>
        
        <form action="{{ route('place.order') }}" method="POST" class="space-y-4">
            @csrf
 

            <!-- Name -->
            <div class="flex flex-col">
                <label for="name" class="text-sm text-gray-600 mb-1">আপনার নাম</label>
                <input type="text" name="name" id="name" value="{{ $user->name }}" 
                    class="w-full border px-3 py-2 rounded-lg focus:ring-2 focus:ring-green-400 outline-none" readonly>
            </div>

            <!-- Father's Name -->
            <div class="flex flex-col">
                <label for="father_name" class="text-sm text-gray-600 mb-1">পিতার নাম</label>
                <input type="text" name="father_name" id="father_name" value="{{ $user->father_name }}" 
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
                    value="{{ $user->address }}" 
                    class="w-full border px-3 py-2 rounded-lg focus:ring-2 focus:ring-green-400 outline-none" 
                    placeholder="ঠিকানা" readonly>
            </div>

            <!-- Phone -->
            <div class="flex flex-col">
                <label for="phoneInput" class="text-sm text-gray-600 mb-1">মোবাইল নম্বর</label>
                <input type="tel" name="phone" id="phoneInput" 
                    value="{{ $user->phone }}" 
                    class="w-full border px-3 py-2 rounded-lg focus:ring-2 focus:ring-green-400 outline-none" 
                    placeholder="মোবাইল নম্বর" readonly>
            </div>

            <button type="submit" class="bg-green-600 text-white w-full py-3 rounded-lg hover:bg-green-700 transition font-semibold">
                ✅ অর্ডার কনফার্ম করুন
            </button>
        </form>
    </div>
    @else
        <p class="text-center text-gray-500">আপনার কার্ট খালি।</p>
    @endif
</section>





<!-- Quantity Updated Notification -->
<div id="qtyNotification" 
     class="fixed bottom-5 right-5 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg opacity-0 transition-opacity duration-300 z-50">
  ✅ Quantity updated
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



 
// 🔹 Quantity Change
function changeQty(btn, type) {
    const item = btn.closest('.cart-item'); 
    if (!item) return;

    const input = item.querySelector('input');
    let qty = parseInt(input.value);
    qty = (type === 'increase') ? qty + 1 : Math.max(1, qty - 1);
    input.value = qty;
   // ✅ সঠিকভাবে .itemQty সিলেক্ট করুন
    const itemQty = item.querySelector('.itemQty');
    if (itemQty) {
        itemQty.textContent = qty; // text নয়, textContent ব্যবহার করুন
    }

    const id = item.dataset.id;

    $.ajax({
        url: "{{ route('cart.update') }}",
        method: 'POST',
        data: { id, quantity: qty },
        success: function(data) {
            if (data.success) {
                item.querySelector('.itemTotal').innerText = `৳${data.itemTotal}`;
                document.getElementById('cartTotal').innerText = `৳${data.cartTotal}`;
                showToast('success', 'Updated', 'পরিমাণ আপডেট হয়েছে ✅');
            } else {
                showToast('error', 'Failed', 'আপডেট ব্যর্থ হয়েছে ❌');
            }
        },
        error: function(xhr) {
            showToast('error', 'Server Error', 'সার্ভারে সমস্যা হয়েছে!');
            console.log(xhr.responseJSON?.message || 'Something went wrong!');
        }
    });
}

// 🔹 Remove Item
function removeItem(btn) {
    const item = btn.closest('.cart-item');
    if (!item) return;

    const id = item.dataset.id;

    swalConfirm('আপনি কি এই পণ্যটি কার্ট থেকে মুছে ফেলতে চান?', function() {
        $.ajax({
            url: "{{ route('cart.remove') }}",
            method: 'POST',
            data: { id },
            success: function(data) {
                if (data.success) {
                    item.remove();
                    document.getElementById('cartTotal').innerText = `৳${data.cartTotal}`;
                    showToast('success', 'Removed', 'পণ্যটি মুছে ফেলা হয়েছে 🗑️');
                } else {
                    showToast('error', 'Failed', 'রিমুভ করা যায়নি!');
                }
            },
            error: function(xhr) {
                showToast('error', 'Server Error', 'সার্ভারে সমস্যা হয়েছে!');
                console.log(xhr.responseJSON?.message || 'Something went wrong!');
            }
        });
    });
}
 
 
  

 
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
