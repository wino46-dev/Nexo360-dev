<?php

namespace App\Http\Controllers\External;

use App\Events\WriteCardEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyGrabacionTarjetumRequest;
use App\Http\Requests\StoreGrabacionTarjetumRequest;
use App\Http\Requests\UpdateGrabacionTarjetumRequest;
use App\Models\GrabacionTarjetum;
use App\Models\Team;
use App\Models\User;
use App\Models\Totem;
use App\Models\Establecimiento;
use Illuminate\Support\Facades\Log;

use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class GrabacionTarjetaController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('grabacion_tarjetum_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = GrabacionTarjetum::with(['emisor', 'receptor'])->select(sprintf('%s.*', (new GrabacionTarjetum)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'grabacion_tarjetum_show';
                $editGate      = 'grabacion_tarjetum_edit';
                $deleteGate    = 'grabacion_tarjetum_delete';
                $crudRoutePart = 'grabacion-tarjeta';

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

            $table->editColumn('room_no', function ($row) {
                return $row->room_no ? $row->room_no : '';
            });
            $table->editColumn('uid_card', function ($row) {
                return $row->uid_card ? $row->uid_card : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'emisor', 'receptor']);

            return $table->make(true);
        }

        $users = User::where('totem_id', null)->get();
        $teams = Team::get();

        return view('external.grabacionTarjeta.index', compact('users', 'teams'));
    }

    public function create()
    {
        return back();

        abort_if(Gate::denies('grabacion_tarjetum_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $emisors = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $receptors = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $sesions = ControlSesion::pluck('estado_sesion', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('external.grabacionTarjeta.create', compact('emisors', 'receptors', 'sesions'));
    }

    public function NotifyPutCardOnReader(Request $request)
    {

        $usuarioTotem = User::where('id', $request->receptor_id)->with(['totem'])->first();

        $tipo_grabador = '';
        $json_grabador = '';
        if (isset($usuarioTotem->totem->totemConfiguracionGrabadors[0]->software_gestion)) {
            $tipo_grabador = $usuarioTotem->totem->totemConfiguracionGrabadors[0]->software_gestion;
            $json_grabador = $usuarioTotem->totem->totemConfiguracionGrabadors[0]->json_grabador;
        }

        $sesion_write_card = collect([
            'sesion_id' => $request->sesion_id,
            'emisor_id' => $request->emisor_id,
            'receptor_id' => $request->receptor_id,
            'step' => $request->step,
            'tipo_grabador' => $tipo_grabador,
            'json_grabador' => $json_grabador
        ]);


        $evento_channel = 'WriteCard';
        if ($tipo_grabador == 'tesa') {
            $evento_channel = env('PUSHER_EVENT_CARD_TESA');
        }

        event(new WriteCardEvent($sesion_write_card, $evento_channel));
        return response()->json(['success' => 'Mensaje de colocación de tarjeta lanzado con éxito']);
    }

    public function store(StoreGrabacionTarjetumRequest $request)
    {
        $data = $request->all();
        $data['folio_id'] = intval($data['folio_id']);
        if (empty($data['folio_id'])) {
            unset($data['folio_id']);
        }
        if (preg_match('/^\d{2}:\d{2}$/', $data['time_in'])) {
            $data['time_in'] .= ':00'; // Agrega ":00" al final
        }
        if (preg_match('/^\d{2}:\d{2}$/', $data['time_out'])) {
            $data['time_out'] .= ':00'; // Agrega ":00" al final
        }


        $grabacionTarjetum = GrabacionTarjetum::create($data);

        $totem_base = User::where('id', $request->receptor_id)->first();
        $totem = Totem::where('id', $totem_base->totem_id)->first();
        $establecimiento = Establecimiento::where('id', $totem->establecimiento_id)->first();
        $provider_id = $establecimiento->proveedor_cerradura ?? '';

        if (isset($grabacionTarjetum->id)) {

            $usuarioTotem = User::where('id', $request->receptor_id)->with(['totem'])->first();

            $tipo_grabador = '';
            $json_grabador = '';
            if (isset($usuarioTotem->totem->totemConfiguracionGrabadors[0]->software_gestion)) {
                $tipo_grabador = $usuarioTotem->totem->totemConfiguracionGrabadors[0]->software_gestion;
                $json_grabador = $usuarioTotem->totem->totemConfiguracionGrabadors[0]->json_grabador;
            }

            $sesion_write_card = collect([
                'sesion_id' => $request->sesion_id,
                'emisor_id' => $request->emisor_id,
                'receptor_id' => $request->receptor_id,
                'transaccion_id' => $grabacionTarjetum->id,
                'status' => $request->status,
                'provider' => $provider_id,
                'tipo_grabador' => $tipo_grabador,
                'json_grabador' => $json_grabador
            ]);
            $evento_channel = 'WriteCard';
            if ($tipo_grabador == 'tesa') {
                $evento_channel = env('PUSHER_EVENT_CARD_TESA');
            }

            event(new WriteCardEvent($sesion_write_card, $evento_channel));

            $tarjetas_sesion = GrabacionTarjetum::with(['emisor'])->where('sesion_id', $request->sesion_id)->get();
            return response()->json(['success' => $tarjetas_sesion]);
        } else {
            return response()->json(['success' => "No se ha podido generar la solicitud de grabación de tarjeta"]);
        }

        return back();
    }

    public function edit(GrabacionTarjetum $grabacionTarjetum)
    {
        return back();

        abort_if(Gate::denies('grabacion_tarjetum_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $emisors = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $receptors = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $sesions = ControlSesion::pluck('estado_sesion', 'id')->prepend(trans('global.pleaseSelect'), '');

        $grabacionTarjetum->load('emisor', 'receptor', 'sesion');

        return view('external.grabacionTarjeta.edit', compact('emisors', 'grabacionTarjetum', 'receptors', 'sesions'));
    }

    public function update(UpdateGrabacionTarjetumRequest $request, GrabacionTarjetum $grabacionTarjetum)
    {

        $grabacionTarjetum->update($request->all());

        return redirect()->route('external.grabacion-tarjeta.index');
    }

    public function show(GrabacionTarjetum $grabacionTarjetum)
    {
        abort_if(Gate::denies('grabacion_tarjetum_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $grabacionTarjetum->load('emisor', 'receptor', 'sesion');

        return view('external.grabacionTarjeta.show', compact('grabacionTarjetum'));
    }

    public function destroy(GrabacionTarjetum $grabacionTarjetum)
    {
        abort_if(Gate::denies('grabacion_tarjetum_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $grabacionTarjetum->delete();

        return back();
    }

    public function massDestroy(MassDestroyGrabacionTarjetumRequest $request)
    {
        $grabacionTarjeta = GrabacionTarjetum::find(request('ids'));

        foreach ($grabacionTarjeta as $grabacionTarjetum) {
            $grabacionTarjetum->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
