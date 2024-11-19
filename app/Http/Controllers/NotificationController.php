<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function middleware()
    {
        return [
            'auth'
        ];
    }

    /**
     * Mark all notifications as read.
     * When a specific id is passed, mark only that one.
     */
    public function mark_as_read()
    {
        $user = Auth::user();

        $user->unreadNotifications
            ->when(request()->id, function ($query) {
                return $query->where('id', request()->id);
            })->markAsRead();

        return back();
    }
}
