<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCalendarEventRequest;
use App\Models\CalendarEvent;

class CalendarEventController extends Controller
{
    public function store(StoreCalendarEventRequest $request)
    {
        CalendarEvent::create($request->validated());

        return redirect()->back()->with('success', 'Event created successfully.');
    }
}
