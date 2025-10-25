@extends('apps.dashboard_master')

@section('content')

<div class="flex min-h-screen bg-gray-50">

    <!-- 🟢 Sidebar -->
    @include('backend.patrials.aside')
    <!-- 🟡 Main Content Area -->
    <div class="flex-1 flex flex-col">

    @include('backend.patrials.top_bar')

        <!-- Content Body --> 
  <section class="bg-gray-50 p-6 rounded-2xl shadow">
    <h2 class="text-2xl font-bold text-green-700 mb-6">⚙️ প্রোফাইল সেটিংস</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Profile Info -->
        <div class="bg-white rounded-2xl shadow p-6">
            <h3 class="text-xl font-semibold text-gray-700 mb-4">👤 প্রোফাইল তথ্য</h3>
            <form action="#" method="POST" class="space-y-4">
                @csrf
                <!-- Name -->
                <div class="flex flex-col">
                    <label for="name" class="text-sm text-gray-600 mb-1">নাম</label>
                    <input type="text" name="name" id="name" value="{{ Auth::user()->name ?? '' }}" 
                        class="w-full border px-3 py-2 rounded-lg focus:ring-2 focus:ring-green-400 outline-none">
                </div>

                <!-- Father's Name -->
                <div class="flex flex-col">
                    <label for="father_name" class="text-sm text-gray-600 mb-1">পিতার নাম</label>
                    <input type="text" name="father_name" id="father_name" value="{{ Auth::user()->father_name ?? '' }}" 
                        class="w-full border px-3 py-2 rounded-lg focus:ring-2 focus:ring-green-400 outline-none">
                </div>

                <!-- Email -->
                <div class="flex flex-col">
                    <label for="email" class="text-sm text-gray-600 mb-1">ইমেইল</label>
                    <input type="email" name="email" id="email" value="{{ Auth::user()->email ?? '' }}" 
                        class="w-full border px-3 py-2 rounded-lg focus:ring-2 focus:ring-green-400 outline-none">
                </div>

                <!-- Phone -->
                <div class="flex flex-col">
                    <label for="phone" class="text-sm text-gray-600 mb-1">মোবাইল নম্বর</label>
                    <input type="tel" name="phone" id="phone" value="{{ Auth::user()->phone ?? '' }}" 
                        class="w-full border px-3 py-2 rounded-lg focus:ring-2 focus:ring-green-400 outline-none">
                </div>

                <button type="submit" class="bg-green-600 text-white w-full py-3 rounded-lg hover:bg-green-700 transition font-semibold">
                    💾 তথ্য সংরক্ষণ করুন
                </button>
            </form>
        </div>

        <!-- Password & Address -->
        <div class="bg-white rounded-2xl shadow p-6 space-y-6">
            <!-- Change Password -->
            <div>
                <h3 class="text-xl font-semibold text-gray-700 mb-4">🔑 পাসওয়ার্ড পরিবর্তন</h3>
                <form action="#" method="POST" class="space-y-4">
                    @csrf
                    <div class="flex flex-col">
                        <label for="current_password" class="text-sm text-gray-600 mb-1">বর্তমান পাসওয়ার্ড</label>
                        <input type="password" name="current_password" id="current_password"
                            class="w-full border px-3 py-2 rounded-lg focus:ring-2 focus:ring-green-400 outline-none">
                    </div>
                    <div class="flex flex-col">
                        <label for="new_password" class="text-sm text-gray-600 mb-1">নতুন পাসওয়ার্ড</label>
                        <input type="password" name="new_password" id="new_password"
                            class="w-full border px-3 py-2 rounded-lg focus:ring-2 focus:ring-green-400 outline-none">
                    </div>
                    <div class="flex flex-col">
                        <label for="confirm_password" class="text-sm text-gray-600 mb-1">নতুন পাসওয়ার্ড নিশ্চিত করুন</label>
                        <input type="password" name="confirm_password" id="confirm_password"
                            class="w-full border px-3 py-2 rounded-lg focus:ring-2 focus:ring-green-400 outline-none">
                    </div>
                    <button type="submit" class="bg-green-600 text-white w-full py-3 rounded-lg hover:bg-green-700 transition font-semibold">
                        🔒 পাসওয়ার্ড পরিবর্তন করুন
                    </button>
                </form>
            </div>

            <!-- Address Update -->
            <div>
                <h3 class="text-xl font-semibold text-gray-700 mb-4">🏠 ঠিকানা পরিবর্তন</h3>
                <form action="#" method="POST" class="space-y-4">
                    @csrf
                    <div class="flex flex-col">
                        <label for="address" class="text-sm text-gray-600 mb-1">ঠিকানা</label>
                        <input type="text" name="address" id="address" value="{{ Auth::user()->address ?? '' }}"
                            class="w-full border px-3 py-2 rounded-lg focus:ring-2 focus:ring-green-400 outline-none">
                    </div>
                    <div class="flex flex-col">
                        <label for="city" class="text-sm text-gray-600 mb-1">শহর / উপজেলা</label>
                        <input type="text" name="city" id="city" value="{{ Auth::user()->city ?? '' }}"
                            class="w-full border px-3 py-2 rounded-lg focus:ring-2 focus:ring-green-400 outline-none">
                    </div>
                    <div class="flex flex-col">
                        <label for="zip" class="text-sm text-gray-600 mb-1">পিন/জিপ কোড</label>
                        <input type="text" name="zip" id="zip" value="{{ Auth::user()->zip ?? '' }}"
                            class="w-full border px-3 py-2 rounded-lg focus:ring-2 focus:ring-green-400 outline-none">
                    </div>
                    <button type="submit" class="bg-green-600 text-white w-full py-3 rounded-lg hover:bg-green-700 transition font-semibold">
                        🏡 ঠিকানা সংরক্ষণ করুন
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

  
    </div>
</div>

@endsection

@section('scripts')
 
@endsection
