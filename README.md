<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 2000 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[OP.GG](https://op.gg)**
- **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
- **[Lendio](https://lendio.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

# SH-360

Este proyecto es una aplicación Laravel con integración en tiempo real (Pusher) y telefonía SIP vía WebRTC/Janus para comunicación con 3CX.

## Flujo de Llamadas (3CX / Llamada Inversa)

Resumen de cómo funciona la llamada (normal e inversa) entre el Agente (Backoffice) y el Tótem (Frontend):

- Frontend (Tótem): recursos en `resources/views/frontend/home/`
  - scripts.blade.php: suscribe a canal Pusher `alda_events` y escucha el evento `TotemHomeEvent`.
  - page_llamada.blade.php: muestra la página de llamada e invoca funciones JS para iniciar la llamada.
  - vue/callVue.blade.php: instancia Vue y expone `miVueApp` con métodos `llamada_iniciar`, `llamada_inversa_iniciar`, `llamada_colgar`.
  - vue/Phone.blade.php y vue/PhoneCommander.blade.php: implementación del softphone sobre WebRTC utilizando Janus SIP plugin. Se conecta a un gateway WebRTC (wss Janus) y realiza/recibe llamadas SIP hacia/desde 3CX usando las credenciales SIP del usuario/caller (`$caller`).

- Backend (Agente): rutas en `routes/web.php` y controlador `app/Http/Controllers/Admin/CallManagerController.php`.
  - Ruta POST `admin/call-manager/llamada-inversa` -> método `llamadaInversa`.
  - `llamadaInversa` valida que el usuario emisor tenga `sip_identity` y que exista una `ControlSesion` activa (emisor=receptor totem). Si todo está correcto, emite el evento de broadcast `SendTotemHomeEvent` con:
    - `tipo_evento_id: 'reverse_call'`
    - `receptor_id`: usuario del tótem
    - `emisor_id`: agente
    - `mensaje`: `sip_identity` del agente (destino de la llamada)

- Tiempo real (Broadcasting):
  - Evento `App\Events\SendTotemHomeEvent` se emite en el canal público `alda_events` con alias `TotemHomeEvent`.
  - El tótem escucha el evento y, cuando recibe `tipo_evento_id = 'reverse_call'`, ejecuta:
    - `openCamera()` y `llamada_inversa(mensaje)` donde `mensaje` es el SIP Identity de destino.
    - `llamada_inversa` llama a `miVueApp.llamada_inversa_iniciar(sipIdentityDestino)` que a su vez invoca `llamar_inversa()` del componente Vue `neo-call`, terminando en `window.instanciaPhone.doCall('sip:' + sipIdentityDestino)` a través de Janus SIP.

- Servidor WebRTC/SIP:
  - PhoneCommander usa Janus (`/js/janus.js`) con `window.config.webrtcServer = wss://gateway.norvoz.es:8989` para exponer un endpoint WebRTC que habla SIP con 3CX.
  - Las credenciales SIP (identity, registrar, username, password, destinos) se inyectan desde backend en `vue/Phone.blade.php` vía `$caller`.

### Llamada normal vs Llamada inversa

Nota 3CX v20: Para llamadas que pasan por COLA, 3CX requiere que el endpoint OFREZCA VIDEO en el INVITE inicial para permitir videorrenegociación. Se ha actualizado el softphone WebRTC para incluir pista de video en el SDP inicial (m=video) en llamadas salientes del Tótem, tanto directas como de llamada inversa.
- Llamada normal (desde Tótem):
  - page_llamada -> `page11_llamar()` -> `miVueApp.llamada_iniciar()` -> `neo-call.llamar()` -> `doCall(this.sipIdentityDestino)` hacia el destino preconfigurado.
- Llamada inversa (iniciada desde Backoffice/Agente):
  - Backoffice click botón -> AJAX POST a `admin/call-manager/llamada-inversa`.
  - Backend emite evento `reverse_call` con SIP del agente.
  - Tótem recibe evento y ejecuta `llamada_inversa(sipIdentityAgente)` -> `doCall('sip:' + sipIdentityAgente)`.

### Archivos Clave
- Rutas: `routes/web.php` (sección call-manager)
- Controlador: `app/Http/Controllers/Admin/CallManagerController.php` (método `llamadaInversa`)
- Evento: `app/Events/SendTotemHomeEvent.php`
- Frontend receptor (Pusher): `resources/views/frontend/home/scripts.blade.php`
- Frontend llamada WebRTC/SIP: `resources/views/frontend/home/vue/Phone*.blade.php`

### Respuestas de API
- `POST admin/call-manager/llamada-inversa`
  - Respuesta success: `{ status: 'ok', message: 'Llamada inversa solicitada correctamente.' }`
  - Errores posibles (HTTP 200):
    - `{ status: 'error', message: 'No está definido el SIP Identity para el usuario.' }`
    - `{ status: 'error', message: 'No hay una sesión activa con un tótem asignado.' }`

Este flujo permite que el agente fuerce una llamada hacia el tótem utilizando la identidad SIP del agente en 3CX, transportada por WebRTC a través de Janus.
