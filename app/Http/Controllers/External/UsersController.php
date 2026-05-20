<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Role;
use App\Models\Team;
use App\Models\Totem;
use App\Models\User;
use App\Models\Sociedad;
use App\Models\Establecimiento;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {

            $query = User::select('users.id', 'users.name', 'users.email', 'totems.codigo as totem_id','users.sip_identity')
                ->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
                ->leftJoin('totems', 'users.totem_id', '=', 'totems.id')

                ->groupBy('users.id', 'users.name', 'users.email', 'totems.codigo', 'users.sip_identity');

            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'user_show';
                $editGate      = 'user_edit';
                $deleteGate    = 'user_delete';
                $crudRoutePart = 'users';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('email', function ($row) {
                return $row->email ? $row->email : '';
            });
            $table->editColumn('login_pms_establecimiento', function ($row) {
                if(!empty($row->login_pms_establecimiento)){
                    return '<div class="text-center">
                        <i class="fa fa-check" aria-hidden="true"></i>
                        </div>';
                }
            });

            $table->editColumn('two_factor', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->two_factor ? 'checked' : null) . '>';
            });
            $table->editColumn('roles', function ($row) {
                $labels = [];
                foreach ($row->roles as $role) {
                    $labels[] = sprintf('<span class="badge badge-info">%s</span>', $role->title);
                }
                return implode(' ', $labels);
            });

            $table->filterColumn('roles', function($query, $keyword) {
                $query->where('role_user.role_id', $keyword);
            });
            $table->filterColumn('totem_id', function($query, $keyword) {
                $query->where('users.totem_id', $keyword);
            });

            $table->rawColumns(['actions', 'placeholder',  'roles', 'totem_id','login_pms_establecimiento']);

            return $table->make(true);
        }

        $roles  = Role::get();
        $totems = Totem::get();
        $teams  = Team::get();

        return view('external.users.index', compact('roles', 'totems', 'teams'));
    }

    public function create()
    {
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::pluck('title', 'id');

        $users_totem_active = User::where('totem_id', '>', 0);
        $totem_actives = $users_totem_active->get()->pluck('totem_id');

        $totems = Totem::whereNotIn('id', $totem_actives)->pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');



        return view('external.users.create', compact('roles',  'totems'));
    }

    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->all());
        $user->roles()->sync($request->input('roles', []));

        return redirect()->route('external.users.index');
    }

    public function edit(User $user)
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::pluck('title', 'id');

        $users_totem_active = User::where('totem_id', '>', 0);
        if(!empty($user->totem_id)){
            $users_totem_active->where('totem_id','<>', $user->totem_id);
        }
        $totem_actives = $users_totem_active->get()->pluck('totem_id');

        $totems = Totem::whereNotIn('id', $totem_actives)->pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $user->load('roles', 'totem');

        return view('external.users.edit', compact('roles',  'totems', 'user'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->all());
        $user->roles()->sync($request->input('roles', []));

        return redirect()->route('external.users.index');
    }

    public function show(User $user)
    {
        abort_if(Gate::denies('user_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->load('roles', 'totem');

        return view('external.users.show', compact('user'));
    }

    public function destroy(User $user)
    {
        abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->delete();

        $user->email = $user->email .'_delete_'.time();

        $user->save();

        return back();
    }

    public function massDestroy(MassDestroyUserRequest $request)
    {
        $users = User::find(request('ids'));

        foreach ($users as $user) {
            $user->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    // ===== User access to Societies and Hotels =====
    public function accessSelect(Request $request)
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $users = User::orderBy('name')->get(['id','name']);
        return view('external.users.access_select', compact('users'));
    }

    public function access(User $user)
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $societies = Sociedad::orderBy('nombre')->pluck('nombre','id');
        $hotels = Establecimiento::orderBy('nombre')->get(['id','nombre','sociedad_id']);

        $user->load(['societies','establecimientos']);
        $selectedSocieties = $user->societies->pluck('id')->toArray();
        $selectedHotels = $user->establecimientos->pluck('id')->toArray();

        return view('external.users.access', compact('user','societies','hotels','selectedSocieties','selectedHotels'));
    }

    public function accessUpdate(Request $request, User $user)
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $data = $request->validate([
            'societies' => ['array'],
            'societies.*' => ['integer','exists:sociedads,id'],
            'hotels' => ['array'],
            'hotels.*' => ['integer','exists:establecimientos,id'],
        ]);

        $societyIds = collect($data['societies'] ?? [])->unique()->values()->all();
        $user->societies()->sync($societyIds);

        // Allowed hotels are those whose sociedad_id is in $societyIds
        $allowedHotelIds = Establecimiento::whereIn('sociedad_id', $societyIds)->pluck('id')->toArray();
        $requestedHotelIds = collect($data['hotels'] ?? [])->intersect($allowedHotelIds)->unique()->values()->all();

        $user->establecimientos()->sync($requestedHotelIds);

        return redirect()->route('external.users.access', $user->id)->with('status', 'Accesos actualizados correctamente');
    }
}
