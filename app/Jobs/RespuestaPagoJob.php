<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

use App\Models\RespuestaPago;
use App\Models\PagoTotem;

class RespuestaPagoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;   
    /**
     * Create a new job instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {        
                
        $respuestaPagos = RespuestaPago::where('pago_origen_id', $this->data['transaccion_id'])->first();
        if(!$respuestaPagos){
            $pagoTorem = PagoTotem::find($this->data['transaccion_id']);
            $pagoTorem->estado = 'Abortado';
            $pagoTorem->save();
        }

    }
}
