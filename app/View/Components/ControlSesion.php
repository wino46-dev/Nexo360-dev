<?php

namespace App\View\Components;

use App\Models\Establecimiento;
use App\Models\User;
use Closure;
use Gate;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ControlSesion as ControlSesionModel;
use Yajra\DataTables\Facades\DataTables;
class ControlSesion extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        // Do not render the session control component for AJAX sub-requests
        // to avoid interfering with AJAX-loaded modals and partials (e.g., Landing Wiki Hotel)
        if (request()->ajax()) {
            return '';
        }

        if (Gate::denies('control_sesion_create')) {
            // Do not block the whole page; just hide this optional widget.
            return '';
        }
        $emisors = auth()->user()->id;

        $current_user_sesion = ControlSesionModel::where('estado_sesion',1)->where('emisor_id',$emisors)->with(['receptor'])->first();
        $current_user_sesion_precount = ControlSesionModel::where('estado_sesion',1)->where('emisor_id',$emisors)->with(['receptor'])->get();
        $current_user_count = $current_user_sesion_precount->count();
        $active_sesions = ControlSesionModel::where('estado_sesion',1)->with(['emisor'])->get();
        $id_sesions = [];
        $valor_string = '';
        $usuarioTotem = null;
        if(isset($current_user_sesion->receptor_id)){
            $usuarioTotem = User::where('id',$current_user_sesion->receptor_id)->with(['totem'])->first();
        }

        if(isset($usuarioTotem->totem->id)){
            $establecimiento = Establecimiento::where('id',$usuarioTotem->totem->establecimiento_id)->first();
            if(isset($establecimiento->id)){
                $valor_string .= 'Establecimiento ('.$establecimiento->api_pms.'):  '.$establecimiento->nombre;
                $valor_string .= ' - Totem :  '.$usuarioTotem->totem->codigo;
            }
        }

        foreach ($active_sesions as $sesion_obj){
            $id_sesions[] = $sesion_obj->receptor_id;
        }

        $active_receptors_sesions = \App\Models\ControlSesion::where('estado_sesion',1)->get();
        $array_users_en_sesion = [];
        foreach ($active_receptors_sesions as $activos){
            if(!in_array($activos->receptor_id, $array_users_en_sesion, true)){
                $array_users_en_sesion[] = $activos->receptor_id;
            }
        }

        $pms_list = Establecimiento::pmsList();

        $receptorsQuery = User::select('users.id as user','totems.codigo as totem','establecimientos.nombre as establecimiento', 'establecimientos.id as establecimiento_id')
            ->join('totems','totems.id','=','users.totem_id')
            ->join('establecimientos','establecimientos.id','totems.establecimiento_id')
            ->where('users.id','!=',auth()->user()->id)
            // Allow concurrent sessions for the same totem (receptor)
            // ->whereNotIn('users.id',$array_users_en_sesion)
            ->where('users.totem_id','!=',null)
            ->whereIn('establecimientos.api_pms',$pms_list);

        // If external scope middleware provided allowed hotels, restrict the receptors list to those hoteles
        $allowed = config('external.allowed_hotels');
        if (is_array($allowed)) {
            $ids = count($allowed) ? $allowed : [0];
            $receptorsQuery->whereIn('establecimientos.id', $ids);
        }

        $receptors = $receptorsQuery->orderBy('establecimientos.nombre', 'asc')->get();

        // Compute panel prefix and whether to show component (hide on reservas.index)
        $panelPrefix = request()->routeIs('external.*') ? 'external' : 'admin';
        $showComponent = !request()->routeIs($panelPrefix . '.reservas.index');

        return view('components.control-sesion',compact('emisors', 'receptors', 'id_sesions','active_sesions','current_user_sesion','current_user_count','valor_string', 'panelPrefix', 'showComponent'));
    }
}
