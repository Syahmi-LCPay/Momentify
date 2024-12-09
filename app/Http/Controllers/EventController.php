<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $events = Event::all();

        $query = $request->input('search');
        $events = Event::when($query, function ($queryBuilder) use ($query) {
            return $queryBuilder->where('event_name', 'like', '%' . $query . '%')
                                ->orWhere('event_place', 'like', '%' . $query . '%');
        })->get();

        return view('events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('events.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_name' => 'required|string|max:255',
            'date' => 'required|date',
            'time' => 'required',
            'event_place' => 'required|string|max:255',
        ]);

        $event = Event::create($validated);

        return redirect()->route('eventconfig.index')->with('success', 'Event created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        return view('events.edit', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'event_name' => 'required|string|max:255',
            'date' => 'required|date',
            'time' => 'required',
            'event_place' => 'required|string|max:255',
        ]);
    
        $event->update($validated);
    
        return redirect()->route('eventconfig.index')->with('success', 'Event updated successfully!');
    }    

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Event $event)
    {
        $event->delete();
    
        return redirect()->route('eventconfig.index')->with('success', 'Event deleted successfully!');
    }    
}
