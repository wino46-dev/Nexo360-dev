<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;

use App\Models\Establecimiento;

use App\Services\HotelApiService;
use App\Services\LittleHotelierService;
use App\Services\MisterPlanApiService;
use App\Services\OctorateImportService;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Cache;
use SpreadsheetReader;


class ReservaController extends Controller
{
    private function assertAllowedHotel($hotelId)
    {
        $allowed = config('external.allowed_hotels');
        if (is_array($allowed) && count($allowed) > 0) {
            if (!in_array((int)$hotelId, array_map('intval', $allowed))) {
                abort(403, 'Hotel no autorizado para este usuario.');
            }
        }
    }

    private function assertAllowedHotels(array $hotelIds)
    {
        foreach ($hotelIds as $hid) {
            $this->assertAllowedHotel($hid);
        }
    }

    public function index(Request $request)
    {
        abort_if(Gate::denies('reserva_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $establecimientosList = Establecimiento::listWithPmsDataAndLocal();

        $establecimientos = [];
        $establecimientos_locales = [];
        foreach ($establecimientosList as $establecimiento) {
            $establecimientos[$establecimiento->id] = $establecimiento->nombre . ' (' . $establecimiento->api_pms . ')';
            if ($establecimiento->api_pms == 'local') {
                $establecimientos_locales[$establecimiento->id] = $establecimiento->nombre . ' (' . $establecimiento->api_pms . ')';
            }
        }

        $pms_list_import = explode(',', env('PMS_IMPORT', ''));

        return view('external.reservas.index', compact('establecimientos', 'establecimientos_locales','pms_list_import'));
    }

    public function buscarDisponibilidad(Request $request)
    {
        $data = $request->all();

        $hotel_id = $data['hotel_id'];
        $this->assertAllowedHotel($hotel_id);

        $hotel_api = new HotelApiService($hotel_id);
        //dd($hotel_api);
        $avails = $hotel_api->availsByRoomType($data);


        $hotel = Establecimiento::find($hotel_id);

        // para pruebas
        /*if($hotel->api_pms== 'local'){
            $avails =  Cache::get('avails',['days' => [], 'room_types_avails' => [] ]);
            Log::info('cache local avails');
        }else{
            Cache::put('avails', $avails);
        }*/

        $res = [
            [
                'hotel_id' => $hotel_id,
                'hotel_name' => $hotel->nombre,
                'days' => $avails['days'],
                'room_types_avails' => $avails['room_types_avails'],
            ]
        ];

        return response()->json([
            'status' => 'ok',
            'data' => $res
        ], 200);
    }

    public function reservationSave(Request $request)
    {
        $data = $request->all();

        // Validate all referenced hotel IDs
        $roomHotelIds = [];
        if (isset($data['rooms']) && is_array($data['rooms'])) {
            foreach ($data['rooms'] as $r) {
                if (isset($r['hotel_id'])) { $roomHotelIds[] = (int)$r['hotel_id']; }
            }
        }
        if (count($roomHotelIds)) { $this->assertAllowedHotels($roomHotelIds); }

        $hotel_id = $data['rooms'][0]['hotel_id'];

        /*return response()->json([
            'status' => 'ok',
            'hotel_id' => $hotel_id,
            'data' => 811720
        ], 200); */


        $hotel_api = new HotelApiService($hotel_id);

        $res = $hotel_api->folioCreate($data);
        if (isset($res['error'])) {
            return response()->json($res, 500);
        }

        return response()->json([
            'status' => 'ok',
            'hotel_id' => $hotel_id,
            'data' => $res
        ], 200);
    }

    public function reservationInfo(Request $request)
    {
        $data = $request->all();
        if (isset($data['hotel_id'])) { $this->assertAllowedHotel($data['hotel_id']); }

        $hotel_api = new HotelApiService($data['hotel_id']);
        $folio = $hotel_api->folioDetail($data['folio_id']);

        //Log::info($folio);
        return view('external.callManager.reservation.folioDetail', compact('folio'));

        /*return response()->json([
            'status' => 'ok',
            'hotel_id' => $data['hotel_id'],
            'data' => $res
        ], 200);*/
    }


    public function reservationSearch(Request $request)
    {
        $data = $request->all();
        $this->assertAllowedHotel($data['hotel_id'] ?? 0);
        $data_api = [
            'q' => $data['search'] ?? null,
            'date_start' => null,
            'date_end' => null,
        ];

        $hotel_api = new HotelApiService($data['hotel_id']);
        $folios = $hotel_api->folioSearch($data_api);

        if (isset($folios['error'])) {

            return response()->json($folios, 500);
        }

        return response()->json(['data' => $folios]);
    }

    public function folioEdit(Request $request)
    {
        $data = $request->all();
        $this->assertAllowedHotel($data['hotel_id'] ?? 0);

        $hotel_api = new HotelApiService($data['hotel_id']);
        $folio = $hotel_api->folioDetail($data['folio_id']);

        return response()->json(['data' => $folio]);
    }

    public function folioUpdate(Request $request)
    {
        $data = $request->all();
        $this->assertAllowedHotel($data['hotel_id'] ?? 0);
        $hotel_api = new HotelApiService($data['hotel_id']);

        if ($data['action'] == 'client_update') {
            $folio_data = [
                'id' => $data['id'],
                'partnerName' => $data['partnerName'],
                'partnerPhone' => $data['partnerPhone'],
                'partnerEmail' => $data['partnerEmail']
            ];
        } else {
            $folio_data = null;
        }
        $folio = $hotel_api->folioUpdate($folio_data);

        return response()->json(['data' => $folio]);
    }

    public function reservationUpdate(Request $request)
    {
        $data = $request->all();
        $this->assertAllowedHotel($data['hotel_id'] ?? 0);
        $hotel_api = new HotelApiService($data['hotel_id']);

        if ($data['action'] == 'update') {
            $reservation_data = [
                'id' => $data['id'],
                'checkin' => $data['checkin3'],
                'checkout' => $data['checkout3'],
                'adults' => $data['adults'],
                'children' => $data['children'],
                'notes' => $data['notes'],
            ];
        } elseif ($data['action'] == 'update-status') {

            $reservation_data = [
                'id' => $data['id'],
                'stateCode' => $data['status'],
            ];
        } else {
            $reservation_data = null;
        }
        $reservation = $hotel_api->reservationUpdate($reservation_data);

        return response()->json(['data' => $reservation]);
    }

    public function reservationAdd(Request $request)
    {
        $data = $request->all();
        $this->assertAllowedHotel($data['hotel_id'] ?? 0);

        $hotel_api = new HotelApiService($data['hotel_id']);

        $folio = $hotel_api->folioAddReservation($data);

        return response()->json(['data' => $folio]);
    }


    public function test(Request $request)
    {

        $hotel_api = new HotelApiService(3);
        $documentTypes = $hotel_api->accountJournals();
        dump($documentTypes);
        $documentTypes = $hotel_api->genders();
        dump($documentTypes);
        $documentTypes = $hotel_api->documentTypes();
        dd($documentTypes);
    }

    public function import(Request $request)
    {
        $data = $request->all();

        $file = $request->file('file');
        $path      = $file->path();
        $reader  = new SpreadsheetReader($path);
        //print_r($reader);

        $hotel_id = $data['hotel_id'];
        $this->assertAllowedHotel($hotel_id);
        $pms_origen = $data['pms_origen'];

        if($pms_origen == 'Octorate'){
            $service = new OctorateImportService($hotel_id);
            $res = $service->import($reader);
        }
        if($pms_origen == 'LittleHotelier'){
            $service = new LittleHotelierService($hotel_id);
            $res = $service->import($reader);
        }
        if($pms_origen == 'MisterPlan'){
            $establecimiento = Establecimiento::find($hotel_id);

            $service = new MisterPlanApiService($establecimiento);
            $res = $service->import($reader);
        }


        return response()->json([
            'status' => 'ok',
            'hotel_id' => $hotel_id,
            'data' => $res
        ], 200);
    }
}
