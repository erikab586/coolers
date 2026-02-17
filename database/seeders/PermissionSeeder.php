<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\RolUsuario;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Definir permisos por módulo
        $permissions = [
            // Módulo Usuarios
            [
                'name' => 'ver_usuarios',
                'display_name' => 'Ver Usuarios',
                'description' => 'Permite ver la lista de usuarios',
                'module' => 'usuarios'
            ],
            [
                'name' => 'crear_usuarios',
                'display_name' => 'Crear Usuarios',
                'description' => 'Permite crear nuevos usuarios',
                'module' => 'usuarios'
            ],
            [
                'name' => 'editar_usuarios',
                'display_name' => 'Editar Usuarios',
                'description' => 'Permite editar usuarios existentes',
                'module' => 'usuarios'
            ],
            [
                'name' => 'eliminar_usuarios',
                'display_name' => 'Eliminar Usuarios',
                'description' => 'Permite eliminar usuarios',
                'module' => 'usuarios'
            ],

            // Módulo Coolers
            [
                'name' => 'ver_coolers',
                'display_name' => 'Ver Coolers',
                'description' => 'Permite ver la lista de coolers',
                'module' => 'coolers'
            ],
            [
                'name' => 'crear_coolers',
                'display_name' => 'Crear Coolers',
                'description' => 'Permite crear nuevos coolers',
                'module' => 'coolers'
            ],
            [
                'name' => 'editar_coolers',
                'display_name' => 'Editar Coolers',
                'description' => 'Permite editar coolers existentes',
                'module' => 'coolers'
            ],
            [
                'name' => 'eliminar_coolers',
                'display_name' => 'Eliminar Coolers',
                'description' => 'Permite eliminar coolers',
                'module' => 'coolers'
            ],

            // Módulo Comercializadoras
            [
                'name' => 'ver_comercializadoras',
                'display_name' => 'Ver Comercializadoras',
                'description' => 'Permite ver la lista de comercializadoras',
                'module' => 'comercializadoras'
            ],
            [
                'name' => 'crear_comercializadoras',
                'display_name' => 'Crear Comercializadoras',
                'description' => 'Permite crear nuevas comercializadoras',
                'module' => 'comercializadoras'
            ],
            [
                'name' => 'editar_comercializadoras',
                'display_name' => 'Editar Comercializadoras',
                'description' => 'Permite editar comercializadoras existentes',
                'module' => 'comercializadoras'
            ],
            [
                'name' => 'eliminar_comercializadoras',
                'display_name' => 'Eliminar Comercializadoras',
                'description' => 'Permite eliminar comercializadoras',
                'module' => 'comercializadoras'
            ],

            // Módulo Contratos
            [
                'name' => 'ver_contratos',
                'display_name' => 'Ver Contratos',
                'description' => 'Permite ver la lista de contratos',
                'module' => 'contratos'
            ],
            [
                'name' => 'crear_contratos',
                'display_name' => 'Crear Contratos',
                'description' => 'Permite crear nuevos contratos',
                'module' => 'contratos'
            ],
            [
                'name' => 'editar_contratos',
                'display_name' => 'Editar Contratos',
                'description' => 'Permite editar contratos existentes',
                'module' => 'contratos'
            ],
            [
                'name' => 'eliminar_contratos',
                'display_name' => 'Eliminar Contratos',
                'description' => 'Permite eliminar contratos',
                'module' => 'contratos'
            ],

            // Módulo Recepciones
            [
                'name' => 'ver_recepciones',
                'display_name' => 'Ver Recepciones',
                'description' => 'Permite ver la lista de recepciones',
                'module' => 'recepciones'
            ],
            [
                'name' => 'crear_recepciones',
                'display_name' => 'Crear Recepciones',
                'description' => 'Permite crear nuevas recepciones',
                'module' => 'recepciones'
            ],
            [
                'name' => 'editar_recepciones',
                'display_name' => 'Editar Recepciones',
                'description' => 'Permite editar recepciones existentes',
                'module' => 'recepciones'
            ],
            [
                'name' => 'eliminar_recepciones',
                'display_name' => 'Eliminar Recepciones',
                'description' => 'Permite eliminar recepciones',
                'module' => 'recepciones'
            ],

            // Módulo Reportes
            [
                'name' => 'ver_reportes',
                'display_name' => 'Ver Reportes',
                'description' => 'Permite ver reportes y estadísticas',
                'module' => 'reportes'
            ],
            [
                'name' => 'exportar_reportes',
                'display_name' => 'Exportar Reportes',
                'description' => 'Permite exportar reportes a Excel/PDF',
                'module' => 'reportes'
            ],

            // Módulo Dashboard
            [
                'name' => 'ver_dashboard',
                'display_name' => 'Ver Dashboard',
                'description' => 'Permite acceder al dashboard principal',
                'module' => 'dashboard'
            ],
        ];

        // Crear permisos
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }

        // Asignar todos los permisos al rol de Administrador (id = 1)
        $adminRole = RolUsuario::find(1);
        if ($adminRole) {
            $allPermissions = Permission::all();
            $adminRole->permissions()->sync($allPermissions->pluck('id'));
            $this->command->info('✅ Todos los permisos asignados al rol Administrador');
        }

        $this->command->info('✅ Permisos creados exitosamente');
    }
}
