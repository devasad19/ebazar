        <!-- Top Bar -->
        <header class="bg-white shadow-md py-4 px-6 flex justify-between items-center">
            <h1 class="text-xl font-bold text-gray-700">ড্যাশবোর্ড</h1>
            <div class="flex items-center gap-4">
                <span class="text-gray-600 text-sm hidden sm:block">{{ Auth::user()->phone ?? '০১৭১০২১৫৪৭' }}</span>
                <img src="https://via.placeholder.com/40" alt="User" class="w-10 h-10 rounded-full object-cover">
            </div>
        </header>