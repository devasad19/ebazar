@extends('apps.front_master')

@section('content')
<div class="bg-gray-50 min-h-screen py-10">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-3xl font-bold text-green-700 mb-8 text-center">🏪 আমাদের বাজার তালিকা</h2>

        <div class="space-y-6">
            @foreach($bazars as $bazar)
            <div class="bg-white p-6 rounded-2xl shadow hover:shadow-xl transition flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex-1">
                    <h3 class="text-2xl font-semibold text-gray-800 mb-2">{{ $bazar->name }}</h3>
                    <p class="text-gray-600">রাইডার সংখ্যা: <strong>{{ count($bazar->riders) }}</strong></p>
                    <p class="text-gray-600">পণ্যের সংখ্যা: <strong>{{ count($bazar->products) }}</strong></p>
                    <p class="text-gray-600 mt-2">কভার করা এলাকা/গ্রাম:</p>
                    <div class="flex flex-wrap mt-1 gap-2">
                        @foreach($bazar->areas?? ['fdsla', 'lfdsl'] as $area)
                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">{{ $area }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
      

    </div>
</div>
@endsection