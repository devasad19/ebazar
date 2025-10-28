<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>eBazar - Dashboard</title>
      <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex flex-col min-h-screen bg-gray-50 text-gray-800">

  <!-- 🔝 Topbar -->
  <header class="bg-white shadow-md sticky top-0 z-50">
    <div class="max-w-7xl mx-auto flex justify-between items-center px-4 py-3">
      <h1 class="text-2xl font-bold text-green-600">eBazar Dashboard</h1>
      <nav class="flex items-center gap-4">
        <span class="text-gray-700">স্বাগত, মোঃ আহাদ</span>
        
        <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                  লগআউট
              </button>
          </form>
      </nav>
    </div>
  </header>

  <div class="flex flex-1">
    <!-- 🟩 Sidebar -->
  
    <!-- 🟦 Main Content -->
    <main class="flex-1 p-6">
      @yield('content')
    </main>
  </div>
 

  <!-- 🌱 Footer -->
  <footer class="bg-gray-900 text-gray-300 text-center py-4 mt-auto">
    <p>© ২০২৫ eBazar.com | আপনার বাজার, আপনার ঘরে 🏡</p>
  </footer>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
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
