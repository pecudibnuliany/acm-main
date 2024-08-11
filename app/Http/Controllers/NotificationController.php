<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(DatabaseNotification $notification)
    {
        // menentukan route untuk notification article
        $model = (new $notification->data['referensi_type'])->find($notification->data['referensi_id']);
        return redirect($model->routeNotification());
    }
}
