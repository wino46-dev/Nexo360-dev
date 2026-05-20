<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;
use App\Models\Establecimiento;
use App\Models\EventoHomeTotem;




class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $establecimientos = Establecimiento::where('partes_tipo_envio', 'ftp')->get();

        foreach ($establecimientos as $establecimiento) {

            if (!empty($establecimiento->partes_hora_envio)) {
                $hora = date('H:i', strtotime($establecimiento->partes_hora_envio));

                $schedule->command('app:export-ftp-partes-pdf ' . $establecimiento->id . ' ' . date('Y-m-d'))
                    //->everyMinute();
                    ->dailyAt($hora);
            }
        }

        $schedule->call(function () {
            $deleted = EventoHomeTotem::where('created_at', '<', now()->subDays(7))
                ->forceDelete();
        })->dailyAt('00:00');

        $schedule->command('telescope:prune --hours=12')->dailyAt('01:00');

        // $schedule->command('inspire')->hourly();
        $schedule->command('queue:restart')->twiceDaily(6, 18);
        $schedule->command('queue:work --timeout=1000')->everyMinute()->withoutOverlapping();
        // Fix type for Totem users without Admin role
        $schedule->command('sh360:fix-totem-type')->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
