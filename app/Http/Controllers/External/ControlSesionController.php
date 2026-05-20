<?php

namespace App\Http\Controllers\External;

use App\Events\NotifyPayment;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyControlSesionRequest;
use App\Http\Requests\StoreControlSesionRequest;
use App\Http\Requests\UpdateControlSesionRequest;
use App\Models\AyudaStepTotem;
use App\Models\ControlSesion;
use App\Models\Establecimiento;
use App\Models\EventoHomeTotem;
use App\Models\GrabacionTarjetum;
use App\Models\PagoTotem;
use App\Models\RespuestaPago;
use App\Models\Team;
use App\Models\TipoEvento;
use App\Models\Totem;
use App\Models\Sociedad;
use App\Models\User;
use Cassandra\Collection;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Events\SendTotemHomeEvent;
use DB;

use App\Events\TotemReservationLoadEvent;
use App\Models\Reserva;



class ControlSesionController extends Controller
{
    public function index(Request $request)
    {
        //dd('1');
        abort_if(Gate::denies('control_sesion_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = ControlSesion::with(['emisor', 'receptor'])->select(sprintf('%s.*', (new ControlSesion)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'control_sesion_show';
                $editGate      = 'control_sesion_edit';
                $deleteGate    = 'control_sesion_delete';
                $crudRoutePart = 'control-sesions';

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

            $table->editColumn('estado_sesion', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->estado_sesion ? 'checked' : null) . '>';
            });
            $table->editColumn('updated_at', function ($row) {
                if($row->created_at == $row->updated_at){
                    return '';
                }else{
                    return $row->updated_at;
                }
            });

            $table->rawColumns(['actions', 'placeholder', 'emisor', 'receptor', 'estado_sesion']);

            return $table->make(true);
        }

        $users = User::where('totem_id',null)->get();


        return view('external.controlSesions.index', compact('users', ));
    }

    public function create()
    {
        abort_if(Gate::denies('control_sesion_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $emisors = auth()->user()->id;
        $current_user_sesion = ControlSesion::where('estado_sesion',1)->where('emisor_id',$emisors)->count();
        if($current_user_sesion >= 1){
            return back()->with(['message'=>'No puedes iniciar otra sesión remota, hasta que cierres la actual']);
        }
        $active_sesions = ControlSesion::where('estado_sesion',1)->with(['emisor'])->get();
        $id_sesions = [];

        foreach ($active_sesions as $sesion_obj){
            array_push($id_sesions,$sesion_obj->receptor_id);
        }


        $receptors = User::where('id','!=',auth()->user()->id)
                         ->where('totem_id','!=',null)
                         ->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');


        return view('external.controlSesions.create', compact('emisors', 'receptors', 'id_sesions','active_sesions'));
    }

    public function store(StoreControlSesionRequest $request)
    {
        // Safety checks
        $receptorId = $request->get('receptor_id');
        $emisorId = $request->get('emisor_id', auth()->id());

        // Always prevent multiple active sessions for the same emisor (user)
        $existsForEmisor = ControlSesion::where('estado_sesion', 1)
            ->where('emisor_id', $emisorId)
            ->exists();
        if ($existsForEmisor) {
            return back()->with(['message' => 'No puedes iniciar otra sesión remota hasta que cierres la actual.']);
        }

        // external-only rule: do NOT allow two simultaneous external sessions on the same totem (receptor)
        // External remains permissive and does not block by receptor.
        $isexternalPanel = request()->routeIs('external.*');
        if ($isexternalPanel && $receptorId) {
            // Look for any active session on this receptor whose emisor is an external (non-external) user
            $activeOnReceptor = ControlSesion::where('estado_sesion', 1)
                ->where('receptor_id', $receptorId)
                ->with('emisor')
                ->get();
            $hasexternalOwner = false;
            foreach ($activeOnReceptor as $sess) {
                // Skip own session check (though by design shouldn't exist due to emisor rule above)
                if ((int)$sess->emisor_id === (int)$emisorId) { continue; }
                $emisor = $sess->emisor;
                // If we cannot determine, be conservative and treat as external to avoid duplicates in external
                $isExternalEmisor = ($emisor && method_exists($emisor, 'isExternal')) ? (bool)$emisor->isExternal() : false;
                if (!$isExternalEmisor) { $hasexternalOwner = true; break; }
            }
            if ($hasexternalOwner) {
                return back()->with(['message' => 'Este tótem ya tiene una sesión de control activa en external.']);
            }
        }

        $controlSesion = ControlSesion::create($request->all());
        $usuarioTotem = User::where('id',$controlSesion->receptor_id)->with(['totem'])->first();
        $valor_string = '';
        if(isset($usuarioTotem->totem->id)){
            $establecimiento = Establecimiento::where('id',$usuarioTotem->totem->establecimiento_id)->first();
            if(isset($establecimiento->id)){
                $valor_string .= 'Establecimiento: '.$establecimiento->nombre;
                $valor_string .= ' - Totem: '.$usuarioTotem->totem->codigo;
                $sesion = collect([
                    'receptor_id' => $controlSesion->receptor_id,
                    'emisor_id' => $controlSesion->emisor_id,
                    'canal_transmision' => 'Home Inferior',
                    'tipo_evento_id' => 99
                ]);

                event(new SendTotemHomeEvent($sesion));

                return back()->with(['success'=> 'notify_conection']);
            }
        }

        return back();


    }

    public function ShowEventManager(ControlSesion $controlSesion){
        abort_if(Gate::denies('control_sesion_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_if(Gate::denies('evento_home_totem_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $emisors = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $receptors = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $tipo_eventos = TipoEvento::where('id','!=',1)->where('id','!=',2)->pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');
        $control_actual = ControlSesion::where('emisor_id',auth()->user()->id)->where('estado_sesion',1)->first();

        if(isset($control_actual->id)){

            $eventos_sesion_push = EventoHomeTotem::select('evento_home_totems.*','tipo_eventos.*','users.name')
                ->join('tipo_eventos','tipo_eventos.id','=','evento_home_totems.tipo_evento_id')
                ->join('users','users.id','=','evento_home_totems.emisor_id')
                ->where('evento_home_totems.sesion_id',$control_actual->id)
                ->orderBy('evento_home_totems.id','DESC')
                ->get();

            $documento_anverso = EventoHomeTotem::select('evento_home_totems.*','tipo_eventos.*','users.name')
                ->join('tipo_eventos','tipo_eventos.id','=','evento_home_totems.tipo_evento_id')
                ->join('users','users.id','=','evento_home_totems.emisor_id')
                ->where('evento_home_totems.sesion_id',$control_actual->id)
                ->where('evento_home_totems.tipo_evento_id',3)
                ->orderBy('evento_home_totems.id','DESC')
                ->first();

            $documento_reverso = EventoHomeTotem::select('evento_home_totems.*','tipo_eventos.*','users.name')
                ->join('tipo_eventos','tipo_eventos.id','=','evento_home_totems.tipo_evento_id')
                ->join('users','users.id','=','evento_home_totems.emisor_id')
                ->where('evento_home_totems.tipo_evento_id',6)
                ->where('evento_home_totems.sesion_id',$control_actual->id)
                ->orderBy('evento_home_totems.id','DESC')
                ->first();

            $pagos_sesion = PagoTotem::with(['pagoOrigenRespuestaPagos','emisor'])->where('sesion_id',$control_actual->id)->get();
            $tarjetas_sesion = GrabacionTarjetum::with(['emisor'])->where('sesion_id',$control_actual->id)->get();

            $establecimiento = "";
            $ayudaStepTotem = "";
            $receptor = $control_actual->receptor_id;
            $totem_base = User::where('id',$receptor)->first();
            $imagenes = [];
            $imagenes_tour = [];
            if(isset($totem_base->id)){
                $totem = Totem::where('id',$totem_base->totem_id)->first();

                if(isset($totem->id)){
                    $establecimiento = Establecimiento::where('id',$totem->establecimiento_id)->first();

                    $imagenes_tour = $establecimiento->tour_images;

                    if($totem->fuente_imagenes == 'Totem'){
                        $imagenes = $totem->imagenes;

                    }else if($totem->fuente_imagenes == 'Establecimiento'){

                        if(isset($establecimiento->id)){
                            $imagenes = $establecimiento->imagenes;
                        }
                    }else if($totem->fuente_imagenes == 'Sociedad'){
                        if(isset($establecimiento->id)){
                            $sociedad = Sociedad::where('id',$establecimiento->sociedad_id)->first();
                            if(isset($sociedad->id)){
                                $imagenes = $sociedad->imagenes;
                            }
                        }
                    }

                    if(isset($establecimiento->id)){
                        $var_establecimiento = $establecimiento;
                        $ayudaStepTotem = AyudaStepTotem::where('establecimiento_id',$establecimiento->id)->first();
                    }
                }
            }

            $control_sesion_actual = EventoHomeTotem::where('sesion_id',$control_actual->id)->with(['tipo_evento'])->get();

            return view('external.manager.control',compact([
                'controlSesion','control_actual','control_sesion_actual',
                'tipo_eventos','emisors','receptors','eventos_sesion_push',
                'documento_anverso','documento_reverso','pagos_sesion',
                'tarjetas_sesion','establecimiento','ayudaStepTotem', 'imagenes','imagenes_tour'
            ]));
        }else{
            return view('external.manager.control',compact(['controlSesion']));
        }

    }


    public function cerrar(Request $request){

        $sesion_actual = ControlSesion::where('emisor_id', $request->emisor_id)
            ->where('estado_sesion', 1)
            ->update(['estado_sesion' => 0]);

        $sesion = collect([
            'receptor_id' => $request->receptor_id,
            'canal_transmision' => 'Home Inferior',
            'tipo_evento_id' => 0
        ]);

        // elimina fotos y firmas
        //$purgar_respuesta_texto = EventoHomeTotem::where('emisor_id',$request->emisor_id)
        //    ->update(['respuesta_texto' => '']);

        event(new SendTotemHomeEvent($sesion));

        return back();

    }

    public function edit(ControlSesion $controlSesion)
    {
        abort_if(Gate::denies('control_sesion_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $emisors = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $receptors = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $controlSesion->load('emisor', 'receptor');

        return view('external.controlSesions.edit', compact('controlSesion', 'emisors', 'receptors'));
    }

    public function update(UpdateControlSesionRequest $request, ControlSesion $controlSesion)
    {
        $controlSesion->update($request->all());

        return redirect()->route('external.control-sesions.index');
    }

    public function show(ControlSesion $controlSesion)
    {
        abort_if(Gate::denies('control_sesion_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $controlSesion->load('emisor', 'receptor', 'sesionPagoTotems', 'sesionGrabacionTarjeta', 'sesionEventoHomeTotems', 'sesionFirmaCheckIns');

        return view('external.controlSesions.show', compact('controlSesion'));
    }

    public function destroy(ControlSesion $controlSesion)
    {
        abort_if(Gate::denies('control_sesion_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $controlSesion->delete();

        return back();
    }

    public function massDestroy(MassDestroyControlSesionRequest $request)
    {
        $controlSesions = ControlSesion::find(request('ids'));

        foreach ($controlSesions as $controlSesion) {
            $controlSesion->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }



}
