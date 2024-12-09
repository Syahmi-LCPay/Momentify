@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-semibold text-center mb-6">Event List</h1>

    <!-- Success Alert -->
    @if (session('success'))
        <div class="bg-green-100 dark:bg-green-800 text-green-700 dark:text-green-200 p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex items-center justify-between mb-4">
        <!-- Search Form -->
        <form action="{{ route('eventconfig.index') }}" method="GET" class="flex items-center">
            <input 
                type="text" 
                name="search" 
                placeholder="Search for events..." 
                value="{{ request('search') }}" 
                class="w-100 p-2 rounded border border-gray-300 dark:border-gray-300"
            />
            <button 
                type="submit" 
                class="ml-2 bg-blue-500 text-white px-3 py-2 rounded hover:bg-blue-600 dark:bg-blue-500 dark:hover:bg-blue-600"
            >
                Search
            </button>
        </form>
    
        <!-- Add Event Button -->
        <a href="{{ route('eventconfig.create') }}" 
           class="bg-blue-500 dark:bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 dark:hover:bg-blue-600">
            + Add New Event
        </a>
    </div>
    

    <!-- Events Table -->
    <div class="overflow-x-auto rounded-lg shadow-lg">
        <table class="min-w-full bg-white dark:bg-gray-800 rounded-lg">
            <thead class="bg-gray-800 dark:bg-gray-700 text-white">
                <tr>
                    <th class="py-4 px-6 text-left text-sm font-medium tracking-wide">Event Name</th>
                    <th class="py-4 px-6 text-left text-sm font-medium tracking-wide">Date</th>
                    <th class="py-4 px-6 text-left text-sm font-medium tracking-wide">Time</th>
                    <th class="py-4 px-6 text-left text-sm font-medium tracking-wide">Place</th>
                    <th class="py-4 px-6 text-center text-sm font-medium tracking-wide">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 dark:text-gray-300">
                @foreach($events as $event)
                    <tr class="border-b dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-150 ease-in-out">
                        <td class="py-4 px-6">{{ $event->event_name }}</td>
                        <td class="py-4 px-6">{{ $event->date }}</td>
                        <td class="py-4 px-6">{{ $event->time }}</td>
                        <td class="py-4 px-6">{{ $event->event_place }}</td>
                        <td class="py-4 px-6 text-center flex justify-center space-x-4">
                            <!-- Edit Button -->
                            <a href="{{ route('eventconfig.edit', $event->id) }}" 
                                class="bg-yellow-500 text-white text-sm px-4 py-2 rounded-lg shadow hover:bg-yellow-600 transition">
                                Edit
                            </a>

                            <!-- Delete Button -->
                            <form action="{{ route('eventconfig.delete', $event->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="bg-red-500 text-white text-sm px-4 py-2 rounded-lg shadow hover:bg-red-600 transition"
                                        onclick="return confirm('Are you sure you want to delete this event?')">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection