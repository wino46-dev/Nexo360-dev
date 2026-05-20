<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyEstablecimientoRequest;
use App\Http\Requests\StoreEstablecimientoRequest;
use App\Http\Requests\UpdateEstablecimientoRequest;
use App\Models\AyudaStepTotem;
use App\Models\Establecimiento;
use App\Models\Sociedad;
use App\Services\HotelApiService;
use Gate;
use Illuminate\Http\Request;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Cache;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use App\Models\FtpUploadLog;



class EstablecimientoController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('establecimiento_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Establecimiento::with(['sociedad'])->select(sprintf('%s.*', (new Establecimiento)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'establecimiento_show';
                $editGate      = 'establecimiento_edit';
                $deleteGate    = 'establecimiento_delete';
                $crudRoutePart = 'establecimientos';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->addColumn('sociedad_codigo', function ($row) {
                return $row->sociedad ? $row->sociedad->codigo : '';
            });

            $table->editColumn('codigo', function ($row) {
                return $row->codigo ? $row->codigo : '';
            });
            $table->editColumn('nombre', function ($row) {
                return $row->nombre ? $row->nombre : '';
            });
            $table->editColumn('tour_images', function ($row) {
                if (! $row->tour_images) {
                    return '';
                }
                $links = [];
                foreach ($row->tour_images as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank"><img src="' . $media->getUrl('thumb') . '" width="50px" height="50px"></a>';
                }

                return implode(' ', $links);
            });
            $table->editColumn('actions2', function ($row) {
                return '<a href="#" class="btn btn-xs btn-primary"
                    onclick="partes_envios(' . $row->id . ')"
                    title="Configuración Envio de Partes ">Envio Partes</a>';
            });


            $table->rawColumns(['actions', 'actions2', 'placeholder', 'sociedad', 'tour_images']);

            return $table->make(true);
        }

        $sociedads = Sociedad::get();

        return view('external.establecimientos.index', compact('sociedads'));
    }

    public function create()
    {
        abort_if(Gate::denies('establecimiento_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sociedads = Sociedad::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');
        $establecimientos = Establecimiento::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $proveedorCerraduraList = Establecimiento::proveedoresCerraduras();

        $pms_list = Establecimiento::pmsList();

        return view('external.establecimientos.create', compact('sociedads', 'proveedorCerraduraList', 'pms_list'));
    }

    public function store(StoreEstablecimientoRequest $request)
    {
        $establecimiento = Establecimiento::create($request->all());

        if ($request->input('logo_establecimiento', false)) {
            $establecimiento->addMedia(storage_path('tmp/uploads/' . basename($request->input('logo_establecimiento'))))->toMediaCollection('logo_establecimiento');
        }

        foreach ($request->input('imagenes', []) as $file) {
            $establecimiento->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('imagenes');
        }

        foreach ($request->input('tour_images', []) as $file) {
            $establecimiento->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('tour_images');
        }

        foreach ($request->input('imagenes_pagina_1', []) as $file) {
            $establecimiento->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('imagenes_pagina_1');
        }

        if ($request->input('spinner', false)) {
            $establecimiento->addMedia(storage_path('tmp/uploads/' . basename($request->input('spinner'))))->toMediaCollection('spinner');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $establecimiento->id]);
        }

        return redirect()->route('external.establecimientos.index');
    }

    public function edit(Establecimiento $establecimiento)
    {
        abort_if(Gate::denies('establecimiento_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');




        $sociedads = Sociedad::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $establecimiento->load('sociedad');

        $proveedorCerraduraList = Establecimiento::proveedoresCerraduras();
        $pms_list = Establecimiento::pmsList();

        $hotel_api_tmp = new HotelApiService($establecimiento->id, true);
        $hotel_api_tmp->resetAuthCache();

        $metodos_pagos = [];
        try {
            $hotel_api = new HotelApiService($establecimiento->id, true);

            $metodos_pagos_list = $hotel_api->accountJournals();
        } catch (\Exception $e) {
        }

        if (!empty($metodos_pagos_list[0]['id'])) {
            foreach ($metodos_pagos_list as $meto) {
                $metodos_pagos[$meto['id']] = $meto['name'] . ' (' . $meto['type'] . ')';
            }
        }

        return view(
            'external.establecimientos.edit',
            compact('establecimiento', 'sociedads', 'proveedorCerraduraList', 'metodos_pagos', 'pms_list')
        );
    }

    public function update(UpdateEstablecimientoRequest $request, Establecimiento $establecimiento)
    {
        $establecimiento->update($request->all());

        if ($request->input('logo_establecimiento', false)) {
            if (! $establecimiento->logo_establecimiento || $request->input('logo_establecimiento') !== $establecimiento->logo_establecimiento->file_name) {
                if ($establecimiento->logo_establecimiento) {
                    $establecimiento->logo_establecimiento->delete();
                }
                $establecimiento->addMedia(storage_path('tmp/uploads/' . basename($request->input('logo_establecimiento'))))->toMediaCollection('logo_establecimiento');
            }
        } elseif ($establecimiento->logo_establecimiento) {
            $establecimiento->logo_establecimiento->delete();
        }

        if (count($establecimiento->imagenes) > 0) {
            foreach ($establecimiento->imagenes as $media) {
                if (! in_array($media->file_name, $request->input('imagenes', []))) {
                    $media->delete();
                }
            }
        }
        $media = $establecimiento->imagenes->pluck('file_name')->toArray();
        foreach ($request->input('imagenes', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $establecimiento->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('imagenes');
            }
        }

        if (count($establecimiento->tour_images) > 0) {
            foreach ($establecimiento->tour_images as $media) {
                if (! in_array($media->file_name, $request->input('tour_images', []))) {
                    $media->delete();
                }
            }
        }
        $media = $establecimiento->tour_images->pluck('file_name')->toArray();
        foreach ($request->input('tour_images', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $establecimiento->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('tour_images');
            }
        }

        if (count($establecimiento->imagenes_pagina_1) > 0) {
            foreach ($establecimiento->imagenes_pagina_1 as $media) {
                if (! in_array($media->file_name, $request->input('imagenes_pagina_1', []))) {
                    $media->delete();
                }
            }
        }
        $media = $establecimiento->imagenes_pagina_1->pluck('file_name')->toArray();
        foreach ($request->input('imagenes_pagina_1', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $establecimiento->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('imagenes_pagina_1');
            }
        }

        if ($request->input('spinner', false)) {
            if (! $establecimiento->spinner || $request->input('spinner') !== $establecimiento->spinner->file_name) {
                if ($establecimiento->spinner) {
                    $establecimiento->spinner->delete();
                }
                $establecimiento->addMedia(storage_path('tmp/uploads/' . basename($request->input('spinner'))))->toMediaCollection('spinner');
            }
        } elseif ($establecimiento->spinner) {
            $establecimiento->spinner->delete();
        }

        return redirect()->back()->with(['message' => 'Establecimiento actualizado correctamente']);
    }

    public function show(Establecimiento $establecimiento)
    {
        abort_if(Gate::denies('establecimiento_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $establecimiento->load('sociedad', 'establecimientoHabitacions', 'establecimientoTotems');
        $establecimientos = Establecimiento::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $ayudaStepTotem = AyudaStepTotem::where('establecimiento_id', $establecimiento->id)->first();
        if (isset($ayudaStepTotem->id)) {
            $ayudaStepTotem->load('establecimiento');
            $ayudaStepTotem;
            return view('external.establecimientos.show', compact('establecimiento', 'establecimientos', 'ayudaStepTotem'));
        } else {
            return view('external.establecimientos.show', compact('establecimiento', 'establecimientos'));
        }
    }

    public function destroy(Establecimiento $establecimiento)
    {
        abort_if(Gate::denies('establecimiento_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $establecimiento->delete();

        return back();
    }

    public function massDestroy(MassDestroyEstablecimientoRequest $request)
    {
        $establecimientos = Establecimiento::find(request('ids'));

        foreach ($establecimientos as $establecimiento) {
            $establecimiento->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('establecimiento_create') && Gate::denies('establecimiento_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Establecimiento();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }

    public function partesConfig($id)
    {

        $establecimiento = Establecimiento::find($id);

        return view('external.establecimientos._partes_form', compact('establecimiento'));
    }

    public function partesConfigSave(Request $request, $id)
    {

        $establecimiento = Establecimiento::find($id);
        $data = $request->all();
        $data['partes_ftp_data'] = trim($data['partes_ftp_data']);

        $establecimiento->update($data);

        return response()->json(['success' => 'Configuración guardada correctamente']);
    }

    public function partesFtpTest(Request $request, $id)
    {
        $establecimiento = Establecimiento::find($id);

        $data = $request->all();

        $ftpConfig = json_decode($data['ftp_data'], true);
        if (empty($ftpConfig)) {
            return response()->json(['error' => 'Configuración FTP inválida.'], 422);
        }
        /* $ftpConfig = [
            "driver" => "sftp",
            "host" => "45.84.208.135",
            "username" => "systems",
            "password" => "",
            "port" => 22,
            "root" => "/home/systems/partes_viajeros",
            "timeout" => 30
        ];*/

        if (!$ftpConfig || !isset($ftpConfig['host'])) {
            return response()->json(['error' => 'Configuración FTP inválida.'], 422);
        }


        $diskName = 'ftp_cliente_' . $establecimiento->id;
        Config::set("filesystems.disks.$diskName", $ftpConfig);

        $localPath = public_path('pdf/parte_viajero_test.pdf');

        if (!file_exists($localPath)) {
            return response()->json(['error' => 'El archivo local no existe.'], 404);
        }

        $remoteDir = 'test/';
        $remotePath = $remoteDir . 'parte_viajero_test.pdf';

        try {
            // Probar conexión
            Storage::disk($diskName)->files();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        // Asegurar directorio remoto
        try { Storage::disk($diskName)->makeDirectory($remoteDir); } catch (\Throwable $e) {}

        // Subida atómica con verificación e idempotencia
        $tmpPath = $remotePath . '.tmp';
        try {
            // Idempotencia: si existe y tamaño coincide, reportar éxito sin subir
            if (Storage::disk($diskName)->exists($remotePath)) {
                $remoteSize = Storage::disk($diskName)->size($remotePath);
                if ($remoteSize === filesize($localPath)) {
                    try {
                        $lastMod = null; try { $lastMod = Storage::disk($diskName)->lastModified($remotePath); } catch (\Throwable $e2) {}
                        FtpUploadLog::create([
                            'establecimiento_id' => $establecimiento->id,
                            'check_in_id' => null,
                            'disk' => $diskName,
                            'local_path' => $localPath,
                            'remote_path' => $remotePath,
                            'status' => 'skip',
                            'attempts' => 0,
                            'size_local' => filesize($localPath),
                            'size_remote' => $remoteSize,
                            'remote_last_modified' => $lastMod ? date('Y-m-d H:i:s', $lastMod) : null,
                            'uploaded_at' => null,
                            'message' => 'Prueba FTP: archivo ya existe con el mismo tamaño.',
                            'meta' => ['test' => true],
                        ]);
                    } catch (\Throwable $eLog) {}
                    return response()->json(['success' => 'Archivo ya existe con el mismo tamaño en el FTP.']);
                }
            }

            $attempts = 0; $maxAttempts = 2;
            while ($attempts < $maxAttempts) {
                $attempts++;
                try {
                    Storage::disk($diskName)->put($tmpPath, fopen($localPath, 'r'));
                    if (Storage::disk($diskName)->exists($remotePath)) {
                        Storage::disk($diskName)->delete($remotePath);
                    }
                    Storage::disk($diskName)->move($tmpPath, $remotePath);
                    $remoteSize = Storage::disk($diskName)->size($remotePath);
                    if ($remoteSize === filesize($localPath)) {
                        try {
                            $lastMod = null; try { $lastMod = Storage::disk($diskName)->lastModified($remotePath); } catch (\Throwable $e2) {}
                            FtpUploadLog::create([
                                'establecimiento_id' => $establecimiento->id,
                                'check_in_id' => null,
                                'disk' => $diskName,
                                'local_path' => $localPath,
                                'remote_path' => $remotePath,
                                'status' => 'success',
                                'attempts' => $attempts,
                                'size_local' => filesize($localPath),
                                'size_remote' => $remoteSize,
                                'remote_last_modified' => $lastMod ? date('Y-m-d H:i:s', $lastMod) : null,
                                'uploaded_at' => now(),
                                'message' => 'Prueba FTP: subida completada y verificada.',
                                'meta' => ['test' => true],
                            ]);
                        } catch (\Throwable $eLog) {}
                        return response()->json(['success' => 'Archivo de prueba subido exitosamente al FTP.']);
                    }
                    throw new \RuntimeException('Verificación de tamaño fallida');
                } catch (\Throwable $e) {
                    try { if (Storage::disk($diskName)->exists($tmpPath)) { Storage::disk($diskName)->delete($tmpPath); } } catch (\Throwable $te) {}
                    if ($attempts >= $maxAttempts) {
                        throw $e;
                    }
                    usleep(300000);
                }
            }
        } catch (\Exception $e) {
            try {
                FtpUploadLog::create([
                    'establecimiento_id' => $establecimiento->id,
                    'check_in_id' => null,
                    'disk' => $diskName ?? null,
                    'local_path' => $localPath ?? null,
                    'remote_path' => ($remotePath ?? ($remoteDir.'parte_viajero_test.pdf')),
                    'status' => 'fail',
                    'attempts' => $attempts ?? 0,
                    'size_local' => file_exists($localPath ?? '') ? filesize($localPath) : null,
                    'size_remote' => null,
                    'remote_last_modified' => null,
                    'uploaded_at' => null,
                    'message' => 'Prueba FTP: error al subir archivo: ' . $e->getMessage(),
                    'meta' => ['test' => true],
                ]);
            } catch (\Throwable $eLog) {}
            return response()->json(['error' => 'Error al subir archivo: ' . $e->getMessage()], 500);
        }
    }


    public function partesBackup(Request $request, $id)
    {
        $data = $request->all();
        $establecimiento = Establecimiento::find($id);

        $fecha = $data['fecha'];
        if ($establecimiento->partes_tipo_envio == 'ftp') {

            Artisan::call('app:export-ftp-partes-pdf', [
                'establecimiento_id' => $id,
                'fecha' => $fecha
            ]);
        }
        return response()->json([
            'success' => 'Backup de partes enviado exitosamente.',
            'data' => Artisan::output()
        ]);
    }
}
