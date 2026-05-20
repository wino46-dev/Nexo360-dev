<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyControlErrorRequest;
use App\Http\Requests\StoreControlErrorRequest;
use App\Models\ControlError;
use App\Models\Establecimiento;
use App\Models\Team;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;

class ControlErrorController extends Controller
{
    public function index(Request $request)
    {

        abort_if(Gate::denies('control_error_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            //$query = ControlError::select(sprintf('%s.*', (new ControlError)->table));
            $query = ControlError::select('control_errors.*')
                ->leftJoin('users', 'users.id', '=', 'control_errors.user_id');

            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'control_error_show';
                $editGate      = 'control_error_edit';
                $deleteGate    = 'control_error_delete';
                $crudRoutePart = 'control-errors';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('origen', function ($row) {
                return $row->origen ? $row->origen : '';
            });
            $table->editColumn('tipo', function ($row) {
                return $row->tipo ? $row->tipo : '';
            });
            $table->editColumn('mensaje', function ($row) {
                return $row->mensaje ? $row->mensaje : '';
            });
            $table->editColumn('user_id', function ($row) {
                //Log::info($row);
                return $row->user?->name;
            });
            $table->editColumn('establecimiento_id', function ($row) {
                return $row->establecimiento?->nombre;
            });
            $table->filterColumn('user_id', function ($query, $keyword) {
                $query->where('users.name', 'like', '%' . $keyword . '%');
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        $teams = Team::get();
        $establecimientos  = Establecimiento::get();

        return view('external.controlErrors.index', compact('teams', 'establecimientos'));
    }

    public function create()
    {
        abort_if(Gate::denies('control_error_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('external.controlErrors.create');
    }

    public function store(StoreControlErrorRequest $request)
    {
        $controlError = ControlError::create($request->all());

        return redirect()->route('external.control-errors.index');
    }

    public function show(ControlError $controlError)
    {
        abort_if(Gate::denies('control_error_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');



        return view('external.controlErrors.show', compact('controlError'));
    }

    public function destroy(ControlError $controlError)
    {
        abort_if(Gate::denies('control_error_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $controlError->delete();

        return back();
    }

    public function massDestroy(MassDestroyControlErrorRequest $request)
    {
        $controlErrors = ControlError::find(request('ids'));

        foreach ($controlErrors as $controlError) {
            $controlError->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
