@extends('apps.front_master')
@section('content')
 
<!-- 🌿 Hero Section -->
<section class="bg-gradient-to-r from-green-100 via-green-50 to-white py-12">
  <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between px-6">
    <!-- Text -->
    <div class="md:w-1/2 text-center md:text-left mb-8 md:mb-0">
      <h2 class="text-4xl font-extrabold text-green-700 mb-4 leading-tight">
        আপনার স্থানীয় বাজার <br class="hidden md:block"/> এখন ঘরে বসেই 🛒
      </h2>
      <p class="text-gray-700 mb-6 text-lg">
        সবজি, মাছ, ফল, মাংস বা ইলেকট্রনিকস — যা চান, অর্ডার করুন eBazar থেকে। 
        বাজারে না গিয়ে ঘরেই ডেলিভারি পান।
      </p>
      <div class="flex flex-col sm:flex-row gap-3 justify-center md:justify-start">
        <input type="text" placeholder="আপনার বাজারের নাম লিখুন" 
               class="px-4 py-3 border rounded-lg w-72 focus:ring-2 focus:ring-green-400 outline-none">
        <button class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition">
          এখনই খুঁজুন
        </button>
      </div>
    </div>

    <!-- Image -->
    <div class="md:w-1/2 flex justify-center">
      <img src="https://cdn-icons-png.flaticon.com/512/3081/3081559.png" 
           alt="eBazar illustration" 
           class="w-72 md:w-96 drop-shadow-lg">
    </div>
  </div>
</section>

<!-- 🔍 Filter Section -->
<section class="max-w-7xl mx-auto px-6 py-8">
  <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100">
    <h3 class="text-xl font-bold mb-4 text-green-700 flex items-center gap-2">
      🔎 পণ্য ফিল্টার করুন
    </h3>

    <form action="{{ route('products.filter') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
      <select name="category_id" class="border px-3 py-2 rounded-lg w-full focus:ring-2 focus:ring-green-400">
        <option value="">ক্যাটাগরি নির্বাচন</option>
        @foreach($categories as $category)
          <option value="{{ $category->id }}">{{ $category->name }}</option>
        @endforeach
      </select>

      <select name="bazar_id" class="border px-3 py-2 rounded-lg w-full focus:ring-2 focus:ring-green-400">
        <option value="">বাজার নির্বাচন</option>
        @foreach($bazars as $bazar)
          <option value="{{ $bazar->id }}">{{ $bazar->name }}</option>
        @endforeach
      </select>
 
      <input type="text" name="keyword" placeholder="পণ্যের নাম লিখুন"
             class="border px-3 py-2 rounded-lg w-full focus:ring-2 focus:ring-green-400">

      <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
        ফিল্টার প্রয়োগ করুন
      </button>
    </form>
  </div>
</section>

<!-- 🛍️ Product Section -->
<section class="max-w-7xl mx-auto px-6 pb-16">
  <div class="flex justify-between items-center mb-6">
    <h3 class="text-2xl font-bold text-green-700">🛒 পণ্যসমূহের তালিকা</h3>
    <a href="{{ route('products.filter') }}" class="text-sm text-green-600 hover:underline">সব পণ্য দেখুন →</a>
  </div>

  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
    @forelse($products as $product)
      <div class="bg-white rounded-xl shadow hover:shadow-lg transition p-4 relative group">
        <img src="{{ url('uploads/products/'.$product->image) }}" 
             alt="{{ $product->name }}" 
             class="rounded-lg w-full h-44 object-cover group-hover:scale-105 transition duration-300">
        <button class="absolute top-3 right-3 bg-white p-2 rounded-full shadow hover:text-red-500">
          ❤️
        </button>
        <div class="mt-4">
          <h4 class="font-bold text-lg">{{ $product->name }}</h4>
          <p class="text-green-600 font-semibold">৳{{ $product->price }} / {{ $product->unit }}</p>
          <!-- <p class="text-sm text-gray-500">
            বিতরণকারী: 
            {{ optional($product->rider)->name ?? 'N/A' }}
          </p> -->
          <p class="text-sm text-gray-500 mb-3">
            বাজার: 
            {{ optional($product->bazar)->name ?? 'N/A' }}
          </p>
          <div class="flex justify-between items-center">
            <a href="#" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-3 rounded-lg text-sm">🛒 অর্ডার করুন</a>
            <a href="{{ route('home.product.details', $product->id) }}"  class="inline-block bg-green-600 text-white py-2 px-3 rounded-lg hover:bg-green-700 transition text-center">
              বিস্তারিত </a>
        </div>
        </div>
      </div>
    @empty
      <div class="col-span-4 text-center text-gray-500 py-10">
        🚫 কোনো পণ্য পাওয়া যায়নি।
      </div>
    @endforelse
  </div>
</section>

<!-- 🚴‍♂️ Rider Section -->
<section class="max-w-7xl mx-auto px-6 pb-16">
  <div class="flex justify-between items-center mb-6">
    <h3 class="text-2xl font-bold text-green-700">🚴‍♂️ আমাদের বিশ্বস্ত রাইডারগণ</h3>
    <a href="#" class="text-sm text-green-600 hover:underline">সব রাইডার দেখুন →</a>
  </div>

  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">

    @foreach($riders as $rider)
    <div class="bg-white rounded-2xl shadow hover:shadow-lg transition overflow-hidden group">
      
      <!-- Profile Image -->
      <div class="relative">
        <img src="{{ $rider->photo ? asset('uploads/riders/'.$rider->photo) : 'https://cdn-icons-png.flaticon.com/512/4140/4140048.png' }}" 
             alt="{{ $rider->name }}" 
             class="h-48 w-full object-cover group-hover:scale-105 transition duration-300">
        <span class="absolute top-3 right-3 bg-green-600 text-white text-xs px-3 py-1 rounded-full">
          {{ ucfirst($rider->status) }}
        </span>
      </div>

      <!-- Info -->
      <div class="p-5 text-center">
        <h4 class="text-lg font-bold text-gray-800">{{ $rider->name }}</h4>
        <p class="text-sm text-gray-500 mb-1">📞 {{ $rider->phone }}</p>
        <p class="text-sm text-gray-500 mb-2">{{ $rider->vehicle_type ? '🚲 '.$rider->vehicle_type : '' }}</p>

        <div class="grid grid-cols-2 gap-2 text-sm mt-3">
          <div class="bg-green-50 p-2 rounded-lg">
            <p class="font-semibold text-green-700">Delivered</p>
            <p class="font-bold text-gray-800">{{ $rider->total_delivered }}</p>
          </div>
          <div class="bg-green-50 p-2 rounded-lg">
            <p class="font-semibold text-green-700">On Time</p>
            <p class="font-bold text-gray-800">
              @php
                $percent = $rider->total_delivered > 0 ? round(($rider->on_time_delivery / $rider->total_delivered) * 100) : 0;
              @endphp
              {{ $percent }}%
            </p>
          </div>
        </div>

        <div class="grid grid-cols-2 gap-2 text-sm mt-3">
          <div class="bg-yellow-50 p-2 rounded-lg">
            <p class="font-semibold text-yellow-700">Pending</p>
            <p class="font-bold text-gray-800">{{ $rider->pending_orders }}</p>
          </div>
          <div class="bg-red-50 p-2 rounded-lg">
            <p class="font-semibold text-red-700">Cancelled</p>
            <p class="font-bold text-gray-800">{{ $rider->cancel_delivery }}</p>
          </div>
        </div>

        <a href="{{ route('riders.show', $rider->id) }}" 
           class="mt-4 inline-block bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg text-sm font-semibold transition">
          বিস্তারিত দেখুন
        </a>
      </div>
    </div>
    @endforeach

  </div>
</section>




@endsection
