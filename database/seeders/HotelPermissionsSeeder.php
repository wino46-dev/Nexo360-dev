<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HotelPermissionsSeeder extends Seeder
{
    public function run()
    {
        $permissionsToAdd = [
            'doc_hotel_reception_info_create',
            'doc_hotel_reception_info_edit',
            'doc_hotel_reception_info_show',
            'doc_hotel_reception_info_delete',
            'doc_hotel_reception_info_access',
            'doc_hotel_estado_caja_create',
            'doc_hotel_estado_caja_edit',
            'doc_hotel_estado_caja_show',
            'doc_hotel_estado_caja_delete',
            'doc_hotel_estado_caja_access',
            'doc_info_hotel_create',
            'doc_info_hotel_edit',
            'doc_info_hotel_show',
            'doc_info_hotel_delete',
            'doc_info_hotel_access',
            'doc_servicio_hotel_create',
            'doc_servicio_hotel_edit',
            'doc_servicio_hotel_show',
            'doc_servicio_hotel_delete',
            'doc_servicio_hotel_access',
            'doc_metodo_pago_hotel_create',
            'doc_metodo_pago_hotel_edit',
            'doc_metodo_pago_hotel_show',
            'doc_metodo_pago_hotel_delete',
            'doc_metodo_pago_hotel_access',
            'doc_ubicacion_hotel_create',
            'doc_ubicacion_hotel_edit',
            'doc_ubicacion_hotel_show',
            'doc_ubicacion_hotel_delete',
            'doc_ubicacion_hotel_access',
            'dock_stock_hotel_create',
            'dock_stock_hotel_edit',
            'dock_stock_hotel_show',
            'dock_stock_hotel_delete',
            'dock_stock_hotel_access',
            'doc_incidencia_hotel_create',
            'doc_incidencia_hotel_edit',
            'doc_incidencia_hotel_show',
            'doc_incidencia_hotel_delete',
            'doc_incidencia_hotel_access',
            'doc_no_deseado_hotel_create',
            'doc_no_deseado_hotel_edit',
            'doc_no_deseado_hotel_show',
            'doc_no_deseado_hotel_delete',
            'doc_no_deseado_hotel_access',
            'doc_habitacion_hotel_create',
            'doc_habitacion_hotel_edit',
            'doc_habitacion_hotel_show',
            'doc_habitacion_hotel_delete',
            'doc_habitacion_hotel_access',
            'doc_tarifa_hotel_create',
            'doc_tarifa_hotel_edit',
            'doc_tarifa_hotel_show',
            'doc_tarifa_hotel_delete',
            'doc_tarifa_hotel_access',
            'wiki_hotel_access',
        ];

        // 1) Eliminar duplicados por title (mantiene el más antiguo)
        $duplicatedIds = DB::table('permissions')
            ->select('id')
            ->whereIn('title', function ($q) {
                $q->select('title')
                    ->from('permissions')
                    ->groupBy('title')
                    ->having(DB::raw('count(*)'), '>', 1);
            })
            ->whereNotIn('id', function ($q) {
                $q->select(DB::raw('min(id)'))
                    ->from('permissions')
                    ->groupBy('title');
            })
            ->pluck('id');

        if ($duplicatedIds->isNotEmpty()) {
            DB::table('permissions')->whereIn('id', $duplicatedIds)->delete();
        }

        // 2) Insertar permisos si no existen (solo por title)
        foreach ($permissionsToAdd as $title) {
            $exists = Permission::where('title', $title)->exists();
            if (!$exists) {
                $perm = new Permission();
                $perm->title = $title;
                $perm->save();
            }
        }

        // 3) Obtener/crear rol adminHotel (roles.title o roles.name)
        $roleId = DB::table('roles')
            ->where(function ($q) {
                $q->where('title', 'adminHotel');
                if (Schema::hasColumn('roles', 'name')) {
                    $q->orWhere('name', 'adminHotel');
                }
            })
            ->value('id');

        if (!$roleId) {
            $data = ['created_at' => now(), 'updated_at' => now()];
            if (Schema::hasColumn('roles', 'title')) {
                $data['title'] = 'adminHotel';
            } elseif (Schema::hasColumn('roles', 'name')) {
                $data['name'] = 'adminHotel';
            } else {
                // Si ninguna columna existe, creamos con 'title' por defecto
                $data['title'] = 'adminHotel';
            }
            $roleId = DB::table('roles')->insertGetId($data);
        }

        // 4) Asignar SOLO estos permisos al rol adminHotel (evitando duplicados)
        $permissionIds = Permission::whereIn('title', $permissionsToAdd)->pluck('id');

        foreach ($permissionIds as $pid) {
            $exists = DB::table('permission_role')
                ->where('role_id', $roleId)
                ->where('permission_id', $pid)
                ->exists();

            if (!$exists) {
                DB::table('permission_role')->insert([
                    'role_id'       => $roleId,
                    'permission_id' => $pid,
                ]);
            }
        }
    }
}
