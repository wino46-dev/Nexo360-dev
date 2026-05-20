<?php

namespace App\Http\Controllers\Api\V1\Admin;
use App\Events\WriteCardResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGrabacionTarjetumRequest;
use App\Http\Requests\UpdateGrabacionTarjetumRequest;
use App\Http\Resources\Admin\GrabacionTarjetumResource;
use App\Models\ControlError;
use App\Models\GrabacionTarjetum;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GrabacionTarjetaApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('grabacion_tarjetum_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new GrabacionTarjetumResource(GrabacionTarjetum::with(['emisor', 'receptor'])->get());
    }

    public function store(StoreGrabacionTarjetumRequest $request)
    {
        $grabacionTarjetum = GrabacionTarjetum::create($request->all());

        return (new GrabacionTarjetumResource($grabacionTarjetum))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(GrabacionTarjetum $grabacionTarjetum)
    {
        abort_if(Gate::denies('grabacion_tarjetum_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new GrabacionTarjetumResource($grabacionTarjetum->load(['receptor.totem.totemConfiguracionGrabadors']));
    }

    public function update(UpdateGrabacionTarjetumRequest $request, GrabacionTarjetum $grabacionTarjetum)
    {
        $grabacionTarjetum->update($request->all());
        sleep(4);
        if($grabacionTarjetum->status === 'Fail'){
            $track_error = ControlError::where('origen','SH360TotemApp')->where('mensaje','like', '%' . $grabacionTarjetum->id . '%')->first();
            if(isset($track_error->id)){
                $track = $track_error->descripcion;
            }else{
                $track = 'El error no se ha podido filtrar. Contacte con soporte y notifique el ID de transacción: '.$grabacionTarjetum->id;
            }
        }else{
            $track = 'Todo OK';
        }
        $sesion_write_card = collect([
            'sesion_id' => $grabacionTarjetum->sesion_id,
            'emisor_id' => $grabacionTarjetum->emisor_id,
            'receptor_id' => $grabacionTarjetum->receptor_id,
            'transaccion_id' => $grabacionTarjetum->id,
            'status' => $grabacionTarjetum->status,
            'uid_card' => $grabacionTarjetum->uid_card,
            'track' => $track,
        ]);
        event(new WriteCardResponse($sesion_write_card));

        return (new GrabacionTarjetumResource($grabacionTarjetum))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(GrabacionTarjetum $grabacionTarjetum)
    {
        abort_if(Gate::denies('grabacion_tarjetum_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $grabacionTarjetum->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
