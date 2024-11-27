<?php

namespace App\Http\Controllers;


class NotificationController extends Controller
{
    // Fetches the latest 10 notifications for the authenticated user
    public function index()
    {
        $user = auth()->user();
        return response()->json([
            'notifications' => $user->notifications()->latest()->take(10)->get()
        ]);
    }

    // Marks a specific notification as read for the authenticated user
    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        
        return response()->json(['message' => 'Notification marked as read']);
    }
}