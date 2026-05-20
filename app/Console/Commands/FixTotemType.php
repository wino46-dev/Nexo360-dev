<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class FixTotemType extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sh360:fix-totem-type {--dry-run : Only show how many users would be updated}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Establece type=totem a usuarios con rol Totem que no tengan rol Admin y cuyo type sea nulo o incorrecto';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Buscando usuarios con rol Totem y type incorrecto...');

        $query = User::query()
            ->whereNull('deleted_at')
            ->whereHas('roles', function ($q) {
                $q->whereRaw('LOWER(title) = ?', ['totem']);
            })
            ->whereDoesntHave('roles', function ($q) {
                $q->where('id', 1)->orWhereRaw('LOWER(title) = ?', ['admin']);
            })
            ->where(function ($q) {
                $q->whereNull('type')
                  ->orWhereRaw('LOWER(type) <> ?', ['totem']);
            });

        $count = (clone $query)->count();
        $this->info("Usuarios a corregir: {$count}");

        if ($count === 0) {
            return Command::SUCCESS;
        }

        if ($this->option('dry-run')) {
            $this->line('Opción --dry-run activada, no se realizan cambios.');
            return Command::SUCCESS;
        }

        $updated = 0;
        DB::beginTransaction();
        try {
            $query->chunkById(200, function ($users) use (&$updated) {
                foreach ($users as $user) {
                    $user->type = User::TYPE_TOTEM;
                    $user->save();
                    $updated++;
                }
            }, $column = 'id');
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->error('Error actualizando usuarios: ' . $e->getMessage());
            return Command::FAILURE;
        }

        $this->info("Usuarios actualizados: {$updated}");
        return Command::SUCCESS;
    }
}
