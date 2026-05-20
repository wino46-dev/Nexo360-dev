<?php

namespace App\Http\Controllers\External;

use App\Events\NotifyPayment;
use App\Events\SendTotemHomeEvent;
use App\Events\TourGaleryEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyEventoHomeTotemRequest;
use App\Http\Requests\StoreEventoHomeTotemRequest;
use App\Models\ControlSesion;
use App\Models\Establecimiento;
use App\Models\EventoHomeTotem;
use App\Models\Team;
use App\Models\TipoEvento;
use App\Models\Totem;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class EventoHomeTotemController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('evento_home_totem_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = EventoHomeTotem::with(['emisor', 'receptor', 'tipo_evento'])->select(sprintf('%s.*', (new EventoHomeTotem)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'evento_home_totem_show';
                $editGate      = 'evento_home_totem_edit';
                $deleteGate    = 'evento_home_totem_delete';
                $crudRoutePart = 'evento-home-totems';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->addColumn('emisor_name', function ($row) {
                return $row->emisor ? $row->emisor->name : '';
            });

            $table->addColumn('receptor_name', function ($row) {
                return $row->receptor ? $row->receptor->name : '';
            });

            $table->addColumn('tipo_evento_nombre', function ($row) {
                return $row->tipo_evento ? $row->tipo_evento->nombre : '';
            });

            $table->editColumn('objeto', function ($row) {
                return $row->objeto ? $row->objeto : '';
            });
            $table->editColumn('canal_transmision', function ($row) {
                return $row->canal_transmision ? EventoHomeTotem::CANAL_TRANSMISION_SELECT[$row->canal_transmision] : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'emisor', 'receptor', 'tipo_evento']);

            return $table->make(true);
        }

        $users = User::where('totem_id', null)->get();
        $tipo_eventos = TipoEvento::get();
        $teams        = Team::get();

        return view('external.eventoHomeTotems.index', compact('users', 'tipo_eventos', 'teams'));
    }

    public function create()
    {
        abort_if(Gate::denies('evento_home_totem_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return back();

        $receptors = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $tipo_eventos = TipoEvento::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('external.eventoHomeTotems.create', compact('receptors', 'tipo_eventos'));
    }

    public function tourGalery(Request $request)
    {
        // Test Payment Data
        $sesion_galery = collect([
            'emisor_id' => $request->emisor_id,
            'receptor_id' => $request->receptor_id,
            'sesion_id' => $request->sesion_id,
            'url' => $request->url,
        ]);
        event(new TourGaleryEvent($sesion_galery));
        return response()->json(['success' => 'Imágen &nbsp;<b>' . $request->url . '</b>&nbsp; lanzada correctamente']);
    }

    public function notifyPaymentFlag(Request $request)
    {
        // Test Payment Data
        $sesion_payment = collect([
            'emisor_id' => $request->emisor_id,
            'receptor_id' => $request->receptor_id,
            'canal_transmision' => 'Payment Flag',
            'transaccion_id' => $request->objeto,
        ]);
        event(new NotifyPayment($sesion_payment));
        return back();
    }

    public function updateContentDocument(Request $request)
    {

        $imagen_anverso = EventoHomeTotem::where('sesion_id', $request->sesion_id)
            ->where('tipo_evento_id', 3)->orderBy('id', 'DESC')->first();
        $imagen_reverso = EventoHomeTotem::where('sesion_id', $request->sesion_id)
            ->where('tipo_evento_id', 6)->orderBy('id', 'DESC')->first();

        $respuesta = [];
        if (isset($imagen_anverso->id)) {
            if ($imagen_anverso->respuesta_texto != null) {
                $respuesta['anverso'] = $imagen_anverso->respuesta_texto;
            }
        }
        if (isset($imagen_reverso->id)) {
            if ($imagen_reverso->respuesta_texto != null) {
                $respuesta['reverso'] = $imagen_reverso->respuesta_texto;
            }
        }

        return response()->json($respuesta);
    }

    public function store(StoreEventoHomeTotemRequest $request)
    {
        try {
            if ($request->tipo_evento_id == 3 or $request->tipo_evento_id == 6 or $request->tipo_evento_id == 4) {

                $eventoHomeTotem = EventoHomeTotem::updateOrCreate([
                    'tipo_evento_id' => $request->tipo_evento_id,
                    'sesion_id'      => $request->sesion_id,
                    'receptor_id'    => $request->receptor_id,
                    'emisor_id'    => $request->emisor_id,
                    'canal_transmision' => $request->canal_transmision,
                    'objeto' => $request->objeto,
                    'pms' => $request->pms,
                ], [

                    'respuesta_texto' => $request->respuesta_texto
                ]);
            } else {
                $eventoHomeTotem = EventoHomeTotem::create($request->all());
            }

            if (isset($eventoHomeTotem->id)) {

                $datos_evento = ControlSesion::select('*')
                    ->join('evento_home_totems', 'evento_home_totems.sesion_id', '=', 'control_sesions.id')
                    ->where('evento_home_totems.id', $eventoHomeTotem->id)
                    ->first()->toArray();


                $empleado = User::where('id', $request->emisor_id)->first();
                if (isset($empleado->id) && $request->tipo_evento_id == 1) {
                    $datos_evento['empleado'] = $empleado->name;

                    // Check mensaje establecimiento totem
                    $totem_base = User::where('id', $request->receptor_id)->first();
                    if (isset($totem_base->id)) {
                        $totem = Totem::where('id', $totem_base->totem_id)->first();
                        if (isset($totem->id)) {
                            $establecimiento = Establecimiento::where('id', $totem->establecimiento_id)->first();
                            if (isset($establecimiento->id)) {
                                $storing = $establecimiento->mensaje_conectado;
                                $variables = [
                                    'empleado' => $empleado->name,
                                    'codigo_hotel' => $establecimiento->codigo,
                                    'hotel' => $establecimiento->nombre
                                ];

                                $palabras = explode(' ', $storing);
                                foreach ($palabras as $key => $palabra) {
                                    if (preg_match('/\[(.*?)\]/', $palabra, $coincidencias)) {
                                        $variable = $coincidencias[1];
                                        if (isset($variables[$variable])) {
                                            $palabras[$key] = $variables[$variable];
                                        }
                                    }
                                }
                                $storingModificado = implode(' ', $palabras);
                                $datos_evento['mensaje_conectado'] = $storingModificado;
                            }
                        }
                    }


                    $datos_evento['canal_transmision'] = 'Home Inferior';
                }

                if (isset($datos_evento['id'])) {

                    if (!empty($request->mensaje)) {
                        $datos_evento['mensaje'] = $request->mensaje;
                    }
                    event(new SendTotemHomeEvent($datos_evento));
                } else {
                    return response()->json(['success' => "Ha ocurrido un error y el evento no ha podido lanzarse"]);
                    //return back()->withErrors(['message'=> 'Ha ocurrido un error y el evento no ha podido lanzarse.']);
                }

                if ($request->tipo_evento_id == 1 or $request->tipo_evento_id == 2 or $request->tipo_evento_id == 5) {

                    $eventos_sesion_push = EventoHomeTotem::select('evento_home_totems.*', 'tipo_eventos.*', 'users.name')
                        ->join('tipo_eventos', 'tipo_eventos.id', '=', 'evento_home_totems.tipo_evento_id')
                        ->join('users', 'users.id', '=', 'evento_home_totems.emisor_id')
                        ->whereNot('evento_home_totems.tipo_evento_id', 3)
                        ->whereNot('evento_home_totems.tipo_evento_id', 4)
                        ->whereNot('evento_home_totems.tipo_evento_id', 6)
                        ->where('evento_home_totems.sesion_id', $request->sesion_id)
                        ->orderBy('evento_home_totems.id', 'DESC')
                        ->get();

                    if (isset($request->tipo)) {
                        return back();
                    }

                    return response()->json(['success' => $eventos_sesion_push]);
                } else if ($request->tipo_evento_id == 3) {
                    return response()->json(['success' => 'Solicitud de escaneo de documentación lanzada correctamente (Anverso)']);
                } else if ($request->tipo_evento_id == 6) {
                    return response()->json(['success' => 'Solicitud de escaneo de documentación lanzada correctamente (Reverso)']);
                } else if ($request->tipo_evento_id == 4) {
                    return response()->json(['success' => 'Solicitud de captura de firma lanzado correctamente']);
                } else if ($request->tipo_evento_id == 8) {
                    return response()->json(['success' => 'Foto Capturada Correctamente']);
                } else {
                    return response()->json(['error' => 'Evento no definido']);
                }
            } else {
                return response()->json(['error' => "Ha ocurrido un error y el evento no ha podido lanzarse"]);
                //return back()->withErrors(['message'=> 'Ha ocurrido un error y el evento no ha podido lanzarse.']);
            }
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex]);
        }
    }

    public function edit(EventoHomeTotem $eventoHomeTotem)
    {
        abort_if(Gate::denies('evento_home_totem_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tipo_eventos = TipoEvento::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $eventoHomeTotem->load('emisor', 'receptor', 'tipo_evento');

        return view('external.eventoHomeTotems.edit', compact('eventoHomeTotem', 'tipo_eventos'));
    }

    public function update(UpdateEventoHomeTotemRequest $request, EventoHomeTotem $eventoHomeTotem)
    {
        $eventoHomeTotem->update($request->all());

        if ($request->input('respuesta_imagen', false)) {
            if (! $eventoHomeTotem->respuesta_imagen || $request->input('respuesta_imagen') !== $eventoHomeTotem->respuesta_imagen->file_name) {
                if ($eventoHomeTotem->respuesta_imagen) {
                    $eventoHomeTotem->respuesta_imagen->delete();
                }
                $eventoHomeTotem->addMedia(storage_path('tmp/uploads/' . basename($request->input('respuesta_imagen'))))->toMediaCollection('respuesta_imagen');
            }
        } elseif ($eventoHomeTotem->respuesta_imagen) {
            $eventoHomeTotem->respuesta_imagen->delete();
        }

        return redirect()->route('external.evento-home-totems.index');
    }

    public function show(EventoHomeTotem $eventoHomeTotem)
    {
        abort_if(Gate::denies('evento_home_totem_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $eventoHomeTotem->load('emisor', 'receptor', 'tipo_evento');

        return view('external.eventoHomeTotems.show', compact('eventoHomeTotem'));
    }

    public function destroy(EventoHomeTotem $eventoHomeTotem)
    {
        abort_if(Gate::denies('evento_home_totem_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $eventoHomeTotem->delete();

        return back();
    }

    public function massDestroy(MassDestroyEventoHomeTotemRequest $request)
    {
        $eventoHomeTotems = EventoHomeTotem::find(request('ids'));

        foreach ($eventoHomeTotems as $eventoHomeTotem) {
            $eventoHomeTotem->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('evento_home_totem_create') && Gate::denies('evento_home_totem_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new EventoHomeTotem();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
