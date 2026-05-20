<?php

namespace App\Http\Controllers\Frontend;

use App\Models\CheckIn;
use App\Models\ConfiguracionGrabador;
use App\Models\ConfiguracionTpv;
use App\Models\ConfiguracionVideo;
use App\Models\Establecimiento;
use App\Models\Sociedad;
use App\Models\Totem;
use App\Models\User;
use Carbon\Carbon;

class HomeController
{
    public function index()
    {
        $myModel = new CheckIn();
        $usuario = User::where('id',Auth()->user()->id)->first();
		if(isset($usuario->id)){
			if($usuario->internalUser() == 1){
				return redirect('/admin');
			}
		}

        $errors = '';
        $spinner_call = '';
        if(isset($usuario->id)){
            $totem = Totem::where('id',$usuario->totem_id)->first();
            if(isset($totem->id)){
                $callerConfig = ConfiguracionVideo::where('totem_id',$totem->id)->first();
                if(isset($callerConfig->id)){
                        $caller = $callerConfig;
                }else{
                    $caller = '';
                }

                $GrabadorConfig = ConfiguracionGrabador::where('totem_id',$totem->id)->first();
                if(isset($GrabadorConfig->id)){
                    $grabador = $GrabadorConfig;
                }else{
                    $grabador = '';
                }

                $tpvConfig = ConfiguracionTpv::where('totem_id',$totem->id)->first();
                if(isset($tpvConfig->id)){
                    $tpv = $tpvConfig;
                }else{
                    $tpv = '';
                }

                
                $establecimiento = Establecimiento::where('id',$totem->establecimiento_id)->first();
                $sociedad = Sociedad::where('id',$establecimiento->sociedad_id)->first();
                
                if($totem->fuente_imagenes == 'Totem'){
                    $imagenes = $totem->imagenes;
                }else if($totem->fuente_imagenes == 'Establecimiento'){
                    
                    if(isset($establecimiento->id)){
                        $imagenes = $establecimiento->imagenes;
                    }else{
                        $errors .= 'Error: Establecimiento no encontrado - ';
                    }
                }else if($totem->fuente_imagenes == 'Sociedad'){
                    
                    if(isset($establecimiento->id)){
                        
                        if(isset($sociedad->id)){
                            $imagenes = $sociedad->imagenes;
                        }else{
                            return 'Error: Sociedad no encontrada';
                        }
                    }
                }
                if($totem->fuente_spinner == 'Totem'){
                    $spinner_call = $totem->spinner;
                }else if($totem->fuente_imagenes == 'Establecimiento'){
                    $spinner_call = ''; // pendiente
                }else if($totem->fuente_imagenes == 'Sociedad'){
                    $spinner_call = ''; // pendiente
                }
                
                $imagenes_pagina_inicial = [];

                if($totem->fuente_imagenes_pagina_1 == 'Totem'){
                    $imagenes_pagina_inicial = $totem->imagenes_pagina_inicial;
                }else if($totem->fuente_imagenes_pagina_1 == 'Establecimiento'){
                    if(isset($establecimiento->id)){
                        $imagenes_pagina_inicial = $establecimiento->imagenes_pagina_1;
                    }
                }else if($totem->fuente_imagenes_pagina_1 == 'Sociedad'){
                    if(isset($establecimiento->id)){                        
                        if(isset($sociedad->id)){
                            $imagenes_pagina_inicial = $sociedad->imagenes_pagina_1;
                        }
                    }                    
                }

            }else{
                return back();
            }
        }else{
            return 'No hay usuario conectado';
        }
        
        $fecha_actual = Carbon::now();
        $dia = $fecha_actual->format('d');
        $mes_nombre = $fecha_actual->format('F');

        $fecha = $dia . ' ' . $mes_nombre;
        //dd($establecimiento->ocultar_header_totem);
        return view('frontend.home2',compact([
            'myModel',
            'imagenes', 
            'caller',
            'grabador',
            'tpv',
            'totem',
            'imagenes_pagina_inicial',
            'spinner_call',
            'establecimiento',
            'sociedad',
            'fecha'
        ]));
    }
    

}
