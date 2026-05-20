<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyAyudaStepTotemRequest;
use App\Http\Requests\StoreAyudaStepTotemRequest;
use App\Http\Requests\UpdateAyudaStepTotemRequest;
use App\Models\AyudaStepTotem;
use App\Models\Establecimiento;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use App\Events\AliceEvent;
use App\Services\AliceService;
use Illuminate\Support\Facades\Log;

class AliceController extends Controller
{

    public function capture(Request $request)
    {
        $data = $request->all();
        Log::info('front capture');
        Log::info($data);

        return view('frontend.alice.capture', compact('data'));
    }

    public function notification(Request $request)
    {
        $data = $request->all();

        Log::info('front notification');
        Log::info($data);
        //$da = AliceService::userReport($data['checkin_id']);
        

        /*$data = collect([
            'emisor_id' => $request->emisor_id,
            'receptor_id' => $request->receptor_id,
            'action' => $request->action,
        ]);*/
        
        event(new AliceEvent($data));
    }
}
