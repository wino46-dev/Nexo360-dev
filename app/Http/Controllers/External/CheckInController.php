<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyCheckInRequest;
use App\Http\Requests\StoreCheckInRequest;
use App\Http\Requests\UpdateCheckInRequest;
use App\Models\CheckIn;
use App\Models\Habitacion;
use App\Models\Reserva;
use App\Models\Reservation;
use App\Models\Team;
use App\Models\Totem;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use ZipArchive;
use File;
use Illuminate\Support\Facades\Log;

class CheckInController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('check_in_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Detect active Control de Sesión and its establecimiento
        $defaultEstablecimientoId = null;
        $defaultEstablecimientoCodigo = null;
        $hasActiveControlSesion = false;
        try {
            $emisors = auth()->user()->id;
            $current = \App\Models\ControlSesion::where('estado_sesion', 1)
                ->where('emisor_id', $emisors)
                ->with(['receptor.totem.establecimiento'])
                ->first();
            if ($current && $current->receptor && $current->receptor->totem) {
                $defaultEstablecimientoId = $current->receptor->totem->establecimiento_id ?? null;
                $defaultEstablecimientoCodigo = optional($current->receptor->totem->establecimiento)->codigo;
                $hasActiveControlSesion = !empty($defaultEstablecimientoId);
            }
        } catch (\Throwable $e) {
            // ignore
        }

        $reservas    = Reservation::get();
        $habitacions = Habitacion::get();
        $totems      = Totem::get();
        $teams       = Team::get();

        return view('external.checkIns.index', compact('reservas', 'habitacions', 'totems', 'teams', 'defaultEstablecimientoId', 'defaultEstablecimientoCodigo', 'hasActiveControlSesion'));
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {

            $filters = $request->only([
                'filtro_establecimiento_id',
                'filtro_reservation_name',
                'filtro_documento',
                'filtro_nombres',
                'filtro_apellidos',
                'filtro_fecha_inicio',
                'filtro_fecha_final'
            ]);


            $query = CheckIn::select(
                'check_in.*',
                'reservations.name as reservation_name', // Ejemplo: agregar código de la reserva
                'establecimientos.nombre as establecimiento_name' // Ejemplo: agregar estado de la reserva
            )
                ->join('reservations', 'check_in.reservation_id', '=', 'reservations.id')
                ->join('folios', 'reservations.folio_id', '=', 'folios.id')
                ->join('establecimientos', 'folios.establecimiento_id', '=', 'establecimientos.id')
                ->where('check_in.checkin_partner_state', 'onboard');

            // External scoping: restrict to allowed hoteles when present
            $allowed = config('external.allowed_hotels');
            if (is_array($allowed)) {
                $ids = count($allowed) ? $allowed : [0];
                $query->whereIn('establecimientos.id', $ids);
            }

            // If an active Control de Sesión exists, force filter by its establecimiento
            try {
                $emisors = auth()->user()->id;
                $current = \App\Models\ControlSesion::where('estado_sesion', 1)
                    ->where('emisor_id', $emisors)
                    ->with(['receptor.totem'])
                    ->first();
                $forcedEid = $current && $current->receptor && $current->receptor->totem ? ($current->receptor->totem->establecimiento_id ?? null) : null;
                if (!empty($forcedEid)) {
                    $query->where('folios.establecimiento_id', $forcedEid);
                }
            } catch (\Throwable $e) {
                // ignore
            }

            // Aplicar filtros dinámicamente
            if (!empty($filters['filtro_establecimiento_id'])) {
                $query->where('folios.establecimiento_id', $filters['filtro_establecimiento_id']);
            }
            if (!empty($filters['filtro_reservation_name'])) {
                $query->where('reservations.name', 'like', '%' . $filters['filtro_reservation_name'] . '%');
            }
            if (!empty($filters['filtro_documento'])) {
                $query->where('check_in.document_number', 'like', '%' . $filters['filtro_documento'] . '%');
            }
            if (!empty($filters['filtro_apellidos'])) {
                $query->where('check_in.lastname', 'like', '%' . $filters['filtro_apellidos'] . '%');
            }

            if (!empty($filters['filtro_nombres'])) {
                $query->where('check_in.firstname', 'like', '%' . $filters['filtro_nombres'] . '%');
            }

            if (!empty($filters['filtro_fecha_inicio']) && !empty($filters['filtro_fecha_final'])) {
                $query->whereBetween('check_in.created_at', [
                    $filters['filtro_fecha_inicio'] . ' 00:00:00',
                    $filters['filtro_fecha_final'] . ' 23:59:59'
                ]);
            }
            //dd($query->toSql());

            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions1', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'check_in_show';
                $editGate      = 'check_in_edit_2';
                $deleteGate    = 'check_in_delete';
                $crudRoutePart = 'check-ins';

                return view('partials.datatablesPdfActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->addColumn('firstname', function ($row) {
                return $row->firstname ? $row->firstname : '';
            });

            $table->addColumn('lastname', function ($row) {
                return $row->lastname ? $row->lastname . ' ' . $row->lastname2 : '';
            });

            $table->addColumn('habitacion_codigo', function ($row) {
                return $row->habitacion ? $row->habitacion->codigo : '';
            });

            $table->addColumn('totem_codigo', function ($row) {
                return $row->totem ? $row->totem->codigo : '';
            });

            $table->editColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at->format('Y-m-d H:i:s') : '';
            });
            $table->editColumn('firma_verificada', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->firma_verificada ? 'checked' : null) . '>';
            });

            $table->rawColumns(['actions', 'placeholder', 'cliente', 'reserva', 'habitacion', 'totem', 'firma_verificada']);

            return $table->make(true);
        }
    }

    public function create()
    {
        abort_if(Gate::denies('check_in_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clientes = Cliente::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $reservas = Reservation::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $habitacions = Habitacion::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $totems = Totem::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('external.checkIns.create', compact('clientes', 'habitacions', 'reservas', 'totems'));
    }

    public function store(StoreCheckInRequest $request)
    {
        $checkIn = CheckIn::create($request->all());

        if ($request->input('dni_anverso', false)) {
            $checkIn->addMedia(storage_path('tmp/uploads/' . basename($request->input('dni_anverso'))))->toMediaCollection('dni_anverso');
        }

        if ($request->input('dni_reverso', false)) {
            $checkIn->addMedia(storage_path('tmp/uploads/' . basename($request->input('dni_reverso'))))->toMediaCollection('dni_reverso');
        }

        if ($request->input('firma_checkin', false)) {
            $checkIn->addMedia(storage_path('tmp/uploads/' . basename($request->input('firma_checkin'))))->toMediaCollection('firma_checkin');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $checkIn->id]);
        }

        $prefix = request()->routeIs('external.*') ? 'external' : 'external';
        return redirect()->route($prefix . '.check-ins.index');
    }

    public function edit(CheckIn $checkIn)
    {
        abort_if(Gate::denies('check_in_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clientes = Cliente::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $reservas = Reservation::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $habitacions = Habitacion::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $totems = Totem::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $checkIn->load('cliente', 'reserva', 'habitacion', 'totem');

        return view('external.checkIns.edit', compact('checkIn', 'clientes', 'habitacions', 'reservas', 'totems'));
    }

    public function update(UpdateCheckInRequest $request, CheckIn $checkIn)
    {
        $checkIn->update($request->all());

        if ($request->input('dni_anverso', false)) {
            if (! $checkIn->dni_anverso || $request->input('dni_anverso') !== $checkIn->dni_anverso->file_name) {
                if ($checkIn->dni_anverso) {
                    $checkIn->dni_anverso->delete();
                }
                $checkIn->addMedia(storage_path('tmp/uploads/' . basename($request->input('dni_anverso'))))->toMediaCollection('dni_anverso');
            }
        } elseif ($checkIn->dni_anverso) {
            $checkIn->dni_anverso->delete();
        }

        if ($request->input('dni_reverso', false)) {
            if (! $checkIn->dni_reverso || $request->input('dni_reverso') !== $checkIn->dni_reverso->file_name) {
                if ($checkIn->dni_reverso) {
                    $checkIn->dni_reverso->delete();
                }
                $checkIn->addMedia(storage_path('tmp/uploads/' . basename($request->input('dni_reverso'))))->toMediaCollection('dni_reverso');
            }
        } elseif ($checkIn->dni_reverso) {
            $checkIn->dni_reverso->delete();
        }

        if ($request->input('firma_checkin', false)) {
            if (! $checkIn->firma_checkin || $request->input('firma_checkin') !== $checkIn->firma_checkin->file_name) {
                if ($checkIn->firma_checkin) {
                    $checkIn->firma_checkin->delete();
                }
                $checkIn->addMedia(storage_path('tmp/uploads/' . basename($request->input('firma_checkin'))))->toMediaCollection('firma_checkin');
            }
        } elseif ($checkIn->firma_checkin) {
            $checkIn->firma_checkin->delete();
        }

        $prefix = request()->routeIs('external.*') ? 'external' : 'external';
        return redirect()->route($prefix . '.check-ins.index');
    }

    public function show(CheckIn $checkIn)
    {
        abort_if(Gate::denies('check_in_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        //$checkIn->load('cliente', 'reserva', 'habitacion', 'totem');

        return view('external.checkIns.show', compact('checkIn'));
    }

    public function destroy(CheckIn $checkIn)
    {
        abort_if(Gate::denies('check_in_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $checkIn->delete();

        return back();
    }

    public function massDestroy(MassDestroyCheckInRequest $request)
    {
        $checkIns = CheckIn::find(request('ids'));

        foreach ($checkIns as $checkIn) {
            $checkIn->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('check_in_create') && Gate::denies('check_in_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new CheckIn();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }

    public function parteViajeroPdf(Request $request)
    {
        $data = $request->all();
        $file_origen = public_path('parte-viajero/parte_viajero_' . $data['checkin_id'] . '.pdf');

        if (File::exists($file_origen)) {

            $checkin_data = CheckIn::select(
                'check_in.*',
                'reservations.name as reservation_name', // Ejemplo: agregar código de la reserva
                'establecimientos.codigo as establecimiento_codigo' // Ejemplo: agregar estado de la reserva
            )
                ->join('reservations', 'check_in.reservation_id', '=', 'reservations.id')
                ->join('folios', 'reservations.folio_id', '=', 'folios.id')
                ->join('establecimientos', 'folios.establecimiento_id', '=', 'establecimientos.id')
                ->where('check_in.id', $data['checkin_id'])
                ->first();

            $reservation_name =  str_replace('/', '-', $checkin_data['reservation_name']);
            $fecha = explode(' ', $checkin_data['created_at'])[0];
            $fecha = str_replace('-', '', $fecha);
            $name_new = $checkin_data['establecimiento_codigo'] . '_' . $reservation_name . '_' . $data['checkin_id'] . '_' . $fecha . '.pdf';
            $file_destino = public_path('parte-viajero-tmp/' . $name_new);

            File::copy($file_origen, $file_destino);
            $url_pdf = 'parte-viajero-tmp/' . $name_new;
            return view('external.checkIns._parte_viajero_pdf', compact('url_pdf'));
        }
    }

    public function pdfDownload(Request $request)
    {
        $data = $request->all();

        $ids = $data['ids'];
        $pdfFiles = [];
        foreach ($ids as $id) {
            //$pdfFiles[] = 'parte_viajero_'.$id.'.pdf';
            $pdfFiles[] = $id;
        }


        $pdfPath = public_path('parte-viajero');

        $zipPath = storage_path('app/public/tmp');
        if (!File::exists($zipPath)) {
            File::makeDirectory($zipPath, 0755, true);
        }

        $zipFileName = 'pdfs_' . time() . '.zip';
        $zipFullPath = $zipPath . '/' . $zipFileName;
        //  dd($zipFullPath);

        // Crear el archivo ZIP
        $zip = new ZipArchive();
        if ($zip->open($zipFullPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {

            foreach ($pdfFiles as $id) {
                $filePath = $pdfPath . '/' . 'parte_viajero_' . $id . '.pdf';
                if (File::exists($filePath)) {

                    $checkin_data = CheckIn::select(
                        'check_in.*',
                        'reservations.name as reservation_name', // Ejemplo: agregar código de la reserva
                        'establecimientos.codigo as establecimiento_codigo' // Ejemplo: agregar estado de la reserva
                    )
                        ->join('reservations', 'check_in.reservation_id', '=', 'reservations.id')
                        ->join('folios', 'reservations.folio_id', '=', 'folios.id')
                        ->join('establecimientos', 'folios.establecimiento_id', '=', 'establecimientos.id')
                        ->where('check_in.id', $id)
                        ->first();

                    $reservation_name =  str_replace('/', '-', $checkin_data['reservation_name']);
                    $fecha = explode(' ', $checkin_data['created_at'])[0];
                    $fecha = str_replace('-', '', $fecha);
                    $name_new = $checkin_data['establecimiento_codigo'] . '_' . $reservation_name . '_' . $id . '_' . $fecha . '.pdf';
                    //$file_destino = public_path('parte-viajero-tmp/' . $name_new);


                    $zip->addFile($filePath, $name_new); // Añadir el archivo al ZIP
                } else {
                }
            }
            $zip->close();
        } else {
            return response()->json(['error' => 'No se pudo crear el archivo ZIP.'], 500);
        }

        return response()->download($zipFullPath)->deleteFileAfterSend(true);
    }
}
