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
    <div class="max-w-7xl mx-auto flex justify-between items-center px-4 py-3">

        {{-- Logo --}}
        <h1 class="text-3xl font-bold text-green-600">
            eBazar<span class="text-gray-800">.com</span>
        </h1>

        {{-- Navbar Links --}}
        <nav class="hidden md:flex space-x-6 text-sm font-medium">
            <a href="{{ route('front_home') }}" class="hover:text-green-600 transition">হোম</a>
            <a href="" class="hover:text-green-600 transition">বাজার</a>
            <a href="" class="hover:text-green-600 transition">দোকানদার</a>
            <a href="" class="hover:text-green-600 transition">যোগাযোগ</a>
        </nav>

        {{-- Right Section --}}
        <div class="flex items-center gap-3">

            {{-- Authenticated --}}
            @auth
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center gap-2 bg-gray-100 px-3 py-1 rounded-lg hover:bg-gray-200 transition">
                        <img src="{{ auth()->user()->photo ? asset('storage/' . auth()->user()->photo) : asset('default-user.png') }}" 
                             class="w-8 h-8 rounded-full object-cover" alt="profile">
                        <span class="text-sm font-medium">{{ auth()->user()->name }}</span>
                        <span class="text-xs text-gray-500">{{ auth()->user()->role->name ?? 'User' }}</span>
                    </button>

                    {{-- Dropdown --}}
                    <div x-show="open" @click.outside="open = false" 
                         class="absolute right-0 mt-2 w-48 bg-white border rounded-lg shadow-lg py-2 z-50">
                        <a href="" class="block px-4 py-2 text-sm hover:bg-gray-100">প্রোফাইল</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100">
                                লগআউট
                            </button>
                        </form>
                    </div>
                </div>
            @else
                {{-- Guest --}}
                <a href="{{ route('login') }}" class="bg-green-600 text-white px-5 py-2 rounded-lg hover:bg-green-700 transition text-sm">
                    লগইন / রেজিস্টার
                </a>
                <a href="{{ route('rider.register') }}" class="bg-indigo-600 text-white px-5 py-2 rounded-lg hover:bg-indigo-700 transition text-sm">
                    রাইডার নিবন্ধন
                </a>
            @endauth

            {{-- Cart --}}
            <button id="cartButton" class="relative bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                🛒 কার্ট
                <span id="cartCount" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold w-5 h-5 flex items-center justify-center rounded-full">0</span>
            </button>
        </div>
    </div>
</header>


  @yield('content')





  <!-- 🌱 Footer -->
  <footer class="bg-gray-900 text-gray-300 text-center py-6">
    <p>© ২০২৫ eBazar.com | আপনার বাজার, আপনার ঘরে 🏡</p>
  </footer>





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


  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<!-- ✅ SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
 
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
          console.log(data.success);
          
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




     renderCart();




  // Initial Load

  updateCartCount();
});


 
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
      cartContainer.innerHTML = `<p class="text-gray-500 text-center py-3">🛒 আপনার কার্ট খালি</p>`;
      cartTotal.textContent = '৳0';
      return;
    }

    let html = '';
    let total = 0;

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
            <button class="text-red-500 hover:text-red-700 text-sm" onclick="removeSessionItemFromCart(${productId})">❌</button>
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
  if (!confirm('আপনি কি নিশ্চিত এই পণ্যটি মুছে ফেলতে চান?')) return;

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
