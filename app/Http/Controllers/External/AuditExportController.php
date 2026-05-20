<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use App\Jobs\ExportAuditLogsJob;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuditExportController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('auditorium_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $defaultEmail = auth()->user()->email ?? '';

        // Available datasets for export (key => label)
        $datasets = [
            'audit-logs' => 'Audit Logs',
            'control-errors' => 'Control Errors',
            'control-sesion' => 'Control Sesión',
            'evento-home-totem' => 'Eventos Home Totem',
            'grabacion-tarjeta' => 'Grabación Tarjeta',
            'respuesta-pago' => 'Respuesta Pago',
            'pago-totem' => 'Pago Totem',
        ];

        return view('external.auditExport.index', compact('defaultEmail', 'datasets'));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('auditorium_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $validated = $request->validate([
            'date_start' => ['required', 'date'],
            'date_end'   => ['required', 'date', 'after_or_equal:date_start'],
            'dataset'    => ['required', 'in:audit-logs,control-errors,control-sesion,evento-home-totem,grabacion-tarjeta,respuesta-pago,pago-totem'],
            'email'      => ['required', 'email'],
        ]);

        ExportAuditLogsJob::dispatch(
            $validated['dataset'],
            $validated['date_start'],
            $validated['date_end'],
            $validated['email'],
            auth()->id()
        );

        return back()->with(['message' => 'La exportación se ha iniciado. Recibirás un correo con el Excel adjunto cuando finalice.']);
    }
}
