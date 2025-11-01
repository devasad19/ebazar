<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>eBazar.com - আপনার বাজার এখন অনলাইনে</title>
      <meta name="csrf-token" content="{{ csrf_token() }}">
 
  @yield('styles')

  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">

<!-- 🔝 Desktop Navbar -->
<!-- 🔝 Desktop Navbar -->
 

<!-- 📱 Mobile App-Style Bottom Navbar -->
<!-- 🔝 Desktop Navbar -->
<header class="bg-white shadow-md sticky top-0 z-50 hidden md:block">
  <div class="max-w-6xl mx-auto px-4 py-3 flex justify-between items-center">

    <!-- 🌿 Logo -->
    <a href="{{ route('front_home') }}" class="flex items-center space-x-2">
      <img src="{{ url('public/default/new-logo.png') }}" alt="ই-বাজার" class="h-16 w-auto">
    </a>

    <!-- 🧭 Main Navigation -->
    <nav class="flex items-center space-x-6 text-sm font-medium">
      @php
          $routes = [
              ['name' => 'হোম', 'route' => route('front_home')],
              ['name' => 'বাজার', 'route' => route('our.bazars')],
              ['name' => 'আমাদের নীতিমালা', 'route' => route('our.policy')],
              ['name' => 'যোগাযোগ', 'route' => route('contact_us')],
          ];
          $current = url()->current();
      @endphp

      @foreach($routes as $item)
        <a href="{{ $item['route'] }}"
           class="relative px-3 py-2 text-gray-700 hover:text-green-700 transition font-medium group {{ $current == $item['route'] ? 'text-green-700' : '' }}">
          {{ $item['name'] }}
          <span class="absolute left-0 bottom-0 w-0 h-[2px] bg-green-600 transition-all duration-300 group-hover:w-full {{ $current == $item['route'] ? 'w-full' : '' }}"></span>
        </a>
      @endforeach
    </nav>

    <!-- 🎯 Right Section -->
    <div class="flex items-center gap-3">
      @auth
        <div class="relative" x-data="{ open: false }">
          <button @click="open = !open" class="flex items-center gap-2 bg-gray-100 px-3 py-1 rounded-lg hover:bg-gray-200 transition">
            <img src="{{ auth()->user()->photo ? url('uploads/users/' . auth()->user()->photo) : url('public/default/user.jpg') }}"
                 class="w-8 h-8 rounded-full object-cover" alt="profile">
            <div class="flex flex-col items-start">
              <span class="text-sm font-medium">{{ auth()->user()->name }}</span>
              <span class="text-xs text-gray-500">{{ auth()->user()->role->name ?? '' }}</span>
            </div>
          </button>

          <div x-show="open" @click.outside="open = false"
               class="absolute right-0 mt-2 w-48 bg-white border rounded-lg shadow-lg py-2 z-50">
            <a href="{{ auth()->user()->role->name }}/dashboard" class="block px-4 py-2 text-sm hover:bg-gray-100">ড্যাশবোর্ড</a>
            @if (auth()->user()->role->name == 'user')
              <a href="user/dashboard/my-orders" class="block px-4 py-2 text-sm hover:bg-gray-100">আমার অর্ডার</a>
            @endif
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100">
                লগআউট
              </button>
            </form>
          </div>
        </div>
      @else
        <a href="{{ route('login') }}" class="bg-green-600 text-white px-5 py-2 rounded-lg hover:bg-green-700 transition text-sm">
          লগইন / রেজিস্টার
        </a>
        <a href="{{ route('rider.register') }}" class="bg-indigo-600 text-white px-5 py-2 rounded-lg hover:bg-indigo-700 transition text-sm">
          রাইডার নিবন্ধন
        </a>
      @endauth

      <!-- 🛒 Cart -->
      <button id="cartButtonHeader" class="relative bg-green-600 text-white px-3 py-2 rounded-lg hover:bg-green-700 transition text-sm">
        🛒 ব্যাগ
        <span id="cartCountHeader" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold w-5 h-5 flex items-center justify-center rounded-full">0</span>
      </button>
    </div>
  </div>
</header>

<!-- 📱 Mobile App-Style Bottom Navbar -->
<!-- 🌈 Modern Colorful Mobile Bottom Navbar -->
<nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 shadow-xl z-50 md:hidden">
  <div class="flex justify-around items-center py-2">

    <!-- 🏠 হোম -->
    <a href="{{ route('front_home') }}" 
       class="flex flex-col items-center group transition-all duration-300">
      <div class="w-9 h-9 flex items-center justify-center rounded-full 
                  {{ request()->routeIs('front_home') ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-600' }}
                  group-hover:bg-green-100 group-hover:text-green-600">
        🏡
      </div>
      <span class="text-xs mt-1 font-medium 
                   {{ request()->routeIs('front_home') ? 'text-green-600' : 'text-gray-700' }}">
        হোম
      </span>
    </a>

    <!-- 🛍️ বাজার -->
    <a href="{{ route('our.bazars') }}" 
       class="flex flex-col items-center group transition-all duration-300">
      <div class="w-9 h-9 flex items-center justify-center rounded-full 
                  {{ request()->routeIs('our.bazars') ? 'bg-pink-100 text-pink-600' : 'bg-gray-100 text-gray-600' }}
                  group-hover:bg-pink-100 group-hover:text-pink-600">
        🛍️
      </div>
      <span class="text-xs mt-1 font-medium 
                   {{ request()->routeIs('our.bazars') ? 'text-pink-600' : 'text-gray-700' }}">
        বাজার
      </span>
    </a>

    <!-- 🎒 ব্যাগ (Middle, Bigger Icon) -->
<!-- 🎒 ব্যাগ (Middle, Bigger Icon with SVG Bag) -->
<button id="cartButtonBottom" 
        class="relative -mt-8 flex flex-col items-center group transition-all duration-300">
  <div class="w-16 h-16 flex items-center justify-center rounded-full bg-white text-white border-4 border-green-200 shadow-lg group-hover:bg-green-200">
    <!-- 🛍️ SVG Bag Icon -->
            <img class="w-16 h-16 p-3 items-center group transition-all" src="{{ url('public/default/bag.svg') }}" alt="">
  </div>
  <span class="text-xs mt-1 text-green-600 font-semibold">ব্যাগ</span>
  <span id="cartCountBottom" 
        class="absolute top-0 right-4 bg-red-500 text-white text-[13px] font-bold w-5 h-5 flex items-center justify-center rounded-full">3</span>
</button>



    <!-- 📜 নীতিমালা -->
    <a href="{{ route('our.policy') }}" 
       class="flex flex-col items-center group transition-all duration-300">
      <div class="w-9 h-9 flex items-center justify-center rounded-full 
                  {{ request()->routeIs('our.policy') ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-600' }}
                  group-hover:bg-blue-100 group-hover:text-blue-600">
        📜
      </div>
      <span class="text-xs mt-1 font-medium 
                   {{ request()->routeIs('our.policy') ? 'text-blue-600' : 'text-gray-700' }}">
        নীতিমালা
      </span>
    </a>

    <!-- ☎️ যোগাযোগ -->
    <a href="{{ route('contact_us') }}" 
       class="flex flex-col items-center group transition-all duration-300">
      <div class="w-9 h-9 flex items-center justify-center rounded-full 
                  {{ request()->routeIs('contact_us') ? 'bg-yellow-100 text-yellow-600' : 'bg-gray-100 text-gray-600' }}
                  group-hover:bg-yellow-100 group-hover:text-yellow-600">
        ☎️
      </div>
      <span class="text-xs mt-1 font-medium 
                   {{ request()->routeIs('contact_us') ? 'text-yellow-600' : 'text-gray-700' }}">
        যোগাযোগ
      </span>
    </a>

  </div>
</nav>







  @yield('content')

 

  <!-- 🛍️ Cart Modal -->
<!-- 🛍️ Mobile App Style Cart Modal -->
<div id="cartModal" 
     class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-end md:items-center opacity-0 pointer-events-none transition-opacity duration-300 z-50">
  
  <div class="bg-white w-full md:w-96 h-3/4 md:h-auto rounded-t-2xl md:rounded-2xl shadow-xl p-5 relative flex flex-col">
    
    <!-- Header -->
    <div class="flex justify-between items-center mb-4 border-b pb-2">
      <h3 class="text-lg md:text-xl font-bold text-green-700">🛒 আপনার বাজার ব্যাগ</h3>
      <button id="closeCart" 
              class="bg-red-100 text-red-700 hover:bg-red-200 px-3 py-1 rounded-lg text-sm font-semibold transition">
        ✕
      </button>
    </div>

    <!-- Cart Items -->
    <div id="cartItems" class="flex-1 overflow-y-auto space-y-3 pb-3">
      <!-- JS দিয়ে কার্ট আইটেম এখানে আসবে -->
      <!-- প্রতিটি আইটেম কার্ডের মতো দেখানো হবে -->
      <!-- উদাহরণ: -->
      <!--
      <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl shadow-sm">
        <img src="..." alt="পণ্য" class="w-16 h-16 rounded-lg object-cover">
        <div class="flex-1 mx-3">
          <p class="font-semibold text-gray-800 text-sm">পণ্যের নাম</p>
          <p class="text-green-600 font-bold text-sm">৳200 x 2</p>
        </div>
        <p class="font-semibold text-gray-700">৳400</p>
      </div>
      -->
    </div>

    <!-- Footer / Total & Checkout -->
    <div class="border-t pt-3 mt-3 flex flex-col md:flex-row justify-between items-center gap-3">
      <p class="font-bold text-green-700 text-lg">মোট: <span id="cartTotal">৳0</span></p>
      <a href="{{ route('home.place.order') }}" 
         class="w-full md:w-auto bg-green-600 text-white text-center px-5 py-3 rounded-lg hover:bg-green-700 transition font-semibold">
        অর্ডার করুন
      </a>
    </div>
  </div>
</div>




  <!-- 🌱 Footer -->
  <footer class="bg-gray-900 text-gray-300 text-center py-6">
    <p>© ২০২৫ eBazar.com | আপনার বাজার, আপনার ঘরে 🏡</p>
    <p>
      <a href="{{ route('terms-and-conditions') }}" class="text-sm text-green-400 hover:text-green-300">টার্মস অ্যান্ড কন্ডিশন</a> ||
      <a href="{{ route('privacy-policy') }}" class="text-sm text-green-400 hover:text-green-300">প্রাইভেসি পলিসি</a>
    </p>
  </footer>
 




  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<!-- ✅ SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<!-- 📜 JS for Mobile Menu (Smooth Animation) -->
<script>
// const menuToggle = document.getElementById('menuToggle');
// const mobileMenu = document.getElementById('mobileMenu');
// let isOpen = false;

// menuToggle.addEventListener('click', () => {
//   isOpen = !isOpen;
//   if (isOpen) {
//     mobileMenu.classList.remove('max-h-0');
//     mobileMenu.classList.add('max-h-[500px]');
//   } else {
//     mobileMenu.classList.remove('max-h-[500px]');
//     mobileMenu.classList.add('max-h-0');
//   }
// });
</script>




<script>

    // 🔹 Common Swal Toast helper
    function showToast(icon, title, message) {
        Swal.fire({
            icon: icon,
            title: title,
            text: message,
            timer: 2000,
            showConfirmButton: false,
            position: 'top-end',
            toast: true
        });
      }



  function swalConfirm(message, callback) {
      Swal.fire({
          title: 'আপনি কি নিশ্চিত?',
          text: message,
          icon: 'question',
          showCancelButton: true,
          confirmButtonText: 'হ্যাঁ',
          cancelButtonText: 'না',
          confirmButtonColor: '#16a34a',
          cancelButtonColor: '#d33'
      }).then((result) => {
          if (result.isConfirmed && typeof callback === "function") {
              callback();
          }
      });
  }


    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        }
    });
</script>


<script>
 



// ✅ Add to Cart (DB-based)
document.addEventListener("DOMContentLoaded", function() {

  // Add to cart
  document.querySelectorAll('.addToCartBtn').forEach(btn => {
  btn.addEventListener('click', async function () {
    const productId = this.dataset.id;
    const input = this.closest('div')?.querySelector('input[type=number]');
    const quantity = input ? parseFloat(input.value) || 1 : 1;


    try {
      // 🧠 Step 1: Send Add-to-Cart request
      const res = await fetch('{{ route("cart.add") }}', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ product_id: productId, quantity })
      });

      const data = await res.json();

      // 🧩 Step 2: Handle special "confirm_clear" response
      if (data.status === 'confirm_clear') {
        const userConfirmed = await Swal.fire({
          title: '⚠️ ব্যাগে ভিন্ন বাজারের পণ্য রয়েছে!',
          html: data.message,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'হ্যাঁ, মুছে নতুনভাবে যোগ করুন',
          cancelButtonText: 'না, বাতিল',
          confirmButtonColor: '#16a34a',
          cancelButtonColor: '#d33',
        });

        if (userConfirmed.isConfirmed) {
            const clearUrl = '{{ route("bazarid.clear.add") }}';

            const clearRes = await fetch(clearUrl, {
              method: 'POST',
              headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
              },
              body: JSON.stringify({ product_id: productId, quantity })
            });


          const response = await clearRes.json();

          if (response.success) {
            showAlert('✅ ব্যাগ আপডেট হয়েছে!', response.message, 'success');
            refreshCartUI();
          } else {
            showAlert('⚠️ ত্রুটি!', response.message || 'ব্যাগ আপডেট করা যায়নি।', 'success');
          }
        }

        return; // stop further execution
      }

      // ✅ Step 3: Normal success
      if (data.success) {
        showAlert('✅ ব্যাগে যোগ হয়েছে!', data.message, 'success');
        refreshCartUI();
      } else {
        showAlert('⚠️ সতর্কতা!', data.message, 'warning');
      }

    } catch (error) {
      console.error("Cart Add Error:", error);
      showAlert('❌ ত্রুটি!', 'ব্যাগে যোগ করা যায়নি। পরে চেষ্টা করুন।', 'error');
    }
  });
});


    updateCartCount();

});

// ✅ Helper functions
function showAlert(title, text, icon) {
  Swal.fire({
    title,
    text,
    icon,
    confirmButtonColor: icon === 'success' ? '#16a34a' : '#ef4444',
    confirmButtonText: 'ঠিক আছে',
  });
}

function refreshCartUI() {
  if (typeof renderCart === 'function') renderCart();
  if (typeof updateCartCount === 'function') updateCartCount();
}
 
async function renderCart() {
  const cartContainer = document.getElementById('cartItems');
  const cartTotal = document.getElementById('cartTotal');
  const baseUrl = "{{ url('uploads/products') }}";

  try {
    const response = await fetch('{{ route("cart.fetch") }}');
    const data = await response.json();
    updateCartCount();

    // যদি session খালি বা DB তে কিছু না থাকে
    if (!data.items || (Array.isArray(data.items) && !data.items.length) || Object.keys(data.items).length === 0) {
      cartContainer.innerHTML = `<p class="text-gray-500 text-center py-3">🛒 আপনার ব্যাগ খালি</p>`;
      cartTotal.textContent = '৳0';
      return;
    }

    let html = '';
    let total = 0;
console.log(data.items);

    // ✅ Logged-in User (DB data)
    if (data.source === 'database') {
      data.items.forEach(item => {
        const subtotal = item.price * item.quantity;
        total += subtotal;
        html += `
          <div class="flex items-center justify-between border p-2 rounded-lg">
            <img src="${baseUrl}/${item.product?.image || 'default.png'}" class="w-12 h-12 rounded object-cover">
            <div class="flex-1 ml-3">
              <p class="font-semibold text-gray-800">${item.product?.name || 'Unnamed Product'}</p>
              <p class="text-sm text-green-600">৳${item.price} × ${item.quantity}</p>
            </div>
            <button class="text-red-500 hover:text-red-700 text-sm" onclick="removeFromCart(${item.product_id})">❌</button>
          </div>
        `;
      });
    }

    // ✅ Guest User (Session data)
    else if (data.source === 'session') {
      Object.entries(data.items).forEach(([productId, item]) => {
        const subtotal = item.price * item.quantity;
        total += subtotal;
        html += `
          <div class="flex items-center justify-between border p-2 rounded-lg">
            <img src="${baseUrl}/${item.image || 'default.png'}" class="w-12 h-12 rounded object-cover">
            <div class="flex-1 ml-3">
              <p class="font-semibold text-gray-800">${item.name}</p>
              <p class="text-sm text-green-600">৳${item.price} × ${item.quantity}</p>
            </div>
            <button class="text-red-500 hover:text-red-700 text-sm" onclick="removeSessionItemFromCart(${item.product_id})">❌</button>
          </div>
        `;
      });
    }

    cartContainer.innerHTML = html;
    cartTotal.textContent = `৳${total.toFixed(2)}`;
  } catch (err) {
    console.error('Error loading cart:', err);
  }
}
 


async function removeSessionItemFromCart(id) {
 
  
  try {
    const url = `{{ route('cart.remove', ':id') }}`.replace(':id', id); // ✅ dynamic URL

    const response = await fetch(url, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      }
    });

    const data = await response.json();

    if (data.success) {
      
      renderCart(); // 🔁 কার্ট রিফ্রেশ
    } else {
      alert('❌ কিছু সমস্যা হয়েছে!');
    }

  } catch (err) {
    console.error('Remove error:', err);
  }
}
 







// ✅ Remove from Cart (DB)
async function removeFromCart(productId) {


    const url = `{{ route('cart.remove', ':id') }}`.replace(':id', productId); // ✅ dynamic URL


    const response = await fetch(url, {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    }
  });
  const data = await response.json();
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
    // document.getElementById('cartCount').textContent = data.count ?? 0;

  document.querySelectorAll('#cartCountHeader, #cartCountBottom').forEach(el => {
    el.textContent = data.count ?? 0;
  });



  } catch (error) {
    console.error('Cart count update failed:', error);
  }
}


// ✅ Modal Controls
// ✅ Cart Modal Controls
const cartButtons = [
  document.getElementById("cartButtonHeader"),
  document.getElementById("cartButtonBottom")
];
const cartModal = document.getElementById("cartModal");
const closeCart = document.getElementById("closeCart");

cartButtons.forEach((btn) => {
  btn?.addEventListener("click", () => {
    cartModal.classList.remove("opacity-0", "pointer-events-none");
    renderCart(); // 🧩 আপনার render function
  });
});

closeCart?.addEventListener("click", () => {
  cartModal.classList.add("opacity-0", "pointer-events-none");
});

window.addEventListener("click", (e) => {
  if (e.target === cartModal) {
    cartModal.classList.add("opacity-0", "pointer-events-none");
  }
});

 

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





  @yield('scripts')









  

</body>
</html>
