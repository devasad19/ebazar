<aside class="w-64 bg-white shadow-md hidden md:flex flex-col">
    <div class="p-6">
        <h2 class="text-2xl font-bold text-green-600 mb-6">User Panel</h2>
        <div class="flex items-center gap-3 mb-6">
            <img src="https://via.placeholder.com/50" alt="User" class="w-12 h-12 rounded-full object-cover">
            <div>
                <h4 class="font-semibold text-gray-800">{{ Auth::user()->name ?? 'মোঃ আহাদ আলী' }}</h4>
                <p class="text-xs text-gray-500">Member</p>
            </div>
        </div>

        <nav class="space-y-2">
            <a href="{{ route('rider.dashboard') }}" 
               class="flex items-center gap-3 px-3 py-2 rounded-lg transition
               {{ request()->routeIs('rider.dashboard') ? 'bg-green-100 font-semibold' : 'hover:bg-green-100' }}">
                🏠 <span>Dashboard</span>
            </a>

            <a href="{{ route('rider.products') }}" 
               class="flex items-center gap-3 px-3 py-2 rounded-lg transition
               {{ request()->routeIs('rider.products') ? 'bg-green-100 font-semibold' : 'hover:bg-green-100' }}">
                📦 <span>My Product List</span>
            </a>

            <a href="{{ route('rider.orders') }}" 
               class="flex items-center gap-3 px-3 py-2 rounded-lg transition
               {{ request()->routeIs('rider.orders') ? 'bg-green-100 font-semibold' : 'hover:bg-green-100' }}">
                🛒 <span>My Orders</span>
            </a>


            <a href="{{ route('rider.settings') }}" 
               class="flex items-center gap-3 px-3 py-2 rounded-lg transition
               {{ request()->routeIs('rider.settings') ? 'bg-green-100 font-semibold' : 'hover:bg-green-100' }}">
                ⚙️ <span>Settings</span>
            </a>

            <a href="" 
               class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-red-100 transition text-red-600">
                🔓 <span>Logout</span>
            </a>
        </nav>
    </div>
</aside>
