<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroySessionRequest;
use App\Models\Session;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class SessionController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('session_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Session::with(['user'])->select(sprintf('%s.*', (new Session)->table));
            $query = $query->where('user_id','!=',null);
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'session_show';
                $editGate      = 'session_edit';
                $deleteGate    = 'session_delete';
                $crudRoutePart = 'sessions';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->addColumn('user_name', function ($row) {
                return $row->user ? $row->user->name : '';
            });

            $table->editColumn('ip_address', function ($row) {
                return $row->ip_address ? $row->ip_address : '';
            });
            $table->editColumn('user_agent', function ($row) {
                return $row->user_agent ? $row->user_agent : '';
            });
            $table->editColumn('payload', function ($row) {
                return $row->payload ? $row->payload : '';
            });
            $table->editColumn('last_activity', function ($row) {
                $actualizado = date('d/m/Y H:i:s', $row->last_activity);
                return $actualizado;
            });

            $table->rawColumns(['actions', 'placeholder', 'user']);

            return $table->make(true);
        }

        $users = User::get();

        return view('admin.sessions.index', compact('users'));
    }

    public function show(Session $session)
    {
        abort_if(Gate::denies('session_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $session->load('user');

        return view('admin.sessions.show', compact('session'));
    }

    public function destroy(Session $session)
    {
        abort_if(Gate::denies('session_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $session->delete();

        return back();
    }

    public function massDestroy(MassDestroySessionRequest $request)
    {
        $sessions = Session::find(request('ids'));

        foreach ($sessions as $session) {
            $session->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
