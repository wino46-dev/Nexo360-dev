<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreConfiguracionVideoRequest;
use App\Http\Requests\UpdateConfiguracionVideoRequest;
use App\Models\ConfiguracionVideo;
use App\Models\Team;
use App\Models\Totem;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ConfiguracionVideoController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('configuracion_video_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = ConfiguracionVideo::with(['totem'])->select(sprintf('%s.*', (new ConfiguracionVideo)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'configuracion_video_show';
                $editGate      = 'configuracion_video_edit';
                $deleteGate    = 'configuracion_video_delete';
                $crudRoutePart = 'configuracion-videos';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->addColumn('totem_codigo', function ($row) {
                return $row->totem ? $row->totem->codigo : '';
            });

            $table->editColumn('sip_identity', function ($row) {
                return $row->sip_identity ? $row->sip_identity : '';
            });
            $table->editColumn('display_name', function ($row) {
                return $row->display_name ? $row->display_name : '';
            });
            $table->editColumn('sip_registar', function ($row) {
                return $row->sip_registar ? $row->sip_registar : '';
            });
            $table->editColumn('sip_identity_destino', function ($row) {
                return $row->sip_identity_destino ? $row->sip_identity_destino : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'totem']);

            return $table->make(true);
        }

        $totems = Totem::get();
        $teams  = Team::get();

        return view('admin.configuracionVideos.index', compact('totems', 'teams'));
    }

    public function create()
    {
        return back();
        abort_if(Gate::denies('configuracion_video_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $totems = Totem::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.configuracionVideos.create', compact('totems'));
    }

    public function store(StoreConfiguracionVideoRequest $request)
    {
        $configuracionVideo = ConfiguracionVideo::create($request->all());
        return  back();

        return redirect()->route('admin.configuracion-videos.index');
    }

    public function edit(ConfiguracionVideo $configuracionVideo)
    {
        return back();
        abort_if(Gate::denies('configuracion_video_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $totems = Totem::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $configuracionVideo->load('totem');

        return view('admin.configuracionVideos.edit', compact('configuracionVideo', 'totems'));
    }

    public function update(UpdateConfiguracionVideoRequest $request, ConfiguracionVideo $configuracionVideo)
    {
        $configuracionVideo->update($request->all());
        return  back();

        return redirect()->route('admin.configuracion-videos.index');
    }

    public function show(ConfiguracionVideo $configuracionVideo)
    {
        abort_if(Gate::denies('configuracion_video_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $configuracionVideo->load('totem');

        return view('admin.configuracionVideos.show', compact('configuracionVideo'));
    }
}
