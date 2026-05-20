<?php

use App\Http\Controllers\WebhookController;

Route::post('login', 'Api\\AuthController@login');
Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:sanctum']], function () {
    // Users
    Route::apiResource('users', 'UsersApiController');
    // Establecimiento
    Route::post('establecimientos/media', 'EstablecimientoApiController@storeMedia')->name('establecimientos.storeMedia');
    Route::apiResource('establecimientos', 'EstablecimientoApiController');

    // Sociedad
    Route::post('sociedads/media', 'SociedadApiController@storeMedia')->name('sociedads.storeMedia');
    Route::apiResource('sociedads', 'SociedadApiController');

    // Habitacion
    Route::apiResource('habitacions', 'HabitacionApiController');

    // Totem
    Route::post('totems/media', 'TotemApiController@storeMedia')->name('totems.storeMedia');
    Route::get('totems/profile', 'TotemApiController@profile')->name('totems.profile');
    Route::get('totems/phone-data', 'TotemApiController@phoneData')->name('totems.phoneData');
    Route::apiResource('totems', 'TotemApiController');

    // Evento Home Totem
    Route::apiResource('evento-home-totems', 'EventoHomeTotemApiController', ['except' => ['update']]);

    // Control Error
    Route::apiResource('control-errors', 'ControlErrorApiController', ['except' => ['update']]);

    // Layout Home
    Route::post('layout-homes/media', 'LayoutHomeApiController@storeMedia')->name('layout-homes.storeMedia');
    Route::apiResource('layout-homes', 'LayoutHomeApiController');

    // Cliente
    Route::apiResource('clientes', 'ClienteApiController');

    // Pais
    Route::apiResource('pais', 'PaisApiController');

    // Provincia
    Route::apiResource('provincia', 'ProvinciaApiController');

    // Ciudad
    Route::apiResource('ciudads', 'CiudadApiController');

    // Reserva
    Route::apiResource('reservas', 'ReservaApiController');

    // Check In
    Route::post('check-ins/media', 'CheckInApiController@storeMedia')->name('check-ins.storeMedia');
    Route::apiResource('check-ins', 'CheckInApiController');

    // Tipo Evento
    Route::apiResource('tipo-eventos', 'TipoEventoApiController');

    // Control Sesion
    Route::apiResource('control-sesions', 'ControlSesionApiController');

    // Configuracion Tpv
    Route::apiResource('configuracion-tpvs', 'ConfiguracionTpvApiController');

    // Pago Totem
    Route::apiResource('pago-totems', 'PagoTotemApiController');

    // Session
    Route::apiResource('sessions', 'SessionApiController', ['except' => ['store', 'update']]);

    // Respuesta Pago
    Route::apiResource('respuesta-pagos', 'RespuestaPagoApiController');

    // Grabacion Tarjeta
    Route::apiResource('grabacion-tarjeta', 'GrabacionTarjetaApiController');

    // Configuracion Grabador
    Route::apiResource('configuracion-grabadors', 'ConfiguracionGrabadorApiController');

    // Respuesta Evento Home Totem
    Route::apiResource('respuesta-evento-home-totems', 'RespuestaEventoHomeTotemApiController');

    // Configuracion Video
    Route::apiResource('configuracion-videos', 'ConfiguracionVideoApiController', ['except' => ['destroy']]);

    // Firma Check In
    Route::post('firma-check-ins/media', 'FirmaCheckInApiController@storeMedia')->name('firma-check-ins.storeMedia');
    Route::apiResource('firma-check-ins', 'FirmaCheckInApiController');

    // Ayuda Step Totem
    Route::post('ayuda-step-totems/media', 'AyudaStepTotemApiController@storeMedia')->name('ayuda-step-totems.storeMedia');
    Route::apiResource('ayuda-step-totems', 'AyudaStepTotemApiController');

    // Doc Hotel Reception Info
    Route::post('doc-hotel-reception-infos/media', 'DocHotelReceptionInfoApiController@storeMedia')->name('doc-hotel-reception-infos.storeMedia');
    Route::apiResource('doc-hotel-reception-infos', 'DocHotelReceptionInfoApiController');

    // Doc Hotel Estado Caja
    Route::post('doc-hotel-estado-cajas/media', 'DocHotelEstadoCajaApiController@storeMedia')->name('doc-hotel-estado-cajas.storeMedia');
    Route::apiResource('doc-hotel-estado-cajas', 'DocHotelEstadoCajaApiController');

    // Doc Info Hotel
    Route::post('doc-info-hotels/media', 'DocInfoHotelApiController@storeMedia')->name('doc-info-hotels.storeMedia');
    Route::apiResource('doc-info-hotels', 'DocInfoHotelApiController');

    // Doc Servicio Hotel
    Route::apiResource('doc-servicio-hotels', 'DocServicioHotelApiController');

    // Doc Metodo Pago Hotel
    Route::apiResource('doc-metodo-pago-hotels', 'DocMetodoPagoHotelApiController');

    // Doc Ubicacion Hotel
    Route::post('doc-ubicacion-hotels/media', 'DocUbicacionHotelApiController@storeMedia')->name('doc-ubicacion-hotels.storeMedia');
    Route::apiResource('doc-ubicacion-hotels', 'DocUbicacionHotelApiController');

    // Dock Stock Hotel
    Route::post('dock-stock-hotels/media', 'DockStockHotelApiController@storeMedia')->name('dock-stock-hotels.storeMedia');
    Route::apiResource('dock-stock-hotels', 'DockStockHotelApiController');

    // Doc Incidencia Hotel
    Route::post('doc-incidencia-hotels/media', 'DocIncidenciaHotelApiController@storeMedia')->name('doc-incidencia-hotels.storeMedia');
    Route::apiResource('doc-incidencia-hotels', 'DocIncidenciaHotelApiController');

    // Doc No Deseado Hotel
    Route::post('doc-no-deseado-hotels/media', 'DocNoDeseadoHotelApiController@storeMedia')->name('doc-no-deseado-hotels.storeMedia');
    Route::apiResource('doc-no-deseado-hotels', 'DocNoDeseadoHotelApiController');

    // Wiki Hotel custom endpoints
    Route::get('wiki-hotel/establecimientos/{establecimiento}/incidencias-abiertas', 'DocIncidenciaHotelApiController@abiertasPorHotel');
    Route::get('wiki-hotel/establecimientos/{establecimiento}/no-deseados', 'DocNoDeseadoHotelApiController@porHotel');

    // Doc Habitacion Hotel
    Route::post('doc-habitacion-hotels/media', 'DocHabitacionHotelApiController@storeMedia')->name('doc-habitacion-hotels.storeMedia');
    Route::apiResource('doc-habitacion-hotels', 'DocHabitacionHotelApiController');

    // Doc Tarifa Hotel
    Route::apiResource('doc-tarifa-hotels', 'DocTarifaHotelApiController');

    // Hotel RAG aggregate endpoint
    Route::get('hotel-rag', 'HotelRagController@show');
    Route::get('hotel-rag/{id}', 'HotelRagController@showById');
    // Hotel list for RAG
    Route::get('list-hotels-rag', 'HotelRagController@listHotels');

    // Call Manager reservation flow endpoints (PMS-agnostic via HotelApiService)
    Route::get('call-manager/folios/search', 'CallManagerReservationApiController@searchFolios');
    Route::get('call-manager/folios/{folio}/detail', 'CallManagerReservationApiController@folioDetail');
    Route::get('call-manager/reservations/{reservation}/detail', 'CallManagerReservationApiController@reservationDetail');

    // Availability endpoints (PMS-agnostic via HotelApiService)
    Route::get('call-manager/availability/by-room-type', 'HotelAvailabilityApiController@availsByRoomType');

});

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1\Admin'], function () {
    Route::post('reservation', 'ReservationController@store');
});

Route::post('webhook/reservation', [WebhookController::class, 'storeReservation']);

// Local PMS public API (multi-tenant, API Key per hotel)
Route::prefix('v1/local-pms/{hotel_code}')
    ->middleware(['api', 'api_pms_auth', 'throttle:api'])
    ->group(function () {
        // Bookings
        Route::get('bookings', [\App\Http\Controllers\Api\LocalPms\BookingsController::class, 'index']);
        Route::post('bookings', [\App\Http\Controllers\Api\LocalPms\BookingsController::class, 'store']);
        Route::get('bookings/{booking}', [\App\Http\Controllers\Api\LocalPms\BookingsController::class, 'show']);
        Route::patch('bookings/{booking}', [\App\Http\Controllers\Api\LocalPms\BookingsController::class, 'update']);
        Route::delete('bookings/{booking}', [\App\Http\Controllers\Api\LocalPms\BookingsController::class, 'destroy']);
        Route::post('bookings/{booking}/rooms', [\App\Http\Controllers\Api\LocalPms\BookingsController::class, 'addRoom']);
        Route::patch('bookings/{booking}/rooms/{reservation}', [\App\Http\Controllers\Api\LocalPms\BookingsController::class, 'updateRoom']);

        // Services (extras/concepts) for reservations/folios
        Route::get('bookings/{booking}/services', [\App\Http\Controllers\Api\LocalPms\ServicesController::class, 'index']);
        Route::post('bookings/{booking}/services', [\App\Http\Controllers\Api\LocalPms\ServicesController::class, 'store']);
        Route::delete('bookings/{booking}/services/{service}', [\App\Http\Controllers\Api\LocalPms\ServicesController::class, 'destroy']);

        // Guests
        Route::get('bookings/{booking}/guests', [\App\Http\Controllers\Api\LocalPms\GuestsController::class, 'index']);
        Route::post('bookings/{booking}/guests', [\App\Http\Controllers\Api\LocalPms\GuestsController::class, 'upsert']);
        Route::post('bookings/{booking}/guests/{guest}/board', [\App\Http\Controllers\Api\LocalPms\GuestsController::class, 'board']);

        // Payments
        Route::get('payments/methods', [\App\Http\Controllers\Api\LocalPms\PaymentsController::class, 'methods']);
        Route::get('payments/transactions', [\App\Http\Controllers\Api\LocalPms\PaymentsController::class, 'transactions']);
        Route::post('payments/charges', [\App\Http\Controllers\Api\LocalPms\PaymentsController::class, 'createCharge']);
        Route::post('payments/refunds', [\App\Http\Controllers\Api\LocalPms\PaymentsController::class, 'refund']);
    });

/*Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1\Admin'], function () {
    // Respuesta Pago
    Route::apiResource('respuesta-pagos', 'RespuestaPagoApiController');
});*/
