<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FtpUploadLog;
use App\Models\Establecimiento;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class FtpUploadLogController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('ftp_upload_log_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = FtpUploadLog::with(['establecimiento'])->select(sprintf('%s.*', (new FtpUploadLog)->getTable()));

            // Apply filters
            if ($request->filled('establecimiento_id')) {
                $query->where('establecimiento_id', $request->input('establecimiento_id'));
            }
            if ($request->filled('check_in_id')) {
                $query->where('check_in_id', $request->input('check_in_id'));
            }
            if ($request->filled('status')) {
                $query->where('status', $request->input('status'));
            }
            if ($request->filled('disk')) {
                $query->where('disk', 'like', '%' . $request->input('disk') . '%');
            }
            if ($request->filled('remote_path')) {
                $query->where('remote_path', 'like', '%' . $request->input('remote_path') . '%');
            }
            if ($request->filled('local_path')) {
                $query->where('local_path', 'like', '%' . $request->input('local_path') . '%');
            }
            if ($request->filled('message')) {
                $query->where('message', 'like', '%' . $request->input('message') . '%');
            }
            // Date range by created_at or uploaded_at
            if ($request->filled('date_start') || $request->filled('date_end')) {
                $start = $request->input('date_start');
                $end = $request->input('date_end');
                $field = $request->input('date_field', 'created_at'); // 'created_at' or 'uploaded_at'
                if ($start && $end) {
                    $query->whereBetween($field, [$start, $end]);
                } elseif ($start) {
                    $query->where($field, '>=', $start);
                } elseif ($end) {
                    $query->where($field, '<=', $end);
                }
            }

            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'ftp_upload_log_show';
                $editGate      = null; // not used
                $deleteGate    = null; // not used
                $crudRoutePart = 'ftp-upload-logs';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->addColumn('establecimiento_nombre', function ($row) {
                return optional($row->establecimiento)->nombre;
            });

            $table->editColumn('status', function ($row) {
                return $row->status;
            });

            $table->editColumn('remote_path', function ($row) {
                return $row->remote_path;
            });

            $table->editColumn('disk', function ($row) {
                return $row->disk;
            });

            $table->editColumn('attempts', function ($row) {
                return $row->attempts;
            });

            $table->editColumn('size_local', function ($row) {
                return $row->size_local;
            });
            $table->editColumn('size_remote', function ($row) {
                return $row->size_remote;
            });

            $table->editColumn('uploaded_at', function ($row) {
                return optional($row->uploaded_at)->format('Y-m-d H:i:s');
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        $establecimientos = Establecimiento::orderBy('nombre')->pluck('nombre', 'id');
        return view('admin.ftpUploadLogs.index', compact('establecimientos'));
    }

    public function show(FtpUploadLog $ftpUploadLog)
    {
        abort_if(Gate::denies('ftp_upload_log_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $ftpUploadLog->load('establecimiento', 'checkIn');
        return view('admin.ftpUploadLogs.show', compact('ftpUploadLog'));
    }
}
