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



  @yield('scripts')

</body>
</html>
