<?php

namespace bootstrap\Observers;

use App\Models\ControlError;
use App\Notifications\DataChangeEmailNotification;
use Illuminate\Support\Facades\Notification;

class ControlErrorActionObserver
{
    public function created(ControlError $model)
    {
        $data  = ['action' => 'created', 'model_name' => 'ControlError'];
        $users = \App\Models\User::whereHas('roles', function ($q) {
            return $q->where('title', 'Admin');
        })->get();
        Notification::send($users, new DataChangeEmailNotification($data));
    }
}
