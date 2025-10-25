@extends('apps.dashboard_master')

@section('content')
<div class="flex min-h-screen bg-gray-50">
    <!-- Sidebar -->
    @include('backend.patrials.admin_aside')

    <!-- Main Content -->
    <div class="flex-1 flex flex-col p-6">
        @include('backend.patrials.top_bar')

        <!-- Page Title -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-green-700">👔 Staff List</h2>
        </div>

        <!-- Filter -->
        <div class="flex justify-end mb-4">
            <select class="border px-3 py-2 rounded-lg focus:ring-2 focus:ring-green-400 outline-none">
                <option value="today">Today</option>
                <option value="yesterday">Yesterday</option>
                <option value="7_days">Last 7 Days</option>
                <option value="15_days">Last 15 Days</option>
                <option value="1_month">1 Month</option>
            </select>
        </div>

        <!-- Staff Table -->
        <div class="bg-white rounded-2xl shadow p-4 overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-green-50 text-gray-700">
                        <th class="py-2 px-4 border">#</th>
                        <th class="py-2 px-4 border">Name</th>
                        <th class="py-2 px-4 border">Email</th>
                        <th class="py-2 px-4 border">Phone</th>
                        <th class="py-2 px-4 border">Role</th>
                        <th class="py-2 px-4 border">Status</th>
                        <th class="py-2 px-4 border">Options</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($staffs as $index => $staff)
                    <tr class="border-b hover:bg-green-50">
                        <td class="py-2 px-4 border">{{ $index + 1 }}</td>
                        <td class="py-2 px-4 border">{{ $staff->name }}</td>
                        <td class="py-2 px-4 border">{{ $staff->email }}</td>
                        <td class="py-2 px-4 border">{{ $staff->phone }}</td>
                        <td class="py-2 px-4 border">{{ ucfirst($staff->role) }}</td>
                        <td class="py-2 px-4 border">
                            @if($staff->status == 'active')
                                <span class="text-green-600 font-semibold">Active</span>
                            @else
                                <span class="text-red-600 font-semibold">Inactive</span>
                            @endif
                        </td>
                        <td class="py-2 px-4 border flex gap-2">
                            <button class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600 transition" 
                                    onclick="alert('View Staff Details')">View</button>
                            <button class="bg-yellow-400 text-white px-2 py-1 rounded hover:bg-yellow-500 transition" 
                                    onclick="alert('Edit Staff')">Edit</button>
                            <button class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 transition" 
                                    onclick="alert('Delete Staff')">Delete</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
