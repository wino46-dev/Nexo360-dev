<?php

namespace App\Services\Contracts;

interface HotelPmsInterface
{
    // Auth/session
    public function login();
    public function resetAuthCache(): void;

    // Core reservation/folio APIs
    public function folioSearch($data);
    public function folioDetail($id);
    public function folioReservations($id);
    public function reservationDetail($id);
    public function reservationDetailLines($id);
    public function reservationServices($id);
    public function reservationCheckinPartners($id);
    public function reservationCheckinPartnersSave($data, $onBoard = false);
    public function reservationUpdate($data);

    // Catalogs and dictionaries
    public function documentTypes();
    public function countries();
    public function country_states($id);
    public function genders();

    // Finance
    public function accountJournals();
    public function transactions($data);

    // Inventory and availability (optional for some PMS)
    public function roomTypes($data = []);
    public function rooms($data = []);
    public function availsByRoomType($data = []);

    // Folio creation/update (optional for some PMS)
    public function folioCreate($data);
    public function folioUpdate($data);
    public function folioAddReservation($data);

    // Extras
    public function getReservationsStatus($data);
    public function getClientInfo($data);
    public function checkinPartnerSave($partner, $reservation);
    public function faltaFirma($data);
}
