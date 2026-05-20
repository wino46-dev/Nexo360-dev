<?php

namespace App\Http\Controllers\Frontend;

use App\Events\TotemResponseToBackoffice;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyCheckInRequest;
use App\Http\Requests\StoreCheckInRequest;
use App\Http\Requests\UpdateCheckInRequest;
use App\Models\CheckIn;
use App\Models\Cliente;
use App\Models\EventoHomeTotem;
use App\Models\Habitacion;
use App\Models\Reserva;
use App\Models\Reservation;
use App\Models\RespuestaEventoHomeTotem;
use App\Models\Team;
use App\Models\Totem;
use Gate;

use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class CheckInController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('check_in_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $checkIns = CheckIn::with(['cliente', 'reserva', 'habitacion', 'totem', 'media'])->get();

        $clientes = Cliente::get();

        $reservas = Reservation::get();

        $habitacions = Habitacion::get();

        $totems = Totem::get();



        return view('frontend.checkIns.index', compact('checkIns', 'clientes', 'habitacions', 'reservas', 'totems'));
    }


    public function screenCamera(Request $request){

        return response()->json(['success'=>'Successfully','image'=> $request->image ]);
    }


    public function create()
    {
        abort_if(Gate::denies('check_in_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clientes = Cliente::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $reservas = Reservation::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $habitacions = Habitacion::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $totems = Totem::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.checkIns.create', compact('clientes', 'habitacions', 'reservas', 'totems'));
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

        return redirect()->route('frontend.check-ins.index');
    }

    public function edit(CheckIn $checkIn)
    {
        abort_if(Gate::denies('check_in_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clientes = Cliente::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $reservas = Reservation::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $habitacions = Habitacion::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $totems = Totem::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $checkIn->load('cliente', 'reserva', 'habitacion', 'totem');

        return view('frontend.checkIns.edit', compact('checkIn', 'clientes', 'habitacions', 'reservas', 'totems'));
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

        return redirect()->route('frontend.check-ins.index');
    }

    public function show(CheckIn $checkIn)
    {
        abort_if(Gate::denies('check_in_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $checkIn->load('cliente', 'reserva', 'habitacion', 'totem');

        return view('frontend.checkIns.show', compact('checkIn'));
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
}
