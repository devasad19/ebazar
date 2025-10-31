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

<!-- 🔝 Navbar -->
<header class="bg-white shadow-md sticky top-0 z-50">
  <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">

    <!-- 🌿 Logo -->
    <a href="{{ route('front_home') }}" class="flex items-center">
      <h1 class="text-2xl md:text-3xl font-bold text-green-600">
        eBazar<span class="text-gray-800">.com</span>
      </h1>
    </a>



    <!-- 🧭 Main Nav -->
    <nav id="mobileMenu" 
         class="max-h-0 overflow-hidden transition-all duration-500 ease-in-out md:max-h-none md:flex md:items-center md:space-x-6 md:static absolute md:static bg-white w-full md:w-auto left-0 top-[64px] md:top-auto border-b md:border-none shadow md:shadow-none flex-col md:flex-row text-sm font-medium">

      <a href="{{ route('front_home') }}" class="block px-4 py-2 hover:text-green-600 transition">হোম</a>
      <a href="{{ route('our.bazars') }}" class="block px-4 py-2 hover:text-green-600 transition">বাজার</a>
      <a href="{{ route('our.policy') }}" class="block px-4 py-2 hover:text-green-600 transition">আমাদের নীতিমালা</a>
      <a href="#" class="block px-4 py-2 hover:text-green-600 transition">যোগাযোগ</a>

      <!-- 👇 Mobile view: Auth buttons -->
      <div class="flex flex-col gap-2 md:hidden border-t pt-3 mt-2">
        @guest
          <a href="{{ route('login') }}" class="bg-green-600 text-white text-center px-5 py-2 rounded-lg hover:bg-green-700 transition text-sm">লগইন / রেজিস্টার</a>
          <a href="{{ route('rider.register') }}" class="bg-indigo-600 text-white text-center px-5 py-2 rounded-lg hover:bg-indigo-700 transition text-sm">রাইডার নিবন্ধন</a>
        @endguest
      </div>
    </nav>

    <!-- 🎯 Right Section -->
    <div class="flex items-center gap-3">
      @auth
        <!-- Profile Dropdown -->
        <div class="relative" x-data="{ open: false }">
          <button @click="open = !open" class="flex items-center gap-2 bg-gray-100 px-3 py-1 rounded-lg hover:bg-gray-200 transition">
            <img src="{{ auth()->user()->photo ? url('uploads/users/' . auth()->user()->photo) : url('public/default/user.jpg') }}" 
                 class="w-8 h-8 rounded-full object-cover" alt="profile">
            <div class="flex flex-col items-start">
              <span class="text-sm font-medium">{{ auth()->user()->name }}</span>
              <span class="text-xs text-gray-500">{{ auth()->user()->role->name ?? '' }}</span>
            </div>
          </button>

          <!-- Dropdown Menu -->
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
        <a href="{{ route('login') }}" class="hidden md:inline bg-green-600 text-white px-5 py-2 rounded-lg hover:bg-green-700 transition text-sm">
          লগইন / রেজিস্টার
        </a>
        <a href="{{ route('rider.register') }}" class="hidden md:inline bg-indigo-600 text-white px-5 py-2 rounded-lg hover:bg-indigo-700 transition text-sm">
          রাইডার নিবন্ধন
        </a>
      @endauth

      <!-- 🛒 Cart Button (সব ভিউতে visible) -->
      <button id="cartButton" class="relative bg-green-600 text-white px-1 py-1 rounded-lg hover:bg-green-700 transition">
        🛒 ব্যাগ
        <span id="cartCount" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold w-5 h-5 flex items-center justify-center rounded-full">0</span>
      </button>

          <!-- 📱 Mobile Menu Toggle -->
    <button id="menuToggle" class="md:hidden text-2xl text-gray-700 ml-2 focus:outline-none">
      ☰
    </button>
    </div>
  </div>
</header>



  @yield('content')

 

  <!-- 🛍️ Cart Modal -->
<div id="cartModal" class="fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center opacity-0 pointer-events-none transition-opacity duration-300 z-50">
  <div class="bg-white w-96 rounded-2xl shadow-lg p-5 relative">
    <h3 class="text-xl font-bold text-green-700 mb-4">🛒 আপনার বাজার ব্যাগ</h3>

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
const menuToggle = document.getElementById('menuToggle');
const mobileMenu = document.getElementById('mobileMenu');
let isOpen = false;

menuToggle.addEventListener('click', () => {
  isOpen = !isOpen;
  if (isOpen) {
    mobileMenu.classList.remove('max-h-0');
    mobileMenu.classList.add('max-h-[500px]');
  } else {
    mobileMenu.classList.remove('max-h-[500px]');
    mobileMenu.classList.add('max-h-0');
  }
});
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
    document.getElementById('cartCount').textContent = data.count || 0;

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





  @yield('scripts')









  

</body>
</html>
