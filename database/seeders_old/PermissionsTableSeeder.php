<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            'user_management_access',
            'permission_create',
            'permission_edit',
            'permission_show',
            'permission_delete',
            'permission_access',
            'role_create',
            'role_edit',
            'role_show',
            'role_delete',
            'role_access',
            'user_create',
            'user_edit',
            'user_show',
            'user_delete',
            'user_access',
            'configuracion_access',
            'audit_log_show',
            'audit_log_access',
            'organizacion_access',
            'establecimiento_create',
            'establecimiento_edit',
            'establecimiento_show',
            'establecimiento_delete',
            'establecimiento_access',
            'sociedad_create',
            'sociedad_edit',
            'sociedad_show',
            'sociedad_delete',
            'sociedad_access',
            'habitacion_create',
            'habitacion_edit',
            'habitacion_show',
            'habitacion_delete',
            'habitacion_access',
            'totem_create',
            'totem_edit',
            'totem_show',
            'totem_delete',
            'totem_access',
            'evento_home_totem_create',
            'evento_home_totem_edit',
            'evento_home_totem_show',
            'evento_home_totem_delete',
            'evento_home_totem_access',
            'auditorium_access',
            'control_error_create',
            'control_error_show',
            'control_error_delete',
            'control_error_access',
            'layout_home_create',
            'layout_home_edit',
            'layout_home_show',
            'layout_home_delete',
            'layout_home_access',
            'gestion_reserva_access',
            'cliente_create',
            'cliente_edit',
            'cliente_show',
            'cliente_delete',
            'cliente_access',
            'pai_create',
            'pai_edit',
            'pai_show',
            'pai_delete',
            'pai_access',
            'provincium_create',
            'provincium_edit',
            'provincium_show',
            'provincium_delete',
            'provincium_access',
            'ciudad_create',
            'ciudad_edit',
            'ciudad_show',
            'ciudad_delete',
            'ciudad_access',
            'reserva_create',
            'reserva_edit',
            'reserva_show',
            'reserva_delete',
            'reserva_access',
            'check_in_create',
            'check_in_edit',
            'check_in_show',
            'check_in_delete',
            'check_in_access',
            'tipo_evento_create',
            'tipo_evento_edit',
            'tipo_evento_show',
            'tipo_evento_delete',
            'tipo_evento_access',
            'control_sesion_create',
            'control_sesion_edit',
            'control_sesion_show',
            'control_sesion_delete',
            'control_sesion_access',
            'configuracion_tpv_create',
            'configuracion_tpv_edit',
            'configuracion_tpv_show',
            'configuracion_tpv_delete',
            'configuracion_tpv_access',
            'pago_totem_create',
            'pago_totem_edit',
            'pago_totem_show',
            'pago_totem_delete',
            'pago_totem_access',
            'session_show',
            'session_delete',
            'session_access',
            'respuesta_pago_create',
            'respuesta_pago_edit',
            'respuesta_pago_show',
            'respuesta_pago_delete',
            'respuesta_pago_access',
            'grabacion_tarjetum_create',
            'grabacion_tarjetum_edit',
            'grabacion_tarjetum_show',
            'grabacion_tarjetum_delete',
            'grabacion_tarjetum_access',
            'configuracion_grabador_create',
            'configuracion_grabador_edit',
            'configuracion_grabador_show',
            'configuracion_grabador_delete',
            'configuracion_grabador_access',
            'configuracion_video_create',
            'configuracion_video_edit',
            'configuracion_video_show',
            'configuracion_video_access',
            'firma_check_in_create',
            'firma_check_in_edit',
            'firma_check_in_show',
            'firma_check_in_delete',
            'firma_check_in_access',
            'ayuda_step_totem_create',
            'ayuda_step_totem_edit',
            'ayuda_step_totem_show',
            'ayuda_step_totem_delete',
            'ayuda_step_totem_access',
            'parte_viajero_create',
            'parte_viajero_edit',
            'parte_viajero_show',
            'parte_viajero_delete',
            'parte_viajero_access',
            'zona_comun_create',
            'zona_comun_edit',
            'zona_comun_show',
            'zona_comun_delete',
            'zona_comun_access',
            'profile_password_edit',

            'room_create',
            'room_edit',
            'room_show',
            'room_delete',
            'room_access',            
            
            'room_type_create',
            'room_type_edit',
            'room_type_show',
            'room_type_delete',
            'room_type_access',            

            'room_type_price_create',
            'room_type_price_edit',
            'room_type_price_show',
            'room_type_price_delete',
            'room_type_price_access',

        ];
        

        // Encontrar IDs de registros duplicados (excepto el más antiguo)
        $duplicatedIds = DB::table('permissions')
            ->select('id')
            ->whereIn('title', function ($query) {
                $query->select('title')
                    ->from('permissions')
                    ->groupBy('title')
                    ->having(DB::raw('count(*)'), '>', 1);
            })
            ->whereNotIn('id', function ($query) {
                $query->select(DB::raw('min(id)'))
                    ->from('permissions')
                    ->groupBy('title');
            })
            ->pluck('id');

        // Eliminar los registros duplicados
        DB::table('permissions')->whereIn('id', $duplicatedIds)->delete();

        foreach ($permissions as $permiso) {

            $permission = Permission::where([
                'title' => $permiso
            ])->first();

            if (!$permission) {                
                $perNew = new Permission();                
                $perNew->title = $permiso;
                $perNew->save();
            }
        }


        $permissions = Permission::select('id')->get();
        foreach ($permissions as $per) {
           
            $exists = DB::table('permission_role')
                ->where('role_id', 1)
                ->where('permission_id', $per->id)
                ->exists();
            if (!$exists) {                

                DB::table('permission_role')->insert([
                    'role_id' => 1,
                    'permission_id' => $per->id
                ]);
            }
        }
    }
}
