<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str as Str;
use App\Models\Establecimiento;
use Exception;

use App\Services\Contracts\HotelPmsInterface;

class HotelApiService
{
    /** @var HotelPmsInterface */
    public $service;
    public $pms;


    public function __construct(int $hotel_id, $login_hotel = false)
    {
        //Log::info('cont HotelApiService');
        $hotel = Establecimiento::where('id', $hotel_id)->first();
        $this->pms = $hotel->api_pms;

        if (!$hotel) {
            throw new Exception("Este hotel no esta configurado: $hotel_id");
        }

        if ($hotel->api_pms == 'roomdoo') {
            $this->service = new RoomdooApiService($hotel, $login_hotel);
        } elseif ($hotel->api_pms == 'local') {
            $this->service = new LocalApiService($hotel);
        } elseif ($hotel->api_pms == 'misterplan') {
            $this->service = new MisterPlanApiService($hotel);
        } else {
            $this->service = new LocalApiService($hotel);
            //throw new Exception("No ha seleccionado el PMS del hotel");
        }
    }

    public function login()
    {
        return $this->service->login();
    }

    public function folioSearch($data)
    {
        return $this->service->folioSearch($data);
    }

    public function folioDetail($id)
    {

        return $this->service->folioDetail($id);
    }

    public function reservationDetail($data)
    {

        return $this->service->reservationDetail($data);
    }

    public function reservationServices($id)
    {
        return $this->service->reservationServices($id);
    }

    public function reservationCheckinPartners($id)
    {

        return $this->service->reservationCheckinPartners($id);
    }

    ///////////////////////////////
    public function documentTypes()
    {

        return $this->service->documentTypes();
    }

    public function countries()
    {

        return $this->service->countries();
    }

    public function country_states($id)
    {

        return $this->service->country_states($id);
    }

    public function genders()
    {

        return $this->service->genders();
    }

    public function reservationCheckinPartnersSave($data, $onBoard = false)
    {

        return $this->service->reservationCheckinPartnersSave($data, $onBoard);
    }

    public function accountJournals()
    {

        return $this->service->accountJournals();
    }

    public function transactions($data)
    {
        return $this->service->transactions($data);
    }

    public function roomTypes($data = [])
    {
        return $this->service->roomTypes($data);
    }

    public function rooms($data = [])
    {
        return $this->service->rooms($data);
    }

    public function availsByRoomType($data = [])
    {
        return $this->service->availsByRoomType($data);
    }

    public function folioCreate($data)
    {
        return $this->service->folioCreate($data);
    }

    public function folioUpdate($data)
    {
        return $this->service->folioUpdate($data);
    }

    public function folioAddReservation($data)
    {
        return $this->service->folioAddReservation($data);
    }

    public function reservationUpdate($data)
    {
        return $this->service->reservationUpdate($data);
    }

    public static function relationshipList()
    {
        return [
            "AB" => "Abuelo/a",
            "BA" => "Bisabuelo/a",
            "BN" => "Bisnieto/a",
            "CD" => "Cuñado/a",
            "CY" => "Cónyuge",
            "HJ" => "Hijo/a",
            "HR" => "Hermano",
            "NI" => "Nieto/a",
            "PM" => "Padre o Madre",
            "SB" => "Sobrino/a",
            "SG" => "Suegro/a",
            "TI" => "Tío/a",
            "YN" => "Yerno o Nuera",
            "TU" => "Tutor/a",
            "OT" => "Otro",
        ];
    }

    public static function relationshipGet($id)
    {
        $list = self::relationshipList();
        if (!empty($list[$id])) {
            return $list[$id];
        }
        return '';
    }

    public function getReservationsStatus($data){
        return $this->service->getReservationsStatus($data);
    }

    public function getClientInfo($data){
        return $this->service->getClientInfo($data);
    }
    public function checkinPartnerSave($partner, $reservation){
        return $this->service->checkinPartnerSave($partner, $reservation);
    }

    public function faltaFirma($data){
        return $this->service->faltaFirma($data);
    }

    public function resetAuthCache(): void
    {
        if (method_exists($this->service, 'resetAuthCache')) {
            $this->service->resetAuthCache();
        }
    }
}
