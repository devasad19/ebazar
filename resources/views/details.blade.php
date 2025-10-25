@extends('apps.front_master')
@section('content')


<!-- 🧭 Breadcrumb -->
<section class="max-w-7xl mx-auto px-6 py-6">
  <nav class="text-sm text-gray-500 mb-4">
    <a href="{{ url('/') }}" class="hover:text-green-600">হোম</a> /
    <a href="#" class="hover:text-green-600">পণ্যসমূহ</a> /
    <span class="text-green-700 font-semibold">{{ $product->name }}</span>
  </nav>
</section>

<section class="max-w-7xl mx-auto px-6 py-10">
  <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

    <!-- 🧾 Product Details -->
    <div class="md:col-span-2 bg-white rounded-2xl shadow p-6">
      <div class="md:flex gap-6">
        <!-- 📸 Product Image -->
        <div class="md:w-1/2 flex justify-center mb-6 md:mb-0">
          <img src="{{ url('uploads/products/'.$product->image) }}"
               alt="{{ $product->name }}"
               class="rounded-xl w-full md:w-72 h-60 object-cover shadow">
        </div>

        <!-- 🧾 Product Info -->
        <div class="md:w-1/2">
          <h2 class="text-3xl font-bold text-green-700 mb-2">{{ $product->name }}</h2>
          <p class="text-lg text-gray-600 mb-3">{{ $product->short_description ?? 'বিবরণ নেই' }}</p>

          <div class="mb-4">
            <p class="text-2xl font-bold text-green-600 mb-1">৳{{ $product->price }} / {{ $product->unit }}</p>
            <p class="text-sm text-gray-500">বিতরণকারী:
              <span class="font-semibold text-gray-700">ফ্রি আছে, অর্ডার করুন, ইন শা আল্লাহ্‌।</span>
            </p>
            <p class="text-sm text-gray-500">বাজার:
              <span class="font-semibold text-gray-700">{{ optional($product->bazar)->name ?? 'N/A' }}</span>
            </p>
            <p class="text-sm text-gray-500">*নোট:
              <span class="font-semibold text-red-600">
                পণ্যের দাম ৫ থেকে ৭ টাকা কম/বেশি হতে পারে।
              </span>
            </p>
          </div>

          <!-- 🛒 Quantity & Add to Cart -->
          <div class="flex items-center gap-3 mb-6">
            <div class="flex items-center mt-2">
              <button type="button" class="bg-indigo-500 text-white hover:bg-indigo-600 px-4 py-2 rounded-l-full font-bold"
                      onclick="decreaseQty(this)">-</button>

              <input type="number" value="1" min="1"
                     class="w-16 text-center border-t border-b border-indigo-300 py-2 focus:outline-none" />

              <button type="button" class="bg-indigo-500 text-white hover:bg-indigo-600 px-4 py-2 rounded-r-full font-bold"
                      onclick="increaseQty(this)">+</button>
            </div>

            <button class="addToCartBtn bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition"
                    data-id="{{ $product->id }}"
                    data-name="{{ $product->name }}"
                    data-price="{{ $product->price }}"
                    data-image="{{ url('uploads/products/'.$product->image) }}">
              🛒 ব্যাগে যোগ করুন
            </button>
          </div>
        </div>
      </div>

      <!-- 🔎 Description -->
      <div class="border-t pt-5 mt-6 text-gray-700">
        <h4 class="font-semibold mb-2 text-green-700">পণ্যের বিস্তারিত বিবরণ:</h4>
        <p class="text-sm leading-relaxed">
          {{ $product->description ?? 'এই পণ্যের বিস্তারিত বিবরণ পাওয়া যায়নি।' }}
        </p>
      </div>
    </div>

    <!-- 📦 Other Products -->
    <div class="bg-white rounded-2xl shadow p-5">
      <h3 class="text-xl font-bold text-green-700 mb-4 flex items-center gap-2">🛒 অন্যান্য পণ্য</h3>

      @forelse($relatedProducts as $item)
        <div class="flex items-center gap-3 border border-gray-100 rounded-xl p-3 hover:shadow-md transition w-full bg-white mb-3">
          <img src="{{ url('uploads/products/'.$item->image) }}"
              alt="{{ $item->name }}" class="w-20 h-20 rounded-lg object-cover flex-shrink-0">
          <div class="flex flex-col justify-between w-full">
            <div>
              <h4 class="font-semibold text-gray-800">{{ $item->name }}</h4>
              <p class="text-green-600 font-medium text-sm">৳{{ $item->price }} / {{ $item->unit }}</p>
            </div>
            <div class="flex items-center justify-between gap-3 mt-1">
              <p class="text-xs text-gray-500">বাজার: {{ optional($item->bazar)->name ?? 'N/A' }}</p>
              <a href="{{ route('home.product.details', $item->id) }}" class="text-xs text-green-600 hover:underline whitespace-nowrap">
                বিস্তারিত দেখুন →
              </a>
            </div>
          </div>
        </div>
      @empty
        <p class="text-gray-500 text-center">এই বাজারে অন্য কোনো পণ্য নেই।</p>
      @endforelse
    </div>
  </div>
</section>


  <!-- 🧩 Related Products -->
  <section class="max-w-7xl mx-auto px-6 pb-16">
    <div class="flex justify-between items-center mb-6">
      <h3 class="text-2xl font-bold text-green-700">🔄 সম্পর্কিত পণ্য</h3>
      <a href="#" class="text-sm text-green-600 hover:underline">সব দেখুন →</a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
      
      <!-- Product Card -->
      <div class="bg-white rounded-xl shadow hover:shadow-lg transition p-4 relative group">
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQbC47y2YlKDgBzBmLitYb75dDU6F028k7oGQ&s" 
             alt="সবজি" 
             class="rounded-lg w-full h-44 object-cover group-hover:scale-105 transition duration-300">
        <button class="absolute top-3 right-3 bg-white p-2 rounded-full shadow hover:text-red-500">
          ❤️
        </button>
        <div class="mt-4">
          <h4 class="font-bold text-lg">তাজা টমেটো</h4>
          <p class="text-green-600 font-semibold">৳৮০ / কেজি</p>
          <p class="text-sm text-gray-500">বাজার: সাভার বাজার</p>
          <button class="bg-green-600 text-white w-full py-2 rounded-lg hover:bg-green-700 transition mt-2">
            বিস্তারিত দেখুন
          </button>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow hover:shadow-lg transition p-4 relative group">
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTyXv5xbCdfaQISj6ljP_klkXK9PKpqyCx9dA&s" 
             alt="সবজি" 
             class="rounded-lg w-full h-44 object-cover group-hover:scale-105 transition duration-300">
        <button class="absolute top-3 right-3 bg-white p-2 rounded-full shadow hover:text-red-500">
          ❤️
        </button>
        <div class="mt-4">
          <h4 class="font-bold text-lg">পটল</h4>
          <p class="text-green-600 font-semibold">৳৫০ / কেজি</p>
          <p class="text-sm text-gray-500">বাজার: রাজশাহী বাজার</p>
           <p class="text-xs text-gray-500">বিতরণকারী: হোসেন আলি</p>
          <button class="bg-green-600 text-white w-full py-2 rounded-lg hover:bg-green-700 transition mt-2">
            বিস্তারিত দেখুন
          </button>
        </div>
      </div>
      <div class="bg-white rounded-xl shadow hover:shadow-lg transition p-4 relative group">
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQbC47y2YlKDgBzBmLitYb75dDU6F028k7oGQ&s" 
             alt="সবজি" 
             class="rounded-lg w-full h-44 object-cover group-hover:scale-105 transition duration-300">
        <button class="absolute top-3 right-3 bg-white p-2 rounded-full shadow hover:text-red-500">
          ❤️
        </button>
        <div class="mt-4">
          <h4 class="font-bold text-lg">তাজা টমেটো</h4>
          <p class="text-green-600 font-semibold">৳৮০ / কেজি</p>
          <p class="text-sm text-gray-500">বাজার: সাভার বাজার</p>
           <p class="text-xs text-gray-500">বিতরণকারী: হোসেন আলি</p>
          <button class="bg-green-600 text-white w-full py-2 rounded-lg hover:bg-green-700 transition mt-2">
            বিস্তারিত দেখুন
          </button>
        </div>
      </div>
      <div class="bg-white rounded-xl shadow hover:shadow-lg transition p-4 relative group">
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTyXv5xbCdfaQISj6ljP_klkXK9PKpqyCx9dA&s" 
             alt="সবজি" 
             class="rounded-lg w-full h-44 object-cover group-hover:scale-105 transition duration-300">
        <button class="absolute top-3 right-3 bg-white p-2 rounded-full shadow hover:text-red-500">
          ❤️
        </button>
        <div class="mt-4">
          <h4 class="font-bold text-lg">পটল</h4>
          <p class="text-green-600 font-semibold">৳৫০ / কেজি</p>
          <p class="text-sm text-gray-500">বাজার: রাজশাহী বাজার</p>
           <p class="text-xs text-gray-500">বিতরণকারী: হোসেন আলি</p>
          <button class="bg-green-600 text-white w-full py-2 rounded-lg hover:bg-green-700 transition mt-2">
            বিস্তারিত দেখুন
          </button>
        </div>
      </div>

    </div>

<!-- 🛍️ Cart Modal -->
<!-- 🛍️ Cart Modal -->
<div id="cartModal"
  class="fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center opacity-0 pointer-events-none transition-opacity duration-300 z-50">
  <div class="bg-white w-96 rounded-2xl shadow-lg p-5 relative">
    <h3 class="text-xl font-bold text-green-700 mb-4">🛒 আপনার কার্ট</h3>

    <div id="cartItems" class="space-y-3 max-h-60 overflow-y-auto">
      <!-- JS দিয়ে কার্ট আইটেম এখানে আসবে -->
    </div>

    <div class="border-t pt-3 mt-4 flex justify-between items-center">
      <p class="font-bold text-green-700">মোট: <span id="cartTotal">৳0</span></p>
      <a href="{{ route('home.place.order') }}"
         class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
         অর্ডার করুন
      </a>
    </div>

    <!-- 🔘 বন্ধ বাটন -->
    <button id="closeCart"
      class="absolute top-4 right-3 bg-red-100 text-red-700 hover:bg-red-200 px-3 py-1 rounded-lg text-sm font-semibold transition">
      বন্ধ
    </button>
  </div>
</div>






  </section>

 
@endsection
@section('scripts')
@section('scripts')
<script>
// ✅ Add to Cart (DB-based)
document.addEventListener("DOMContentLoaded", function() {

  // Add to cart
  document.querySelectorAll('.addToCartBtn').forEach(btn => {
    btn.addEventListener('click', function() {
      const productId = this.dataset.id;
      const quantity = this.closest('div').querySelector('input[type=number]').value;

      fetch('{{ route("cart.add") }}', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          product_id: productId,
          quantity: quantity
        })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          Swal.fire({
            title: '✅ কার্টে যোগ হয়েছে!',
            text: data.message,
            icon: 'success',
            confirmButtonColor: '#16a34a',
            confirmButtonText: 'ঠিক আছে'
          });
          renderCart();
          updateCartCount();
        } else {
          Swal.fire({
            title: '⚠️ সতর্কতা!',
            text: data.message,
            icon: 'warning',
            confirmButtonColor: '#f59e0b',
          });
        }
      })
      .catch(() => {
        Swal.fire({
          title: '❌ ত্রুটি!',
          text: 'কার্টে যোগ করা যায়নি। পরে চেষ্টা করুন।',
          icon: 'error',
          confirmButtonColor: '#ef4444',
        });
      });
    });
  });

  // Initial Load
  renderCart();
  updateCartCount();
});


// ✅ Render Cart from Database
async function renderCart() {
  const cartContainer = document.getElementById('cartItems');
  const cartTotal = document.getElementById('cartTotal');

  try {
    const response = await fetch('{{ route("cart.fetch") }}');
    const data = await response.json();

    if (!data.items.length) {
      cartContainer.innerHTML = `<p class="text-gray-500 text-center py-3">🛒 আপনার কার্ট খালি</p>`;
      cartTotal.textContent = '৳0';
      return;
    }

    let html = '';
    let total = 0;

    data.items.forEach(item => {
      const subtotal = item.price * item.quantity;
      total += subtotal;

      html += `
        <div class="flex items-center justify-between border p-2 rounded-lg">
          <img src="${item.product?.image_url ?? '/uploads/products/default.png'}"
               class="w-12 h-12 rounded object-cover">
          <div class="flex-1 ml-3">
            <p class="font-semibold text-gray-800">${item.product?.name}</p>
            <p class="text-sm text-green-600">৳${item.price} × ${item.quantity}</p>
          </div>
          <button class="text-red-500 hover:text-red-700 text-sm" onclick="removeFromCart(${item.product_id})">❌</button>
        </div>
      `;
    });

    cartContainer.innerHTML = html;
    cartTotal.textContent = `৳${total.toFixed(2)}`;
  } catch (err) {
    console.error('Error loading cart:', err);
  }
}


// ✅ Remove from Cart (DB)
async function removeFromCart(productId) {
  const res = await fetch(`/cart/remove/${productId}`, {
    method: 'DELETE',
    headers: {
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    }
  });
  const data = await res.json();
  if (data.success) {
    Swal.fire({
      title: '🗑️ মুছে ফেলা হয়েছে!',
      text: data.message,
      icon: 'info',
      confirmButtonColor: '#3b82f6',
    });
    renderCart();
    updateCartCount();
  }
}


// ✅ Update Cart Count (Header)
async function updateCartCount() {
  try {
    const res = await fetch('{{ route("cart.count") }}');
    const data = await res.json();
    document.getElementById('cartCount').textContent = data.count ?? 0;
  } catch (error) {
    console.error('Cart count update failed:', error);
  }
}


// ✅ Modal Controls
const cartButton = document.getElementById("cartButton");
const cartModal = document.getElementById("cartModal");
const closeCart = document.getElementById("closeCart");

cartButton?.addEventListener("click", () => {
  cartModal.classList.remove("opacity-0", "pointer-events-none");
  renderCart();
});

closeCart?.addEventListener("click", () => {
  cartModal.classList.add("opacity-0", "pointer-events-none");
});

window.addEventListener("click", (e) => {
  if (e.target === cartModal) {
    cartModal.classList.add("opacity-0", "pointer-events-none");
  }
});


// ✅ Quantity Increase / Decrease
function increaseQty(btn) {
  const input = btn.previousElementSibling;
  input.value = parseInt(input.value) + 1;
}

function decreaseQty(btn) {
  const input = btn.nextElementSibling;
  if (parseInt(input.value) > 1) {
    input.value = parseInt(input.value) - 1;
  }
}
</script>
@endsection

 

