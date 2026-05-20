<?php

namespace App\Http\Controllers\External;

use App\Events\NotifyPayment;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPagoTotemRequest;
use App\Http\Requests\StorePagoTotemRequest;
use App\Http\Requests\UpdatePagoTotemRequest;
use App\Models\PagoTotem;
use App\Models\Team;
use App\Models\User;
use App\Models\ControlSesion;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Log;
use App\Jobs\RespuestaPagoJob;
use App\Services\HotelApiService;
use App\Models\ControlError;
use DB;


class PagoTotemController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('pago_totem_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = PagoTotem::with(['emisor', 'receptor'])->select(sprintf('%s.*', (new PagoTotem)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'pago_totem_show';
                $editGate      = 'pago_totem_edit';
                $deleteGate    = 'pago_totem_delete';
                $crudRoutePart = 'pago-totems';

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

            $table->editColumn('importe', function ($row) {
                return $row->importe ? $row->importe : '';
            });
            $table->editColumn('factura', function ($row) {
                return $row->factura ? $row->factura : '';
            });
            $table->editColumn('tipo_operacion', function ($row) {
                return $row->tipo_operacion ? PagoTotem::TIPO_OPERACION_SELECT[$row->tipo_operacion] : '';
            });

            $table->addColumn('sesion_id', function ($row) {
                return $row->sesion ? $row->sesion->id : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'emisor', 'receptor']);

            return $table->make(true);
        }

        $users = User::where('totem_id', null)->get();
        $teams = Team::get();

        return view('external.pagoTotems.index', compact('users', 'teams'));
    }

    public function create()
    {
        return back();

        abort_if(Gate::denies('pago_totem_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $emisors = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $receptors = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $sesions = ControlSesion::pluck('estado_sesion', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('external.pagoTotems.create', compact('emisors', 'receptors', 'sesions'));
    }

    public function store(StorePagoTotemRequest $request)
    {
        $data = $request->all();

        if (isset($data['origen']) && $data['origen'] == 'manual') {
            $data['estado'] = 'Autorizada';
        }

        $hotel_api = new HotelApiService(session('api_establecimiento_id'));

        $folio = $hotel_api->folioDetail($data['folio_id']);

        $pendiente = floatval($folio['pendingAmount']);
        if (floatval($data['importe']) > $pendiente) {
            return response()->json([
                'success' => false,
                'message' => 'El importe es mayor al valor pendiente'
            ]);
        }


        $pagoTotem = PagoTotem::create($data);

        if ($pagoTotem) {

            try {

                $usuarioTotem = User::where('id', $request->receptor_id)->with(['totem'])->first();
                if (!isset($usuarioTotem->totem->id)) {
                    return 'error totem_id';
                }

                $hotel_api = new HotelApiService($usuarioTotem->totem->establecimiento_id);

                //$accountJournals = $hotel_api->accountJournals();

                $journalId = $usuarioTotem->totem->establecimiento->pms_payment_method_manual;

                $pago_api_data = [
                    'journalId' => $journalId ?? '0',
                    'amount' => floatval($request->importe),
                    'folioId' => intval($request->folio_id),
                    'transactionType' => "customer_inbound",
                    'reference' => "Pago Manual, Factura: " . $request->factura,
                    'date' => date('Y-m-d'),
                ];

                if (isset($data['estado']) && $data['estado'] == 'Autorizada') {
                    $hotel_api->transactions($pago_api_data);
                }
            } catch (\Exception $e) {
                ControlError::create([
                    'origen' => 'RoomdooApiService',
                    'tipo' => 'Pago Api',
                    'mensaje' => 'Error al realizar pago en la api',
                    'descripcion' => 'Folio: ' . intval($request->folio_id) . ', referencia: ' . "Pago Manual, Factura: " . $request->factura,
                    'establecimiento_id' => session('api_establecimiento_id') ?? null
                ]);
            }
        }

        if (isset($pagoTotem->id)) {
            if ($data['origen'] == 'totem') {
                $sesion_payment = collect([
                    'sesion_id' => $request->sesion_id,
                    'emisor_id' => $request->emisor_id,
                    'receptor_id' => $request->receptor_id,
                    'transaccion_id' => $pagoTotem->id,
                ]);
                event(new NotifyPayment($sesion_payment));
                RespuestaPagoJob::dispatch($sesion_payment)
                    ->delay(now()->addSeconds(60));
            }

            $pagos_sesion = PagoTotem::with(['pagoOrigenRespuestaPagos', 'emisor'])->where('sesion_id', $request->sesion_id)->get();

            return response()->json(['success' => $pagos_sesion]);
        } else {
            return response()->json(['success' => "No se ha podido generar la orden de cobro"]);
        }
    }

    public function edit(PagoTotem $pagoTotem)
    {
        abort_if(Gate::denies('pago_totem_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $emisors = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $receptors = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $pagoTotem->load('emisor', 'receptor');

        return view('external.pagoTotems.edit', compact('emisors', 'pagoTotem', 'receptors'));
    }

    public function update(UpdatePagoTotemRequest $request, PagoTotem $pagoTotem)
    {
        $pagoTotem->update($request->all());

        return redirect()->route('external.pago-totems.index');
    }

    public function show(PagoTotem $pagoTotem)
    {
        abort_if(Gate::denies('pago_totem_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pagoTotem->load('emisor', 'receptor', 'sesion', 'pagoOrigenRespuestaPagos');

        return view('external.pagoTotems.show', compact('pagoTotem'));
    }

    public function destroy(PagoTotem $pagoTotem)
    {
        DB::beginTransaction();
        try {
            abort_if(Gate::denies('pago_totem_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

            $current_user_sesion = ControlSesion::where('estado_sesion', 1)->where('emisor_id', auth()->user()->id)->with(['receptor'])->first();
            if (!isset($current_user_sesion->receptor_id)) {
                return 'error receptor_id';
            }
            $usuarioTotem = User::where('id', $current_user_sesion->receptor_id)->with(['totem'])->first();
            if (!isset($usuarioTotem->totem->id)) {
                return 'error totem_id';
            }
            //$remote_hotel_id = $usuarioTotem->totem->establecimiento->remote_hotel_id;

            $hotel_api = new HotelApiService($usuarioTotem->totem->establecimiento_id, true);

            $accountJournals = $hotel_api->accountJournals();
           //

            $pago_api_data = [
                'journalId' => $accountJournals[0]['id'] ?? '0',
                'amount' => floatval($pagoTotem->importe),
                'folioId' => intval($pagoTotem->folio_id),
                'transactionType' => "customer_outbound",
                'reference' => "Pago Manual Eliminado, Factura: " . $pagoTotem->factura,
                'date' => date('Y-m-d'),
            ];
            if (isset($pagoTotem['estado']) && $pagoTotem['estado'] == 'Autorizada') {

              //  HotelApiService::transactions($pago_api_data);
                $pagoTotem->delete();
            }
            DB::commit();


        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return $e->getMessage();
        }

        return back();
    }

    public function massDestroy(MassDestroyPagoTotemRequest $request)
    {
        $pagoTotems = PagoTotem::find(request('ids'));

        foreach ($pagoTotems as $pagoTotem) {
            $pagoTotem->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
