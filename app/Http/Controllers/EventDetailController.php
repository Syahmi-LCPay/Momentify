<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Photographer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventDetailController extends Controller
{
    public function index(Request $request)
    {
        // Get the search query if provided
        $search = $request->input('search');

        // Filter events if a search query exists
        $events = Event::with('photographers','images')
            ->when($search, function ($query, $search) {
                $query->where('event_name', 'like', '%' . $search . '%')
                        ->orWhere('date', 'like', '%' . $search . '%')
                        ->orWhereHas('photographers', function ($photographerQuery) use ($search) {
                            $photographerQuery->where('name', 'like', '%' . $search . '%');
                        });
            })
            ->when(auth()->user()->is_photographer == 1, function ($query) {
                $query->whereHas('photographers', function ($photographerQuery) {
                    $photographerQuery->where('name', auth()->user()->name);
                });
            })
            ->get();

        return view('eventdetails.index', compact('events', 'search'));
    }

    public function show(Event $event)
    {
        $photographers = Photographer::all();
        return view('eventdetails.show', compact('event', 'photographers'));
    }

    public function assignPhotographer(Request $request, Event $event)
    {
        $request->validate([
            'photographer_id' => 'required|exists:photographers,id',
        ]);

        $event->photographers()->attach($request->photographer_id);

        return redirect()->back()->with('success', 'Photographer assigned successfully!');
    }

    public function removePhotographer($eventId, $photographerId)
    {
        $event = Event::findOrFail($eventId);
        $photographer = Photographer::findOrFail($photographerId);

        // Detach the photographer from the event
        $event->photographers()->detach($photographer);

        return redirect()->route('eventdetails.show', $eventId)
                        ->with('success', 'Photographer removed successfully.');
    }

    public function uploadPhotos(Request $request, $id) {
        $event = Event::find($id);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $filename = $image->getClientOriginalName();
                $path = Storage::disk('public')->put('event', $image);

                // Save the path to the database
                $event->images()->create([
                    'filename' => $filename,
                    'path' => $path,
                ]);
            }
        }

        return redirect()->route('eventdetails.index')->with('success', 'Image Upload successfully.');
    }
}

