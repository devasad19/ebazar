<aside class="w-64 bg-white shadow-md hidden md:flex flex-col">
    <div class="p-6">
        <h2 class="text-2xl font-bold text-green-600 mb-6">এডমিন প্যানেল</h2>

        <!-- 👤 User Info -->
        <div class="flex items-center gap-3 mb-6">
            <img src="{{ auth()->user()->photo ? url('uploads/users/' . auth()->user()->photo) : url('public/default/user.jpg') }}" alt="User" class="w-12 h-12 rounded-full object-cover">
            <div>
                <h4 class="font-semibold text-gray-800">{{ Auth::user()->name ?? 'মোঃ আহাদ আলী' }}</h4>
                <p class="text-xs text-gray-500">এডমিন</p>
            </div>
        </div>

        <!-- 🧭 Navigation -->
        <nav class="space-y-2 text-gray-700">
            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center gap-3 px-3 py-2 rounded-lg transition
               {{ request()->routeIs('admin.dashboard') ? 'bg-green-100 font-semibold text-green-700' : 'hover:bg-green-100' }}">
                <i class="fa-solid fa-gauge-high w-5 text-green-600"></i>
                <span>ড্যাশবোর্ড</span>
            </a>

            <a href="{{ route('admin.manage_bazar') }}" 
               class="flex items-center gap-3 px-3 py-2 rounded-lg transition
               {{ request()->routeIs('admin.manage_bazar') ? 'bg-green-100 font-semibold text-green-700' : 'hover:bg-green-100' }}">
                <i class="fa-solid fa-store w-5 text-green-600"></i>
                <span>ম্যানেজ বাজার তালিকা</span>
            </a>

            <a href="{{ route('admin.manage_products') }}" 
               class="flex items-center gap-3 px-3 py-2 rounded-lg transition
               {{ request()->routeIs('admin.manage_products') ? 'bg-green-100 font-semibold text-green-700' : 'hover:bg-green-100' }}">
                <i class="fa-solid fa-boxes-stacked w-5 text-green-600"></i>
                <span>পণ্যের তালিকা</span>
            </a>

            <a href="{{ route('admin.all_orders') }}" 
               class="flex items-center gap-3 px-3 py-2 rounded-lg transition
               {{ request()->routeIs('admin.all_orders') ? 'bg-green-100 font-semibold text-green-700' : 'hover:bg-green-100' }}">
                <i class="fa-solid fa-clipboard-list w-5 text-green-600"></i>
                <span>ম্যানেজ অর্ডারস</span>
            </a>

            <a href="{{ route('admin.rider_list') }}" 
               class="flex items-center gap-3 px-3 py-2 rounded-lg transition
               {{ request()->routeIs('admin.rider_list') ? 'bg-green-100 font-semibold text-green-700' : 'hover:bg-green-100' }}">
                <i class="fa-solid fa-motorcycle w-5 text-green-600"></i>
                <span>রাইডার'স তালিকা</span>
            </a>

            <a href="{{ route('admin.customer_list') }}" 
               class="flex items-center gap-3 px-3 py-2 rounded-lg transition
               {{ request()->routeIs('admin.customer_list') ? 'bg-green-100 font-semibold text-green-700' : 'hover:bg-green-100' }}">
                <i class="fa-solid fa-users w-5 text-green-600"></i>
                <span>কাস্টমার তালিকা</span>
            </a>

            <a href="{{ route('admin.staff_list') }}" 
               class="flex items-center gap-3 px-3 py-2 rounded-lg transition
               {{ request()->routeIs('admin.staff_list') ? 'bg-green-100 font-semibold text-green-700' : 'hover:bg-green-100' }}">
                <i class="fa-solid fa-user-tie w-5 text-green-600"></i>
                <span>স্টাফ তালিকা</span>
            </a>

            <a href="{{ route('admin.settings') }}" 
               class="flex items-center gap-3 px-3 py-2 rounded-lg transition
               {{ request()->routeIs('admin.settings') ? 'bg-green-100 font-semibold text-green-700' : 'hover:bg-green-100' }}">
                <i class="fa-solid fa-gear w-5 text-green-600"></i>
                <span>সেটিংস</span>
            </a>
        <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="flex items-center gap-3 px-3 py-2 w-full  rounded-lg hover:bg-red-100 transition text-red-600">
                  🔓 <span>লগআউট</span>
              </button>
          </form>


        </nav>
    </div>
</aside>
