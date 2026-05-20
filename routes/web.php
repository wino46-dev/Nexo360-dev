<?php

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response as HttpResponse;

Route::get('/', 'LoginRedirect@index')->middleware('auth');
Auth::routes(['register' => false]);

// Public fallback to serve files from storage/app/public when the public/storage symlink is missing
Route::get('storage/{path}', function ($path) {
    // Normalize path
    $path = ltrim($path, '/');
    if (!Storage::disk('public')->exists($path)) {
        abort(404);
    }
    // Optionally set caching headers
    $mime = Storage::disk('public')->mimeType($path) ?: 'application/octet-stream';
    $stream = Storage::disk('public')->readStream($path);
    return response()->stream(function() use ($stream) {
        fpassthru($stream);
    }, HttpResponse::HTTP_OK, [
        'Content-Type' => $mime,
        'Cache-Control' => 'public, max-age=31536000',
    ]);
})->where('path', '.*');


Route::group(['prefix' => 'external', 'as' => 'external.', 'namespace' => 'External', 'middleware' => ['auth', '2fa', 'admin', 'panel:external','external.hotels']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');
    Route::post('permissions/sync', 'PermissionsController@sync');
    Route::get('reservation-api/reservation-costs/{id}', 'ReservationApiController@reservationCosts')->name('reservation-api.reservation-costs');



    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    // User access to Societies & Hotels
    Route::get('users/access', 'UsersController@accessSelect')->name('users.access.select');
    Route::get('users/{user}/access', 'UsersController@access')->name('users.access');
    Route::post('users/{user}/access', 'UsersController@accessUpdate')->name('users.access.update');
    Route::resource('users', 'UsersController');

    // Audit Logs
    Route::resource('audit-logs', 'AuditLogsController', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);

    // FTP Upload Logs (read-only)
    Route::resource('ftp-upload-logs', 'FtpUploadLogController', ['except' => ['create', 'store', 'edit', 'update', 'destroy', 'destroy']]);

    // Audit Export
    Route::get('audit-export', 'AuditExportController@index')->name('audit-export.index');
    Route::post('audit-export', 'AuditExportController@store')->name('audit-export.store');

    // Team
    Route::delete('teams/destroy', 'TeamController@massDestroy')->name('teams.massDestroy');
    Route::resource('teams', 'TeamController');

    // Establecimiento
    Route::delete('establecimientos/destroy', 'EstablecimientoController@massDestroy')->name('establecimientos.massDestroy');
    Route::post('establecimientos/media', 'EstablecimientoController@storeMedia')->name('establecimientos.storeMedia');
    Route::post('establecimientos/ckmedia', 'EstablecimientoController@storeCKEditorImages')->name('establecimientos.storeCKEditorImages');
    Route::post('establecimientos/parse-csv-import', 'EstablecimientoController@parseCsvImport')->name('establecimientos.parseCsvImport');
    Route::post('establecimientos/process-csv-import', 'EstablecimientoController@processCsvImport')->name('establecimientos.processCsvImport');

    Route::get('establecimientos/partes-config/{id}', 'EstablecimientoController@partesConfig');
    Route::post('establecimientos/partes-config/{id}', 'EstablecimientoController@partesConfigSave');
    Route::post('establecimientos/partes-backup/{id}', 'EstablecimientoController@partesBackup');
    Route::post('establecimientos/partes-ftp-test/{id}', 'EstablecimientoController@partesFtpTest');

    Route::resource('establecimientos', 'EstablecimientoController');

    // Sociedad
    Route::delete('sociedads/destroy', 'SociedadController@massDestroy')->name('sociedads.massDestroy');
    Route::post('sociedads/media', 'SociedadController@storeMedia')->name('sociedads.storeMedia');
    Route::post('sociedads/ckmedia', 'SociedadController@storeCKEditorImages')->name('sociedads.storeCKEditorImages');
    Route::resource('sociedads', 'SociedadController');

    // Habitacion
    Route::delete('habitacions/destroy', 'HabitacionController@massDestroy')->name('habitacions.massDestroy');
    Route::post('habitacions/parse-csv-import', 'HabitacionController@parseCsvImport')->name('habitacions.parseCsvImport');
    Route::post('habitacions/process-csv-import', 'HabitacionController@processCsvImport')->name('habitacions.processCsvImport');
    Route::resource('habitacions', 'HabitacionController');

    // Totem
    Route::delete('totems/destroy', 'TotemController@massDestroy')->name('totems.massDestroy');
    Route::post('totems/media', 'TotemController@storeMedia')->name('totems.storeMedia');
    Route::post('totems/ckmedia', 'TotemController@storeCKEditorImages')->name('totems.storeCKEditorImages');
    Route::post('totems/update-mass', 'TotemController@updateMass');
    Route::resource('totems', 'TotemController');

    // Evento Home Totem
    Route::delete('evento-home-totems/destroy', 'EventoHomeTotemController@massDestroy')->name('evento-home-totems.massDestroy');
    Route::post('evento-home-totems/media', 'EventoHomeTotemController@storeMedia')->name('evento-home-totems.storeMedia');
    Route::post('evento-home-totems/ckmedia', 'EventoHomeTotemController@storeCKEditorImages')->name('evento-home-totems.storeCKEditorImages');
    Route::resource('evento-home-totems', 'EventoHomeTotemController');
    Route::post('evento-home-totems/tour-galery', 'EventoHomeTotemController@tourGalery')->name('evento-home-totems.tour-galery');
    Route::post('evento-home-totems/update-content-document','EventoHomeTotemController@updateContentDocument')->name('evento-home-totems.updateContentDocument');
    Route::post('evento-home-totems/{id}','EventoHomeTotemController@update')->name('evento-home-totems.storen');

    // Control Error
    Route::delete('control-errors/destroy', 'ControlErrorController@massDestroy')->name('control-errors.massDestroy');
    Route::resource('control-errors', 'ControlErrorController', ['except' => ['edit', 'update']]);

    // Layout Home
    Route::delete('layout-homes/destroy', 'LayoutHomeController@massDestroy')->name('layout-homes.massDestroy');
    Route::post('layout-homes/media', 'LayoutHomeController@storeMedia')->name('layout-homes.storeMedia');
    Route::post('layout-homes/ckmedia', 'LayoutHomeController@storeCKEditorImages')->name('layout-homes.storeCKEditorImages');
    Route::resource('layout-homes', 'LayoutHomeController');

    // Pais
    Route::delete('pais/destroy', 'PaisController@massDestroy')->name('pais.massDestroy');
    Route::post('pais/parse-csv-import', 'PaisController@parseCsvImport')->name('pais.parseCsvImport');
    Route::post('pais/process-csv-import', 'PaisController@processCsvImport')->name('pais.processCsvImport');
    Route::resource('pais', 'PaisController');

    // Provincia
    Route::delete('provincia/destroy', 'ProvinciaController@massDestroy')->name('provincia.massDestroy');
    Route::post('provincia/parse-csv-import', 'ProvinciaController@parseCsvImport')->name('provincia.parseCsvImport');
    Route::post('provincia/process-csv-import', 'ProvinciaController@processCsvImport')->name('provincia.processCsvImport');
    Route::resource('provincia', 'ProvinciaController');

    // Ciudad
    Route::delete('ciudads/destroy', 'CiudadController@massDestroy')->name('ciudads.massDestroy');
    Route::post('ciudads/parse-csv-import', 'CiudadController@parseCsvImport')->name('ciudads.parseCsvImport');
    Route::post('ciudads/process-csv-import', 'CiudadController@processCsvImport')->name('ciudads.processCsvImport');
    Route::resource('ciudads', 'CiudadController');


    // Check In
    Route::delete('check-ins/destroy', 'CheckInController@massDestroy')->name('check-ins.massDestroy');
    Route::post('check-ins/media', 'CheckInController@storeMedia')->name('check-ins.storeMedia');
    Route::post('check-ins/ckmedia', 'CheckInController@storeCKEditorImages')->name('check-ins.storeCKEditorImages');
    Route::post('check-ins/parte-viajero-pdf', 'CheckInController@parteViajeroPdf');
    Route::post('check-ins/list', 'CheckInController@list');
    Route::post('check-ins/pdf-download', 'CheckInController@pdfDownload');
    Route::resource('check-ins', 'CheckInController');

    // Tipo Evento
    Route::delete('tipo-eventos/destroy', 'TipoEventoController@massDestroy')->name('tipo-eventos.massDestroy');
    Route::resource('tipo-eventos', 'TipoEventoController');

    // Control Sesion
    Route::delete('control-sesions/destroy', 'ControlSesionController@massDestroy')->name('control-sesions.massDestroy');
    Route::post('control-sesions/cerrar', 'ControlSesionController@cerrar')->name('control-sesions.cerrar');
    Route::post('control-sesions/manager', 'ControlSesionController@PostEventManager')->name('control-sesions.postManager');
    Route::get('sesion-manager', 'ControlSesionController@ShowEventManager')->name('control-sesions.manager');

    //Route::post('sesion-manager/reservation-totem-event', 'ControlSesionController@reservationTotemEvent')->name('control-sesions.reservation-totem-event');

    Route::resource('control-sesions', 'ControlSesionController');



    // Configuracion Tpv
    Route::delete('configuracion-tpvs/destroy', 'ConfiguracionTpvController@massDestroy')->name('configuracion-tpvs.massDestroy');
    Route::resource('configuracion-tpvs', 'ConfiguracionTpvController');

    // Pago Totem
    Route::delete('pago-totems/destroy', 'PagoTotemController@massDestroy')->name('pago-totems.massDestroy');
    Route::resource('pago-totems', 'PagoTotemController');

    // Session
    Route::delete('sessions/destroy', 'SessionController@massDestroy')->name('sessions.massDestroy');
    Route::resource('sessions', 'SessionController', ['except' => ['create', 'store', 'edit', 'update']]);

    Route::get('team-members', 'TeamMembersController@index')->name('team-members.index');
    Route::post('team-members', 'TeamMembersController@invite')->name('team-members.invite');

    // Respuesta Pago
    Route::delete('respuesta-pagos/destroy', 'RespuestaPagoController@massDestroy')->name('respuesta-pagos.massDestroy');
    Route::resource('respuesta-pagos', 'RespuestaPagoController');

    // Grabacion Tarjeta
    Route::delete('grabacion-tarjeta/destroy', 'GrabacionTarjetaController@massDestroy')->name('grabacion-tarjeta.massDestroy');
    Route::post('grabacion-tarjeta/colocar','GrabacionTarjetaController@NotifyPutCardOnReader')->name('grabacion-tarjeta.notify-put-card-on-reader');
    Route::resource('grabacion-tarjeta', 'GrabacionTarjetaController');

    // Configuracion Grabador
    Route::delete('configuracion-grabadors/destroy', 'ConfiguracionGrabadorController@massDestroy')->name('configuracion-grabadors.massDestroy');
    Route::resource('configuracion-grabadors', 'ConfiguracionGrabadorController');

    // Respuesta Evento Home Totem
    Route::delete('respuesta-evento-home-totems/destroy', 'RespuestaEventoHomeTotemController@massDestroy')->name('respuesta-evento-home-totems.massDestroy');
    Route::resource('respuesta-evento-home-totems', 'RespuestaEventoHomeTotemController');

    // Configuracion Video
    Route::resource('configuracion-videos', 'ConfiguracionVideoController');


    // Ayuda Step Totem
    Route::delete('ayuda-step-totems/destroy', 'AyudaStepTotemController@massDestroy')->name('ayuda-step-totems.massDestroy');
    Route::post('ayuda-step-totems/media', 'AyudaStepTotemController@storeMedia')->name('ayuda-step-totems.storeMedia');
    Route::post('ayuda-step-totems/ckmedia', 'AyudaStepTotemController@storeCKEditorImages')->name('ayuda-step-totems.storeCKEditorImages');
    Route::resource('ayuda-step-totems', 'AyudaStepTotemController');

    // Parte Viajero
    Route::delete('parte-viajeros/destroy', 'ParteViajeroController@massDestroy')->name('parte-viajeros.massDestroy');
    Route::resource('parte-viajeros', 'ParteViajeroController');


    Route::get('call-manager', 'CallManagerController@index')->name('call-manager.index');
    Route::get('call-manager/payment-table/{folio_id}', 'CallManagerController@paymentTable')->name('call-manager.paymentTable');
    Route::get('call-manager/write-card-table', 'CallManagerController@writeCardTable')->name('call-manager.writeCardTable');
    Route::post('call-manager/document-view', 'CallManagerController@documentView')->name('call-manager.documentView');
    Route::post('call-manager/sign-view', 'CallManagerController@signView')->name('call-manager.signView');
    Route::post('call-manager/parte-send-mail', 'CallManagerController@parteSendMail');
    Route::post('call-manager/llamada-inversa', 'CallManagerController@llamadaInversa');
    Route::post('call-manager/recommendations', 'CallManagerController@recommendations')->name('call-manager.recommendations');
    Route::post('call-manager/reservation-recommendations', 'CallManagerController@reservationRecommendations')->name('call-manager.reservation-recommendations');
    Route::post('call-manager/huesped-relacion', 'CallManagerController@huespedRelacion');
    Route::post('call-manager/cerrar-parte-viajero-totem', 'CallManagerController@cerrarParteViajeroTotem');
    Route::post('call-manager/huesped-info', 'CallManagerController@huespedInfo');


    Route::get('call-manager/reservation-resumen/{id}', 'CallManagerController@reservationResumen')->name('call-manager.reservationResumen');



    // Zona Comun
    Route::delete('zona-comuns/destroy', 'ZonaComunController@massDestroy')->name('zona-comuns.massDestroy');
    Route::post('zona-comuns/parse-csv-import', 'ZonaComunController@parseCsvImport')->name('zona-comuns.parseCsvImport');
    Route::post('zona-comuns/process-csv-import', 'ZonaComunController@processCsvImport')->name('zona-comuns.processCsvImport');
    Route::resource('zona-comuns', 'ZonaComunController');


    // Reservas Api
    Route::get('reservation-api/folio-search', 'ReservationApiController@folioSearch');
    Route::get('reservation-api/folio-detail', 'ReservationApiController@folioDetail');
    Route::post('reservation-api/folio-detail-select', 'ReservationApiController@folioDetailSelect');
    Route::get('reservation-api/folio-status', 'ReservationApiController@folioStatus');

    Route::get('reservation-api/reservation-select/{id}', 'ReservationApiController@reservationSelect');
    Route::get('reservation-api/countries-states/{id}', 'ReservationApiController@countriesStates');

    Route::post('reservation-api/checkin-partner', 'ReservationApiController@checkinPartner');
    Route::post('reservation-api/checkin', 'ReservationApiController@checkin');


    Route::get('alice/index/{id}', 'AliceController@index');
    Route::get('alice/status/{id}', 'AliceController@status');
    Route::post('alice/events/{id}', 'AliceController@events');

    Route::get('sh360-ocr/index/{id}', 'Sh360OcrController@index');

    // Reserva
    //Route::delete('reservas/destroy', 'ReservaController@massDestroy')->name('reservas.massDestroy');
    Route::get('reservas/index', 'ReservaController@index')->name('reservas.index');
    Route::post('reservas/buscar-disponibilidad', 'ReservaController@buscarDisponibilidad');
    Route::post('reservas/reservation-save', 'ReservaController@reservationSave');
    Route::post('reservas/reservation-info', 'ReservaController@reservationInfo');
    Route::post('reservas/reservation-search', 'ReservaController@reservationSearch');
    Route::post('reservas/folio-edit', 'ReservaController@folioEdit');
    Route::post('reservas/folio-update', 'ReservaController@folioUpdate');
    Route::post('reservas/reservation-update', 'ReservaController@reservationUpdate');
    Route::post('reservas/reservation-add', 'ReservaController@reservationAdd');
    Route::post('reservas/import', 'ReservaController@import');


    Route::get('reservas/test', 'ReservaController@test');
    //Route::resource('reservas', 'ReservaController');

    Route::resource('rooms', 'RoomController');
    Route::post('rooms/list', 'RoomController@list');

    Route::resource('rooms-types', 'RoomTypeController');
    Route::post('rooms-types/list', 'RoomTypeController@list');

    Route::resource('rooms-types-prices', 'RoomTypePriceController');
    Route::post('rooms-types-prices/list', 'RoomTypePriceController@list');

    Route::resource('avails', 'AvailController');
    Route::post('avails/list', 'AvailController@list');

    //Route::get('alice/front', 'AliceController@front');
    //Route::get('alice/login', 'AliceController@login');

    // Doc Hotel Reception Info
    Route::delete('doc-hotel-reception-infos/destroy', 'DocHotelReceptionInfoController@massDestroy')->name('doc-hotel-reception-infos.massDestroy');
    Route::post('doc-hotel-reception-infos/media', 'DocHotelReceptionInfoController@storeMedia')->name('doc-hotel-reception-infos.storeMedia');
    Route::post('doc-hotel-reception-infos/ckmedia', 'DocHotelReceptionInfoController@storeCKEditorImages')->name('doc-hotel-reception-infos.storeCKEditorImages');
    Route::resource('doc-hotel-reception-infos', 'DocHotelReceptionInfoController');

    // Doc Hotel Estado Caja
    Route::delete('doc-hotel-estado-cajas/destroy', 'DocHotelEstadoCajaController@massDestroy')->name('doc-hotel-estado-cajas.massDestroy');
    Route::post('doc-hotel-estado-cajas/media', 'DocHotelEstadoCajaController@storeMedia')->name('doc-hotel-estado-cajas.storeMedia');
    Route::post('doc-hotel-estado-cajas/ckmedia', 'DocHotelEstadoCajaController@storeCKEditorImages')->name('doc-hotel-estado-cajas.storeCKEditorImages');
    Route::post('doc-hotel-estado-cajas/parse-csv-import', 'DocHotelEstadoCajaController@parseCsvImport')->name('doc-hotel-estado-cajas.parseCsvImport');
    Route::post('doc-hotel-estado-cajas/process-csv-import', 'DocHotelEstadoCajaController@processCsvImport')->name('doc-hotel-estado-cajas.processCsvImport');
    Route::resource('doc-hotel-estado-cajas', 'DocHotelEstadoCajaController');

    // Doc Info Hotel
    Route::delete('doc-info-hotels/destroy', 'DocInfoHotelController@massDestroy')->name('doc-info-hotels.massDestroy');
    Route::post('doc-info-hotels/media', 'DocInfoHotelController@storeMedia')->name('doc-info-hotels.storeMedia');
    Route::post('doc-info-hotels/ckmedia', 'DocInfoHotelController@storeCKEditorImages')->name('doc-info-hotels.storeCKEditorImages');
    Route::resource('doc-info-hotels', 'DocInfoHotelController');

    // Doc Servicio Hotel
    Route::delete('doc-servicio-hotels/destroy', 'DocServicioHotelController@massDestroy')->name('doc-servicio-hotels.massDestroy');
    Route::resource('doc-servicio-hotels', 'DocServicioHotelController');

    // Doc Metodo Pago Hotel
    Route::delete('doc-metodo-pago-hotels/destroy', 'DocMetodoPagoHotelController@massDestroy')->name('doc-metodo-pago-hotels.massDestroy');
    Route::post('doc-metodo-pago-hotels/parse-csv-import', 'DocMetodoPagoHotelController@parseCsvImport')->name('doc-metodo-pago-hotels.parseCsvImport');
    Route::post('doc-metodo-pago-hotels/process-csv-import', 'DocMetodoPagoHotelController@processCsvImport')->name('doc-metodo-pago-hotels.processCsvImport');
    Route::resource('doc-metodo-pago-hotels', 'DocMetodoPagoHotelController');

    // Doc Ubicacion Hotel
    Route::delete('doc-ubicacion-hotels/destroy', 'DocUbicacionHotelController@massDestroy')->name('doc-ubicacion-hotels.massDestroy');
    Route::post('doc-ubicacion-hotels/media', 'DocUbicacionHotelController@storeMedia')->name('doc-ubicacion-hotels.storeMedia');
    Route::post('doc-ubicacion-hotels/ckmedia', 'DocUbicacionHotelController@storeCKEditorImages')->name('doc-ubicacion-hotels.storeCKEditorImages');
    Route::resource('doc-ubicacion-hotels', 'DocUbicacionHotelController');

    // Dock Stock Hotel
    Route::delete('dock-stock-hotels/destroy', 'DockStockHotelController@massDestroy')->name('dock-stock-hotels.massDestroy');
    Route::post('dock-stock-hotels/media', 'DockStockHotelController@storeMedia')->name('dock-stock-hotels.storeMedia');
    Route::post('dock-stock-hotels/ckmedia', 'DockStockHotelController@storeCKEditorImages')->name('dock-stock-hotels.storeCKEditorImages');
    Route::resource('dock-stock-hotels', 'DockStockHotelController');

    // Doc Incidencia Hotel
    Route::delete('doc-incidencia-hotels/destroy', 'DocIncidenciaHotelController@massDestroy')->name('doc-incidencia-hotels.massDestroy');
    Route::post('doc-incidencia-hotels/media', 'DocIncidenciaHotelController@storeMedia')->name('doc-incidencia-hotels.storeMedia');
    Route::post('doc-incidencia-hotels/ckmedia', 'DocIncidenciaHotelController@storeCKEditorImages')->name('doc-incidencia-hotels.storeCKEditorImages');
    Route::resource('doc-incidencia-hotels', 'DocIncidenciaHotelController');

    // Doc No Deseado Hotel
    Route::delete('doc-no-deseado-hotels/destroy', 'DocNoDeseadoHotelController@massDestroy')->name('doc-no-deseado-hotels.massDestroy');
    Route::post('doc-no-deseado-hotels/media', 'DocNoDeseadoHotelController@storeMedia')->name('doc-no-deseado-hotels.storeMedia');
    Route::post('doc-no-deseado-hotels/ckmedia', 'DocNoDeseadoHotelController@storeCKEditorImages')->name('doc-no-deseado-hotels.storeCKEditorImages');
    Route::resource('doc-no-deseado-hotels', 'DocNoDeseadoHotelController');

    // Doc Habitacion Hotel
    Route::delete('doc-habitacion-hotels/destroy', 'DocHabitacionHotelController@massDestroy')->name('doc-habitacion-hotels.massDestroy');
    Route::post('doc-habitacion-hotels/media', 'DocHabitacionHotelController@storeMedia')->name('doc-habitacion-hotels.storeMedia');
    Route::post('doc-habitacion-hotels/ckmedia', 'DocHabitacionHotelController@storeCKEditorImages')->name('doc-habitacion-hotels.storeCKEditorImages');
    Route::resource('doc-habitacion-hotels', 'DocHabitacionHotelController');

    // Doc Tarifa Hotel
    Route::delete('doc-tarifa-hotels/destroy', 'DocTarifaHotelController@massDestroy')->name('doc-tarifa-hotels.massDestroy');
    Route::resource('doc-tarifa-hotels', 'DocTarifaHotelController');

    // Landing Wiki Hotel
    Route::get('landing_wiki_hotel', 'LandingWikiHotelController@index')->name('landing_wiki_hotel');

});

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth', '2fa', 'admin', 'panel:admin']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');
    Route::post('permissions/sync', 'PermissionsController@sync');


    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    // User access to Societies & Hotels
    Route::get('users/access', 'UsersController@accessSelect')->name('users.access.select');
    Route::get('users/{user}/access', 'UsersController@access')->name('users.access');
    Route::post('users/{user}/access', 'UsersController@accessUpdate')->name('users.access.update');
    Route::resource('users', 'UsersController');

    // Audit Logs
    Route::resource('audit-logs', 'AuditLogsController', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);

    // FTP Upload Logs (read-only)
    Route::resource('ftp-upload-logs', 'FtpUploadLogController', ['except' => ['create', 'store', 'edit', 'update', 'destroy', 'destroy']]);

    // Audit Export
    Route::get('audit-export', 'AuditExportController@index')->name('audit-export.index');
    Route::post('audit-export', 'AuditExportController@store')->name('audit-export.store');

    // Team
    Route::delete('teams/destroy', 'TeamController@massDestroy')->name('teams.massDestroy');
    Route::resource('teams', 'TeamController');

    // Establecimiento
    Route::delete('establecimientos/destroy', 'EstablecimientoController@massDestroy')->name('establecimientos.massDestroy');
    Route::post('establecimientos/media', 'EstablecimientoController@storeMedia')->name('establecimientos.storeMedia');
    Route::post('establecimientos/ckmedia', 'EstablecimientoController@storeCKEditorImages')->name('establecimientos.storeCKEditorImages');
    Route::post('establecimientos/parse-csv-import', 'EstablecimientoController@parseCsvImport')->name('establecimientos.parseCsvImport');
    Route::post('establecimientos/process-csv-import', 'EstablecimientoController@processCsvImport')->name('establecimientos.processCsvImport');

    Route::get('establecimientos/partes-config/{id}', 'EstablecimientoController@partesConfig');
    Route::post('establecimientos/partes-config/{id}', 'EstablecimientoController@partesConfigSave');
    Route::post('establecimientos/partes-backup/{id}', 'EstablecimientoController@partesBackup');
    Route::post('establecimientos/partes-ftp-test/{id}', 'EstablecimientoController@partesFtpTest');

    Route::resource('establecimientos', 'EstablecimientoController');

    // Sociedad
    Route::delete('sociedads/destroy', 'SociedadController@massDestroy')->name('sociedads.massDestroy');
    Route::post('sociedads/media', 'SociedadController@storeMedia')->name('sociedads.storeMedia');
    Route::post('sociedads/ckmedia', 'SociedadController@storeCKEditorImages')->name('sociedads.storeCKEditorImages');
    Route::resource('sociedads', 'SociedadController');

    // Habitacion
    Route::delete('habitacions/destroy', 'HabitacionController@massDestroy')->name('habitacions.massDestroy');
    Route::post('habitacions/parse-csv-import', 'HabitacionController@parseCsvImport')->name('habitacions.parseCsvImport');
    Route::post('habitacions/process-csv-import', 'HabitacionController@processCsvImport')->name('habitacions.processCsvImport');
    Route::resource('habitacions', 'HabitacionController');

    // Totem
    Route::delete('totems/destroy', 'TotemController@massDestroy')->name('totems.massDestroy');
    Route::post('totems/media', 'TotemController@storeMedia')->name('totems.storeMedia');
    Route::post('totems/ckmedia', 'TotemController@storeCKEditorImages')->name('totems.storeCKEditorImages');
    Route::post('totems/update-mass', 'TotemController@updateMass');
    Route::resource('totems', 'TotemController');

    // Evento Home Totem
    Route::delete('evento-home-totems/destroy', 'EventoHomeTotemController@massDestroy')->name('evento-home-totems.massDestroy');
    Route::post('evento-home-totems/media', 'EventoHomeTotemController@storeMedia')->name('evento-home-totems.storeMedia');
    Route::post('evento-home-totems/ckmedia', 'EventoHomeTotemController@storeCKEditorImages')->name('evento-home-totems.storeCKEditorImages');
    Route::resource('evento-home-totems', 'EventoHomeTotemController');
    Route::post('evento-home-totems/tour-galery', 'EventoHomeTotemController@tourGalery')->name('evento-home-totems.tour-galery');
    Route::post('evento-home-totems/update-content-document','EventoHomeTotemController@updateContentDocument')->name('evento-home-totems.updateContentDocument');
    Route::post('evento-home-totems/{id}','EventoHomeTotemController@update')->name('evento-home-totems.storen');

    // Control Error
    Route::delete('control-errors/destroy', 'ControlErrorController@massDestroy')->name('control-errors.massDestroy');
    Route::resource('control-errors', 'ControlErrorController', ['except' => ['edit', 'update']]);

    // Layout Home
    Route::delete('layout-homes/destroy', 'LayoutHomeController@massDestroy')->name('layout-homes.massDestroy');
    Route::post('layout-homes/media', 'LayoutHomeController@storeMedia')->name('layout-homes.storeMedia');
    Route::post('layout-homes/ckmedia', 'LayoutHomeController@storeCKEditorImages')->name('layout-homes.storeCKEditorImages');
    Route::resource('layout-homes', 'LayoutHomeController');

    // Pais
    Route::delete('pais/destroy', 'PaisController@massDestroy')->name('pais.massDestroy');
    Route::post('pais/parse-csv-import', 'PaisController@parseCsvImport')->name('pais.parseCsvImport');
    Route::post('pais/process-csv-import', 'PaisController@processCsvImport')->name('pais.processCsvImport');
    Route::resource('pais', 'PaisController');

    // Provincia
    Route::delete('provincia/destroy', 'ProvinciaController@massDestroy')->name('provincia.massDestroy');
    Route::post('provincia/parse-csv-import', 'ProvinciaController@parseCsvImport')->name('provincia.parseCsvImport');
    Route::post('provincia/process-csv-import', 'ProvinciaController@processCsvImport')->name('provincia.processCsvImport');
    Route::resource('provincia', 'ProvinciaController');

    // Ciudad
    Route::delete('ciudads/destroy', 'CiudadController@massDestroy')->name('ciudads.massDestroy');
    Route::post('ciudads/parse-csv-import', 'CiudadController@parseCsvImport')->name('ciudads.parseCsvImport');
    Route::post('ciudads/process-csv-import', 'CiudadController@processCsvImport')->name('ciudads.processCsvImport');
    Route::resource('ciudads', 'CiudadController');


    // Check In
    Route::delete('check-ins/destroy', 'CheckInController@massDestroy')->name('check-ins.massDestroy');
    Route::post('check-ins/media', 'CheckInController@storeMedia')->name('check-ins.storeMedia');
    Route::post('check-ins/ckmedia', 'CheckInController@storeCKEditorImages')->name('check-ins.storeCKEditorImages');
    Route::post('check-ins/parte-viajero-pdf', 'CheckInController@parteViajeroPdf');
    Route::post('check-ins/list', 'CheckInController@list');
    Route::post('check-ins/pdf-download', 'CheckInController@pdfDownload');
    Route::resource('check-ins', 'CheckInController');

    // Tipo Evento
    Route::delete('tipo-eventos/destroy', 'TipoEventoController@massDestroy')->name('tipo-eventos.massDestroy');
    Route::resource('tipo-eventos', 'TipoEventoController');

    // Control Sesion
    Route::delete('control-sesions/destroy', 'ControlSesionController@massDestroy')->name('control-sesions.massDestroy');
    Route::post('control-sesions/cerrar', 'ControlSesionController@cerrar')->name('control-sesions.cerrar');
    Route::post('control-sesions/manager', 'ControlSesionController@PostEventManager')->name('control-sesions.postManager');
    Route::get('sesion-manager', 'ControlSesionController@ShowEventManager')->name('control-sesions.manager');

    //Route::post('sesion-manager/reservation-totem-event', 'ControlSesionController@reservationTotemEvent')->name('control-sesions.reservation-totem-event');

    Route::resource('control-sesions', 'ControlSesionController');



    // Configuracion Tpv
    Route::delete('configuracion-tpvs/destroy', 'ConfiguracionTpvController@massDestroy')->name('configuracion-tpvs.massDestroy');
    Route::resource('configuracion-tpvs', 'ConfiguracionTpvController');

    // Pago Totem
    Route::delete('pago-totems/destroy', 'PagoTotemController@massDestroy')->name('pago-totems.massDestroy');
    Route::resource('pago-totems', 'PagoTotemController');

    // Session
    Route::delete('sessions/destroy', 'SessionController@massDestroy')->name('sessions.massDestroy');
    Route::resource('sessions', 'SessionController', ['except' => ['create', 'store', 'edit', 'update']]);

    Route::get('team-members', 'TeamMembersController@index')->name('team-members.index');
    Route::post('team-members', 'TeamMembersController@invite')->name('team-members.invite');

    // Respuesta Pago
    Route::delete('respuesta-pagos/destroy', 'RespuestaPagoController@massDestroy')->name('respuesta-pagos.massDestroy');
    Route::resource('respuesta-pagos', 'RespuestaPagoController');

    // Grabacion Tarjeta
    Route::delete('grabacion-tarjeta/destroy', 'GrabacionTarjetaController@massDestroy')->name('grabacion-tarjeta.massDestroy');
    Route::post('grabacion-tarjeta/colocar','GrabacionTarjetaController@NotifyPutCardOnReader')->name('grabacion-tarjeta.notify-put-card-on-reader');
    Route::resource('grabacion-tarjeta', 'GrabacionTarjetaController');

    // Configuracion Grabador
    Route::delete('configuracion-grabadors/destroy', 'ConfiguracionGrabadorController@massDestroy')->name('configuracion-grabadors.massDestroy');
    Route::resource('configuracion-grabadors', 'ConfiguracionGrabadorController');

    // Respuesta Evento Home Totem
    Route::delete('respuesta-evento-home-totems/destroy', 'RespuestaEventoHomeTotemController@massDestroy')->name('respuesta-evento-home-totems.massDestroy');
    Route::resource('respuesta-evento-home-totems', 'RespuestaEventoHomeTotemController');

    // Configuracion Video
    Route::resource('configuracion-videos', 'ConfiguracionVideoController');


    // Ayuda Step Totem
    Route::delete('ayuda-step-totems/destroy', 'AyudaStepTotemController@massDestroy')->name('ayuda-step-totems.massDestroy');
    Route::post('ayuda-step-totems/media', 'AyudaStepTotemController@storeMedia')->name('ayuda-step-totems.storeMedia');
    Route::post('ayuda-step-totems/ckmedia', 'AyudaStepTotemController@storeCKEditorImages')->name('ayuda-step-totems.storeCKEditorImages');
    Route::resource('ayuda-step-totems', 'AyudaStepTotemController');

    // Parte Viajero
    Route::delete('parte-viajeros/destroy', 'ParteViajeroController@massDestroy')->name('parte-viajeros.massDestroy');
    Route::resource('parte-viajeros', 'ParteViajeroController');


    Route::get('call-manager', 'CallManagerController@index')->name('call-manager.index');
    Route::get('call-manager/payment-table/{folio_id}', 'CallManagerController@paymentTable')->name('call-manager.paymentTable');
    Route::get('call-manager/write-card-table', 'CallManagerController@writeCardTable')->name('call-manager.writeCardTable');
    Route::post('call-manager/document-view', 'CallManagerController@documentView')->name('call-manager.documentView');
    Route::post('call-manager/sign-view', 'CallManagerController@signView')->name('call-manager.signView');
    Route::post('call-manager/parte-send-mail', 'CallManagerController@parteSendMail');
    Route::post('call-manager/llamada-inversa', 'CallManagerController@llamadaInversa');
    Route::post('call-manager/recommendations', 'CallManagerController@recommendations')->name('call-manager.recommendations');
    Route::post('call-manager/reservation-recommendations', 'CallManagerController@reservationRecommendations')->name('call-manager.reservation-recommendations');
    Route::post('call-manager/huesped-relacion', 'CallManagerController@huespedRelacion');
    Route::post('call-manager/cerrar-parte-viajero-totem', 'CallManagerController@cerrarParteViajeroTotem');
    Route::post('call-manager/huesped-info', 'CallManagerController@huespedInfo');


    Route::get('call-manager/reservation-resumen/{id}', 'CallManagerController@reservationResumen')->name('call-manager.reservationResumen');



    // Zona Comun
    Route::delete('zona-comuns/destroy', 'ZonaComunController@massDestroy')->name('zona-comuns.massDestroy');
    Route::post('zona-comuns/parse-csv-import', 'ZonaComunController@parseCsvImport')->name('zona-comuns.parseCsvImport');
    Route::post('zona-comuns/process-csv-import', 'ZonaComunController@processCsvImport')->name('zona-comuns.processCsvImport');
    Route::resource('zona-comuns', 'ZonaComunController');


    // Reservas Api
    Route::get('reservation-api/folio-search', 'ReservationApiController@folioSearch');
    Route::get('reservation-api/folio-detail', 'ReservationApiController@folioDetail');
    Route::post('reservation-api/folio-detail-select', 'ReservationApiController@folioDetailSelect');
    Route::get('reservation-api/folio-status', 'ReservationApiController@folioStatus');

    Route::get('reservation-api/reservation-select/{id}', 'ReservationApiController@reservationSelect');
    Route::get('reservation-api/countries-states/{id}', 'ReservationApiController@countriesStates');

    Route::post('reservation-api/checkin-partner', 'ReservationApiController@checkinPartner');
    Route::post('reservation-api/checkin', 'ReservationApiController@checkin');


    Route::get('alice/index/{id}', 'AliceController@index');
    Route::get('alice/status/{id}', 'AliceController@status');
    Route::post('alice/events/{id}', 'AliceController@events');

    Route::get('sh360-ocr/index/{id}', 'Sh360OcrController@index');

    // Reserva
    //Route::delete('reservas/destroy', 'ReservaController@massDestroy')->name('reservas.massDestroy');
    Route::get('reservas/index', 'ReservaController@index')->name('reservas.index');
    Route::post('reservas/buscar-disponibilidad', 'ReservaController@buscarDisponibilidad');
    Route::post('reservas/reservation-save', 'ReservaController@reservationSave');
    Route::post('reservas/reservation-info', 'ReservaController@reservationInfo');
    Route::post('reservas/reservation-search', 'ReservaController@reservationSearch');
    Route::post('reservas/folio-edit', 'ReservaController@folioEdit');
    Route::post('reservas/folio-update', 'ReservaController@folioUpdate');
    Route::post('reservas/reservation-update', 'ReservaController@reservationUpdate');
    Route::post('reservas/reservation-add', 'ReservaController@reservationAdd');
    Route::post('reservas/import', 'ReservaController@import');


    Route::get('reservas/test', 'ReservaController@test');
    //Route::resource('reservas', 'ReservaController');

    Route::resource('rooms', 'RoomController');
    Route::post('rooms/list', 'RoomController@list');

    Route::resource('rooms-types', 'RoomTypeController');
    Route::post('rooms-types/list', 'RoomTypeController@list');

    Route::resource('rooms-types-prices', 'RoomTypePriceController');
    Route::post('rooms-types-prices/list', 'RoomTypePriceController@list');

    Route::resource('avails', 'AvailController');
    Route::post('avails/list', 'AvailController@list');

    //Route::get('alice/front', 'AliceController@front');
    //Route::get('alice/login', 'AliceController@login');

    // Doc Hotel Reception Info
    Route::delete('doc-hotel-reception-infos/destroy', 'DocHotelReceptionInfoController@massDestroy')->name('doc-hotel-reception-infos.massDestroy');
    Route::post('doc-hotel-reception-infos/media', 'DocHotelReceptionInfoController@storeMedia')->name('doc-hotel-reception-infos.storeMedia');
    Route::post('doc-hotel-reception-infos/ckmedia', 'DocHotelReceptionInfoController@storeCKEditorImages')->name('doc-hotel-reception-infos.storeCKEditorImages');
    Route::resource('doc-hotel-reception-infos', 'DocHotelReceptionInfoController');

    // Doc Hotel Estado Caja
    Route::delete('doc-hotel-estado-cajas/destroy', 'DocHotelEstadoCajaController@massDestroy')->name('doc-hotel-estado-cajas.massDestroy');
    Route::post('doc-hotel-estado-cajas/media', 'DocHotelEstadoCajaController@storeMedia')->name('doc-hotel-estado-cajas.storeMedia');
    Route::post('doc-hotel-estado-cajas/ckmedia', 'DocHotelEstadoCajaController@storeCKEditorImages')->name('doc-hotel-estado-cajas.storeCKEditorImages');
    Route::post('doc-hotel-estado-cajas/parse-csv-import', 'DocHotelEstadoCajaController@parseCsvImport')->name('doc-hotel-estado-cajas.parseCsvImport');
    Route::post('doc-hotel-estado-cajas/process-csv-import', 'DocHotelEstadoCajaController@processCsvImport')->name('doc-hotel-estado-cajas.processCsvImport');
    Route::resource('doc-hotel-estado-cajas', 'DocHotelEstadoCajaController');

    // Doc Info Hotel
    Route::delete('doc-info-hotels/destroy', 'DocInfoHotelController@massDestroy')->name('doc-info-hotels.massDestroy');
    Route::post('doc-info-hotels/media', 'DocInfoHotelController@storeMedia')->name('doc-info-hotels.storeMedia');
    Route::post('doc-info-hotels/ckmedia', 'DocInfoHotelController@storeCKEditorImages')->name('doc-info-hotels.storeCKEditorImages');
    Route::resource('doc-info-hotels', 'DocInfoHotelController');

    // Doc Servicio Hotel
    Route::delete('doc-servicio-hotels/destroy', 'DocServicioHotelController@massDestroy')->name('doc-servicio-hotels.massDestroy');
    Route::resource('doc-servicio-hotels', 'DocServicioHotelController');

    // Doc Metodo Pago Hotel
    Route::delete('doc-metodo-pago-hotels/destroy', 'DocMetodoPagoHotelController@massDestroy')->name('doc-metodo-pago-hotels.massDestroy');
    Route::post('doc-metodo-pago-hotels/parse-csv-import', 'DocMetodoPagoHotelController@parseCsvImport')->name('doc-metodo-pago-hotels.parseCsvImport');
    Route::post('doc-metodo-pago-hotels/process-csv-import', 'DocMetodoPagoHotelController@processCsvImport')->name('doc-metodo-pago-hotels.processCsvImport');
    Route::resource('doc-metodo-pago-hotels', 'DocMetodoPagoHotelController');

    // Doc Ubicacion Hotel
    Route::delete('doc-ubicacion-hotels/destroy', 'DocUbicacionHotelController@massDestroy')->name('doc-ubicacion-hotels.massDestroy');
    Route::post('doc-ubicacion-hotels/media', 'DocUbicacionHotelController@storeMedia')->name('doc-ubicacion-hotels.storeMedia');
    Route::post('doc-ubicacion-hotels/ckmedia', 'DocUbicacionHotelController@storeCKEditorImages')->name('doc-ubicacion-hotels.storeCKEditorImages');
    Route::resource('doc-ubicacion-hotels', 'DocUbicacionHotelController');

    // Dock Stock Hotel
    Route::delete('dock-stock-hotels/destroy', 'DockStockHotelController@massDestroy')->name('dock-stock-hotels.massDestroy');
    Route::post('dock-stock-hotels/media', 'DockStockHotelController@storeMedia')->name('dock-stock-hotels.storeMedia');
    Route::post('dock-stock-hotels/ckmedia', 'DockStockHotelController@storeCKEditorImages')->name('dock-stock-hotels.storeCKEditorImages');
    Route::resource('dock-stock-hotels', 'DockStockHotelController');

    // Doc Incidencia Hotel
    Route::delete('doc-incidencia-hotels/destroy', 'DocIncidenciaHotelController@massDestroy')->name('doc-incidencia-hotels.massDestroy');
    Route::post('doc-incidencia-hotels/media', 'DocIncidenciaHotelController@storeMedia')->name('doc-incidencia-hotels.storeMedia');
    Route::post('doc-incidencia-hotels/ckmedia', 'DocIncidenciaHotelController@storeCKEditorImages')->name('doc-incidencia-hotels.storeCKEditorImages');
    Route::resource('doc-incidencia-hotels', 'DocIncidenciaHotelController');

    // Doc No Deseado Hotel
    Route::delete('doc-no-deseado-hotels/destroy', 'DocNoDeseadoHotelController@massDestroy')->name('doc-no-deseado-hotels.massDestroy');
    Route::post('doc-no-deseado-hotels/media', 'DocNoDeseadoHotelController@storeMedia')->name('doc-no-deseado-hotels.storeMedia');
    Route::post('doc-no-deseado-hotels/ckmedia', 'DocNoDeseadoHotelController@storeCKEditorImages')->name('doc-no-deseado-hotels.storeCKEditorImages');
    Route::resource('doc-no-deseado-hotels', 'DocNoDeseadoHotelController');

    // Doc Habitacion Hotel
    Route::delete('doc-habitacion-hotels/destroy', 'DocHabitacionHotelController@massDestroy')->name('doc-habitacion-hotels.massDestroy');
    Route::post('doc-habitacion-hotels/media', 'DocHabitacionHotelController@storeMedia')->name('doc-habitacion-hotels.storeMedia');
    Route::post('doc-habitacion-hotels/ckmedia', 'DocHabitacionHotelController@storeCKEditorImages')->name('doc-habitacion-hotels.storeCKEditorImages');
    Route::resource('doc-habitacion-hotels', 'DocHabitacionHotelController');

    // Doc Tarifa Hotel
    Route::delete('doc-tarifa-hotels/destroy', 'DocTarifaHotelController@massDestroy')->name('doc-tarifa-hotels.massDestroy');
    Route::resource('doc-tarifa-hotels', 'DocTarifaHotelController');

    // Landing Wiki Hotel
    Route::get('landing_wiki_hotel', 'LandingWikiHotelController@index')->name('landing_wiki_hotel');

    Route::get('reservation-api/reservation-costs/{id}', 'ReservationApiController@reservationCosts')->name('reservation-api.reservation-costs');

});



Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth', '2fa']], function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
        Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
        Route::post('profile/two-factor', 'ChangePasswordController@toggleTwoFactor')->name('password.toggleTwoFactor');
        Route::post('profile/update-pms-password', 'ChangePasswordController@updatePmsPassword')->name('password.updatePmsPassword');
    }
});
Route::group(['as' => 'frontend.', 'namespace' => 'Frontend', 'middleware' => ['auth', '2fa']], function () {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/home2', 'HomeController@index');

    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');


    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // Team
    Route::delete('teams/destroy', 'TeamController@massDestroy')->name('teams.massDestroy');
    Route::resource('teams', 'TeamController');

    // Establecimiento
    Route::delete('establecimientos/destroy', 'EstablecimientoController@massDestroy')->name('establecimientos.massDestroy');
    Route::post('establecimientos/media', 'EstablecimientoController@storeMedia')->name('establecimientos.storeMedia');
    Route::post('establecimientos/ckmedia', 'EstablecimientoController@storeCKEditorImages')->name('establecimientos.storeCKEditorImages');
    Route::resource('establecimientos', 'EstablecimientoController');

    // Sociedad
    Route::delete('sociedads/destroy', 'SociedadController@massDestroy')->name('sociedads.massDestroy');
    Route::post('sociedads/media', 'SociedadController@storeMedia')->name('sociedads.storeMedia');
    Route::post('sociedads/ckmedia', 'SociedadController@storeCKEditorImages')->name('sociedads.storeCKEditorImages');
    Route::resource('sociedads', 'SociedadController');

    // Habitacion
    Route::delete('habitacions/destroy', 'HabitacionController@massDestroy')->name('habitacions.massDestroy');
    Route::resource('habitacions', 'HabitacionController');

    // Totem
    Route::delete('totems/destroy', 'TotemController@massDestroy')->name('totems.massDestroy');
    Route::post('totems/media', 'TotemController@storeMedia')->name('totems.storeMedia');
    Route::post('totems/ckmedia', 'TotemController@storeCKEditorImages')->name('totems.storeCKEditorImages');
    Route::resource('totems', 'TotemController');

    // Evento Home Totem
    Route::delete('evento-home-totems/destroy', 'EventoHomeTotemController@massDestroy')->name('evento-home-totems.massDestroy');
    Route::post('evento-home-totems/media', 'EventoHomeTotemController@storeMedia')->name('evento-home-totems.storeMedia');
    Route::post('evento-home-totems/ckmedia', 'EventoHomeTotemController@storeCKEditorImages')->name('evento-home-totems.storeCKEditorImages');
    Route::resource('evento-home-totems', 'EventoHomeTotemController');

    Route::get('evento-home-totems/reservation/{id}','EventoHomeTotemController@reservation')->name('evento-home-totems.reservation');
    Route::get('evento-home-totems/parte-viajero-header/{id}','EventoHomeTotemController@parteViajeroHeader')->name('evento-home-totems.parteViajeroHeader');
    Route::post('evento-home-totems/pago-pdf','EventoHomeTotemController@pagoPdf');
    Route::post('evento-home-totems/{id}','EventoHomeTotemController@update')->name('evento-home-totems.storen');


    // Control Error
    Route::delete('control-errors/destroy', 'ControlErrorController@massDestroy')->name('control-errors.massDestroy');
    Route::resource('control-errors', 'ControlErrorController', ['except' => ['edit', 'update']]);

    // Layout Home
    Route::delete('layout-homes/destroy', 'LayoutHomeController@massDestroy')->name('layout-homes.massDestroy');
    Route::post('layout-homes/media', 'LayoutHomeController@storeMedia')->name('layout-homes.storeMedia');
    Route::post('layout-homes/ckmedia', 'LayoutHomeController@storeCKEditorImages')->name('layout-homes.storeCKEditorImages');
    Route::resource('layout-homes', 'LayoutHomeController');


    // Pais
    Route::delete('pais/destroy', 'PaisController@massDestroy')->name('pais.massDestroy');
    Route::resource('pais', 'PaisController');

    // Provincia
    Route::delete('provincia/destroy', 'ProvinciaController@massDestroy')->name('provincia.massDestroy');
    Route::resource('provincia', 'ProvinciaController');

    // Ciudad
    Route::delete('ciudads/destroy', 'CiudadController@massDestroy')->name('ciudads.massDestroy');
    Route::resource('ciudads', 'CiudadController');

    // Reserva
    //Route::delete('reservas/destroy', 'ReservaController@massDestroy')->name('reservas.massDestroy');
    //Route::resource('reservas', 'ReservaController');


    // Check In
    Route::delete('check-ins/destroy', 'CheckInController@massDestroy')->name('check-ins.massDestroy');
    Route::post('check-ins/media', 'CheckInController@storeMedia')->name('check-ins.storeMedia');
    Route::post('check-ins/ckmedia', 'CheckInController@storeCKEditorImages')->name('check-ins.storeCKEditorImages');
    Route::post('check-ins/screen-camera', 'CheckInController@screenCamera')->name('check-ins.screenCamera');
    Route::resource('check-ins', 'CheckInController');


    // Tipo Evento
    Route::delete('tipo-eventos/destroy', 'TipoEventoController@massDestroy')->name('tipo-eventos.massDestroy');
    Route::resource('tipo-eventos', 'TipoEventoController');

    // Control Sesion
    Route::delete('control-sesions/destroy', 'ControlSesionController@massDestroy')->name('control-sesions.massDestroy');
    Route::resource('control-sesions', 'ControlSesionController');

    // Respuesta Pago
    Route::delete('respuesta-pagos/destroy', 'RespuestaPagoController@massDestroy')->name('respuesta-pagos.massDestroy');
    Route::resource('respuesta-pagos', 'RespuestaPagoController');

    // Respuesta Evento Home Totem
    Route::delete('respuesta-evento-home-totems/destroy', 'RespuestaEventoHomeTotemController@massDestroy')->name('respuesta-evento-home-totems.massDestroy');
    Route::resource('respuesta-evento-home-totems', 'RespuestaEventoHomeTotemController');

    // Configuracion Video
    Route::resource('configuracion-videos', 'ConfiguracionVideoController', ['except' => ['destroy']]);




    // Ayuda Step Totem
    Route::delete('ayuda-step-totems/destroy', 'AyudaStepTotemController@massDestroy')->name('ayuda-step-totems.massDestroy');
    Route::post('ayuda-step-totems/media', 'AyudaStepTotemController@storeMedia')->name('ayuda-step-totems.storeMedia');
    Route::post('ayuda-step-totems/ckmedia', 'AyudaStepTotemController@storeCKEditorImages')->name('ayuda-step-totems.storeCKEditorImages');
    Route::resource('ayuda-step-totems', 'AyudaStepTotemController');


    Route::post('alice/capture', 'AliceController@capture');
    Route::post('alice/notification', 'AliceController@notification');


    Route::get('frontend/profile', 'ProfileController@index')->name('profile.index');
    Route::post('frontend/profile', 'ProfileController@update')->name('profile.update');
    Route::post('frontend/profile/destroy', 'ProfileController@destroy')->name('profile.destroy');
    Route::post('frontend/profile/password', 'ProfileController@password')->name('profile.password');
    Route::post('profile/toggle-two-factor', 'ProfileController@toggleTwoFactor')->name('profile.toggle-two-factor');

});
Route::group(['namespace' => 'Auth', 'middleware' => ['auth', '2fa']], function () {
    // Two Factor Authentication
    if (file_exists(app_path('Http/Controllers/Auth/TwoFactorController.php'))) {
        Route::get('two-factor', 'TwoFactorController@show')->name('twoFactor.show');
        Route::post('two-factor', 'TwoFactorController@check')->name('twoFactor.check');
        Route::get('two-factor/resend', 'TwoFactorController@resend')->name('twoFactor.resend');
    }
});


//Route::get('test/pago_respuesta_ok', 'TestController@pago_respuesta_ok');
//Route::get('test/login', 'TestController@login');
//Route::get('test/pdf', 'TestController@pdf');
Route::get('test/{opc}', 'TestController@index');

//Route::get('alice/login', 'AliceController@login');
//Route::get('alice/front', 'AliceController@front');

// Swagger UI for Local PMS API
Route::get('/docs', function () {
    return view('docs.swagger');
})->name('docs.swagger');

// Admin Swagger UI (complete API docs) - protected
Route::get('admin/docs/api', function () {
    return view('docs.swagger');
})->middleware(['auth', '2fa', 'can:api_doc_access'])->name('admin.docs.api');

// Serve the Local PMS OpenAPI YAML
Route::get('/docs/local-pms.yaml', function () {
    $path = base_path('docs/local-pms.yaml');
    if (!\Illuminate\Support\Facades\File::exists($path)) {
        abort(404, 'OpenAPI file not found');
    }
    return response()->file($path, [
        'Content-Type' => 'application/yaml',
        'Cache-Control' => 'no-cache',
    ]);
})->name('docs.local_pms_yaml');


// Email send route for traveller forms from admin check-ins list (protected)
Route::post('admin/check-ins/send-mail', [\App\Http\Controllers\Admin\CheckInController::class, 'sendMail'])
    ->middleware(['auth','2fa'])
    ->name('admin.check-ins.sendMail');
