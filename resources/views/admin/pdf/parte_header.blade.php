<?php
use Illuminate\Support\Facades\Log;
use App\Services\HotelApiService;
use App\Models\CheckIn;


$services = json_decode($reservation['services'] ?? '[]', true);
$services_names = array_column($services, 'name');

while (count($services_names) < 3) {
    $services_names[] = 'NO';
}

$services_total = count($services);

$services = [
    'service_extra_1' => 'NO',
    'service_extra_2' => 'NO',
    'service_extra_3' => 'NO',
];

for ($i = 1; $i <= $services_total && $i <= count($services); $i++) {
    $services["service_extra_$i"] = 'SI';
}

$service_extra_1 = $services['service_extra_1'];
$service_extra_2 = $services['service_extra_2'];
$service_extra_3 = $services['service_extra_3'];

$accept_personal_data_value = 'NO';
if (!empty($checkin['accept_personal_data'])) {
    $accept_personal_data_value = 'SI';
}
$responsible_checkin_partner_name = '';
if (!empty($checkin['responsible_checkin_partner_id'])) {
    $responsible = CheckIn::where('remote_id', $checkin['responsible_checkin_partner_id'])->first();
    if ($responsible) {
        $responsible_checkin_partner_name = $responsible->firstname . ' ' . $responsible->lastname;
    }
}

?>
<style>
    .table_border {
        border-collapse: collapse;
        border: 1px solid #000;
    }

    .table_border td {
        border: 1px solid #000;
        padding: 3px 5px;
    }
</style>
<table style="width:100%">
    <tr>
        <td style="width:50%">
            <div style="text-align: center;">
                <img src="{{ $establecimiento->logo_establecimiento ? $establecimiento->logo_establecimiento->getUrl() : '' }}"
                    style="max-height:110px" />

            </div>
        </td>
        <td style="width:50%;">
            <div style="text-align: center;">
                <h3>{{ $establecimiento->nombre }}</h3>
                NIF: {{ $establecimiento->nif }}<br />
                {{ $establecimiento->direccion }}<br />
                Categoria: {{ $establecimiento->categoria }}<br />
            </div>
        </td>
    </tr>
</table>

<div style="width:100%; text-align:center; margin-top:10px">
    <div><b>PART OF TRAVELLERS ENTRY</b></div>
    <div>DOCUMENT NUMBER: GUEST-IN/{{ $checkin['number'] }}</div>
</div>

<table style="width:100%; margin-top:30px">
    <tr>
        <td style="width:50%; " valign="top">
            <div style="text-align:center"><b>GUEST INFORMATION</b></div>
            <table style="width:100%" class="table_border">
                <tr>
                    <td style="width:50%">
                        <b>Document number:</b>
                    </td>
                    <td>
                        {{ $checkin['document_number'] }}
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Type:</b>
                    </td>
                    <td>
                        {{ $checkin['document_type_name'] }}
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Expedition date:</b>
                    </td>
                    <td>
                        {{ $checkin['document_expedition_date'] }}
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Name:</b>
                    </td>
                    <td>
                        {{ strtoupper($checkin['firstname']) }}
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Surname:</b>
                    </td>
                    <td>
                        {{ strtoupper($checkin['lastname']) }} {{ strtoupper($checkin['lastname2']) }}
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Gender:</b>
                    </td>
                    <td>
                        {{ strtoupper($checkin['gender']) }}
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>BirthDate:</b>
                    </td>
                    <td>
                        {{ $checkin['birthdate'] }}
                    </td>
                </tr>
                <?php
                if(!empty($checkin['responsible_checkin_partner_id']) && !empty($checkin['relationship'])){
                ?>
                <tr>
                    <td>
                        <b>Adult:</b>
                    </td>
                    <td>
                        {{ $responsible_checkin_partner_name }}
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Relationship:</b>
                    </td>
                    <td>
                        {{ HotelApiService::relationshipGet($checkin['relationship']) }}
                    </td>
                </tr>
                <?php }else{ ?>
                <tr>
                    <td>
                        &nbsp;
                    </td>
                    <td>

                    </td>
                </tr>
                <tr>
                    <td>
                        &nbsp;
                    </td>
                    <td>

                    </td>
                </tr>
                <?php } ?>
            </table>
        </td>
        <td style="width:10px"></td>
        <td style="width:50%" valign="top">
            <div style="text-align:center"><b>BOOKING INFORMATION</b></div>
            <table style="width:100%" class="table_border">
                <tr>
                    <td style="width:50%">
                        <b>Booking number: </b>
                    </td>
                    <td>
                        {{ $reservation['name'] }}
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Room:</b>
                    </td>
                    <td>
                        {{ $reservation['room_name'] }}
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Régimen:</b>
                    </td>
                    <td>
                        {{ $reservation['reservation_type'] }}
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Checkin date:</b>
                    </td>
                    <td>
                        {{ $reservation['checkin'] }}
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Checkout date:</b>
                    </td>
                    <td>
                        {{ $reservation['checkout'] }}
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Price (IVA incl.):</b>
                    </td>
                    <td>
                        {{ $reservation['price_total'] }}
                    </td>
                </tr>
                <?php foreach($services_names as $ks => $name ){ ?>
                <tr>
                    <td>
                        <b>Extra {{ $ks + 1 }}:</b>
                    </td>
                    <td>
                        {{ $name }}
                    </td>
                </tr>
                <?php } ?>

            </table>
        </td>
    </tr>
</table>
<div style="width:100%;margin-top:10px">
    Time to access rooms: <b> {{ substr($reservation['arrival_hour'], 0, 5) }}</b>.
    Departure time: <b> {{ substr($reservation['departure_hour'], 0, 5) }}</b> If the accommodation is not left at that
    time, the establishment will charge a day’s stay according to current rate that day
</div>
<div style="width:100%;margin-top:20px">
    En nombre de la empresa {{ $establecimiento->sociedad->nombre }}, compuesto por {{ $establecimiento->nombre }},
    tratamos la información que nos facilita con el fin de prestarle el servicio solicitado y realizar la facturación
    del mismo. Se conservarán mientras se mantenga la relación comercial o durante los años necesarios para cumplir con
    las obligaciones legales. No se cederán a terceros salvo en casos en que exista una obligación legal. Usted tiene
    derecho a obtener información sobre el tratamiento de sus datos personales, acceder, modificar, rectificar o
    solicitar su supresión cuando ya no sean necesarios, en la dirección {{ $establecimiento->direccion }}
</div>
<?php if(!empty($show_accept_personal_data)) { ?>
<div style="width:90%;margin-top:20px">
    Autorizo a la empresa {{ $establecimiento->sociedad->nombre }}, compuesto por {{ $establecimiento->nombre }}, a la
    utilización de mis datos personales para la realización de acciones comerciales propias y de terceros:
    <b>{{ $accept_personal_data_value }}</b>
</div>
<?php } ?>
