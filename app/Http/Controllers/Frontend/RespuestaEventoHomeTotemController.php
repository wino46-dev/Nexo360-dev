<?php

namespace App\Http\Controllers\Frontend;

use App\Events\TotemResponseToBackoffice;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyRespuestaEventoHomeTotemRequest;
use App\Http\Requests\StoreRespuestaEventoHomeTotemRequest;
use App\Http\Requests\UpdateRespuestaEventoHomeTotemRequest;
use App\Models\EventoHomeTotem;
use App\Models\RespuestaEventoHomeTotem;
use App\Models\Session;
use App\Models\Team;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RespuestaEventoHomeTotemController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('respuesta_evento_home_totem_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $respuestaEventoHomeTotems = RespuestaEventoHomeTotem::with(['evento'])->get();

        $evento_home_totems = EventoHomeTotem::get();



        return view('frontend.respuestaEventoHomeTotems.index', compact('evento_home_totems', 'respuestaEventoHomeTotems'));
    }

    public function create()
    {
        abort_if(Gate::denies('respuesta_evento_home_totem_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $eventos = EventoHomeTotem::pluck('objeto', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.respuestaEventoHomeTotems.create', compact('eventos'));
    }

    public function store(StoreRespuestaEventoHomeTotemRequest $request)
    {

        try {
            $respuestaEventoHomeTotem = RespuestaEventoHomeTotem::create($request->all());
            if(isset($respuestaEventoHomeTotem->id)){

                $sesion = EventoHomeTotem::where('id',$respuestaEventoHomeTotem->evento_id)->first();
                if(isset($sesion->id)){
                    $respuesta = [
                        'id' => $respuestaEventoHomeTotem->id,
                        'evento_id' => $respuestaEventoHomeTotem->evento_id,
                        'sesion_id' => $sesion->sesion_id,
                    ];
                    event(new TotemResponseToBackoffice($respuesta));
                    return response()->json(['success'=>'Captura Lanzada correctamente']);
                }else{
                    return response()->json(['success'=>'Ha ocurrido un error al lanzar la petición']);
                }

            }else{
                return response()->json(['success'=>'Ha ocurrido un error al lanzar la petición']);
            }

        }catch (\Exception $ex){
            return response()->json(['success'=> $ex->getMessage()]);
        }

    }

    public function edit(RespuestaEventoHomeTotem $respuestaEventoHomeTotem)
    {
        abort_if(Gate::denies('respuesta_evento_home_totem_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $eventos = EventoHomeTotem::pluck('objeto', 'id')->prepend(trans('global.pleaseSelect'), '');

        $respuestaEventoHomeTotem->load('evento');

        return view('frontend.respuestaEventoHomeTotems.edit', compact('eventos', 'respuestaEventoHomeTotem'));
    }

    public function update(UpdateRespuestaEventoHomeTotemRequest $request, RespuestaEventoHomeTotem $respuestaEventoHomeTotem)
    {
        $respuestaEventoHomeTotem->update($request->all());

        return redirect()->route('frontend.respuesta-evento-home-totems.index');
    }

    public function show(RespuestaEventoHomeTotem $respuestaEventoHomeTotem)
    {
        abort_if(Gate::denies('respuesta_evento_home_totem_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $respuestaEventoHomeTotem->load('evento');

        return view('frontend.respuestaEventoHomeTotems.show', compact('respuestaEventoHomeTotem'));
    }

    public function destroy(RespuestaEventoHomeTotem $respuestaEventoHomeTotem)
    {
        abort_if(Gate::denies('respuesta_evento_home_totem_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $respuestaEventoHomeTotem->delete();

        return back();
    }

    public function massDestroy(MassDestroyRespuestaEventoHomeTotemRequest $request)
    {
        $respuestaEventoHomeTotems = RespuestaEventoHomeTotem::find(request('ids'));

        foreach ($respuestaEventoHomeTotems as $respuestaEventoHomeTotem) {
            $respuestaEventoHomeTotem->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
