<?php

namespace App\Observers;
use Illuminate\Support\Facades\Log;

use App\Models\ControlError;

class ControlErrorObserver
{
    /**
     * Handle the ControlError "created" event.
     */
    public function created(ControlError $controlError): void
    {
        $controlError->user_id = auth()->user()->id ?? 0;
        $controlError->save();
    }

    /**
     * Handle the ControlError "updated" event.
     */
    public function updated(ControlError $controlError): void
    {
        //
    }

    /**
     * Handle the ControlError "deleted" event.
     */
    public function deleted(ControlError $controlError): void
    {
        //
    }

    /**
     * Handle the ControlError "restored" event.
     */
    public function restored(ControlError $controlError): void
    {
        //
    }

    /**
     * Handle the ControlError "force deleted" event.
     */
    public function forceDeleted(ControlError $controlError): void
    {
        //
    }
}
