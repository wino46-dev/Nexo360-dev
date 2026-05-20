<?php 
$fecha = explode(" ", $pago->fecha_operacion);

$horaCompleta = $fecha[1]; 

$patronHora = '/^([01]\d|2[0-3]):([0-5]\d):([0-5]\d)(\.\d+)?$/';

$horaFormateada = '';
if (preg_match($patronHora, $horaCompleta)) {    
    $horaFormateada = date('H:i', strtotime($horaCompleta));
 
}

$comercio = $pago->comercio;
if (strlen($comercio) > 4) {
    // Reemplazar los últimos 4 dígitos por asteriscos
    $comercioEnmascarado = substr($comercio, 0, -4) . '****';
} else {
    // Si tiene 4 o menos dígitos, enmascarar todo
    $comercioEnmascarado = '****';
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Recibo</title>
</head>

<body>
    <style>
        .container {
            font-family: 'Verdana', sans-serif;
        }

        .text-center {
            text-align: center;
        }
        .text-left {
            text-align: left;
        }
        .parte td {
            height: 30px
        }
        .mt-1{
            margin-top:5px;
        }
        .mt-2{
            margin-top:10px;
        }
        .mt-3{
            margin-top:15px;
        }
        .mt-4{
            margin-top:20px;
        }
        .alert{
            border:1px solid #d6d8db;
            background-color: #e2e3e5;
            color:#383d41;
            border-radius: .25rem;
            padding:5px;
        }
    </style>
    <div class="container">
        <div class="text-center">
            <img src="{{ $establecimiento->logo_establecimiento ? $establecimiento->logo_establecimiento->getUrl() : '' }}"
                class="img-fluid" style="max-height:110px" />
            <h2 style="margin-top:5px">PAGO AUTORIZADO</h2>              
            @if ($pago->oper_contact_less == 'true')
                <div class="text-center" style="padding: 0px 5px 5px 0px">
                    <img src="{{ url('/img/contactless.png') }}" class="img-fluid" style="max-height:50px" />
                </div>
            @endif

            <div>
                {{ $establecimiento->nombre }} - {{ $establecimiento->ciudad }}
            </div>           
        </div>
        <div class="text-left mt-2" >
            &nbsp;No. TRANSACCIÓN: <b>{{ $pago->conttrans }}</b>
        </div>
        <div class="text-left mt-1">
            <table style="width:100%">
                <tr>
                    <!-- <td style="width:50%">
                        COMERCIO: {{ $comercioEnmascarado }}
                    </td> -->
                    <td >
                        AID: {{ $pago->idapp }} 
                    </td>
                </tr>
            </table>           
        </div>
        <div class="text-left mt-1">
            <table style="width:100%">
                <tr>
                    <td style="width:50%">
                        TPV: {{ $pago->terminal }}
                    </td>
                    <td >
                        ARC: {{ $pago->codrespauto }}
                    </td>
                </tr>
            </table>            
        </div>
        <div class="text-center mt-1">
            <span style="font-size:120%">{{ $pago->tarjeta_cliente_recibo }} </span>
        </div>
        <div class="text-left mt-1">
            {{ $pago->etiqueta_app }} 
        </div>

        <div class="text-left mt-4">
            <b>VENTA</b>
        </div>
        <div class="mt-1">
            <table style="width:100%" border="0" cellspancing="0">
                <tr>
                    <td style="width:50%">
                        <div class="text-center">Aut: {{ $pago->codigo_respuesta }}</div>
                        <div class="text-center">Fecha: {{ $fecha[0] ?? '' }} </div>
                    </td>
                    <td>
                        <div class="text-center">Ped: {{ $pago->pedido }} </div>
                        <div class="text-center">Hora: {{ $horaFormateada }}</div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="text-center mt-3">
            TRANSACTION CURRENCY
        </div>
        <div class="text-center mt-1">
            <b><span style="font-size:130%" >{{ $pago->importe }} &nbsp;&nbsp;&nbsp; EUR</span> </b>
        </div>
        <!-- <div class="text-center mt-2 mb-2 ">
            <div class="alert alert-secondary p-1" >
                {{ $pago->literales }}                
            </div>
        </div> -->
        <div class="text-center">
            <img src="{{ url('/img/redsys-pago-seguro-300x85-1.png') }}" class="img-fluid" style="max-height:110px" />
        </div>
        <div class="mt-4">            
            {!! $sociedad->pago_html_inferior !!}
        </div>
        



    </div>
</body>

</html>
