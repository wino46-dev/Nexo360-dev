# API de SH-360 — Endpoints añadidos (Wiki Hotel y Call Manager)

Esta guía explica cómo autenticarse y cómo usar los endpoints añadidos para:
- Wiki Hotel: incidencias abiertas y lista de no deseados por hotel.
- Call Manager: búsqueda de folios, detalle de folio y detalle de reserva (PMS-agnóstico vía HotelApiService).

Todos los endpoints descritos aquí están bajo el prefijo `/api/v1` y requieren autenticación `auth:sanctum` y permisos adecuados.

## 1) Autenticación

1. Obtener token (ejemplo):
   - Endpoint: `POST /api/login`
   - Body JSON: `{ "email": "tu@correo.com", "password": "tu_password" }`
   - Respuesta: incluye un `token` Sanctum.

2. Usar el token en cada petición:
   - Header: `Authorization: Bearer {TOKEN}`
   - Header: `Accept: application/json`

## 2) Wiki Hotel

Permisos requeridos (Gates):
- Incidencias: `doc_incidencia_hotel_access`
- No deseados: `doc_no_deseado_hotel_access`

Endpoints:
- Incidencias abiertas por hotel
  - Método: `GET`
  - Path: `/api/v1/wiki-hotel/establecimientos/{establecimiento}/incidencias-abiertas`
  - Path param: `{establecimiento}` = ID del hotel (establecimientos.id)
  - Respuesta: JSON con array de incidencias (estado = "Abierta")

- No deseados por hotel
  - Método: `GET`
  - Path: `/api/v1/wiki-hotel/establecimientos/{establecimiento}/no-deseados`
  - Path param: `{establecimiento}` = ID del hotel (establecimientos.id)
  - Respuesta: JSON con array de registros "no deseados"

Ejemplos cURL:
```bash
curl -X GET "https://TU_DOMINIO/api/v1/wiki-hotel/establecimientos/12/incidencias-abiertas" \
  -H "Authorization: Bearer {TOKEN}" \
  -H "Accept: application/json"

curl -X GET "https://TU_DOMINIO/api/v1/wiki-hotel/establecimientos/12/no-deseados" \
  -H "Authorization: Bearer {TOKEN}" \
  -H "Accept: application/json"
```

Notas:
- Las respuestas usan los Resources `DocIncidenciaHotelResource` y `DocNoDeseadoHotelResource` cargando la relación `establecimiento`.
- Se devuelve 403 si el usuario no tiene permiso.

## 3) Call Manager (flujo de reservas vía HotelApiService)

Permiso requerido (Gate): `reserva_access`.

Resumen del flujo (igual que el Call Manager en admin):
1) Buscar folios por nombre entre fechas y hotel → 2) obtener detalle del folio → 3) obtener detalle de la reserva (incluye líneas según PMS).

Endpoints:
1. Buscar folios
   - Método: `GET`
   - Path: `/api/v1/call-manager/folios/search`
   - Query params (validación interna):
     - `establecimiento_id` (required, integer, exists)
     - `q` (optional, string) — nombre a buscar
     - `date_start` (optional, date) — formato `YYYY-MM-DD`
     - `date_end` (optional, date)
     - `type` (optional, string) — alternativa cuando no se usa `q`/fechas (según PMS)
   - Respuesta: `{ "data": [ ...folios... ] }`

2. Detalle de folio
   - Método: `GET`
   - Path: `/api/v1/call-manager/folios/{folio}/detail`
   - Path param: `{folio}` = ID de folio en el PMS
   - Query param: `establecimiento_id` (required)
   - Respuesta: `{ "data": { ...folio... } }`

3. Detalle de reserva
   - Método: `GET`
   - Path: `/api/v1/call-manager/reservations/{reservation}/detail`
   - Path param: `{reservation}` = ID de la reserva en el PMS
   - Query param: `establecimiento_id` (required)
   - Respuesta: `{ "data": { ...reservation (con líneas)... } }`

Ejemplos cURL:
```bash
# 1) Buscar folios por nombre entre fechas para un hotel
curl -G "https://TU_DOMINIO/api/v1/call-manager/folios/search" \
  -H "Authorization: Bearer {TOKEN}" \
  -H "Accept: application/json" \
  --data-urlencode "establecimiento_id=12" \
  --data-urlencode "q=García" \
  --data-urlencode "date_start=2025-10-01" \
  --data-urlencode "date_end=2025-10-31"

# 2) Detalle del folio
curl -X GET "https://TU_DOMINIO/api/v1/call-manager/folios/ABC123/detail?establecimiento_id=12" \
  -H "Authorization: Bearer {TOKEN}" \
  -H "Accept: application/json"

# 3) Detalle de la reserva
curl -X GET "https://TU_DOMINIO/api/v1/call-manager/reservations/RESV789/detail?establecimiento_id=12" \
  -H "Authorization: Bearer {TOKEN}" \
  -H "Accept: application/json"
```

Notas:
- Todos los endpoints usan `HotelApiService` por lo que son agnósticos del PMS (Roomdoo, MisterPlan, Local, etc.).
- Si el PMS devuelve un error, el endpoint propagará `{ error: ... }` con HTTP 500.
- Asegúrate de pasar `establecimiento_id` del hotel correcto en cada llamada del flujo.

## 4) Disponibilidad de habitaciones (vía HotelApiService)

Permiso requerido (Gate): `reserva_access`.

Endpoint:
- Disponibilidad por tipo de habitación
  - Método: `GET`
  - Path: `/api/v1/call-manager/availability/by-room-type`
  - Query params (validación interna):
    - `establecimiento_id` (required, integer, exists)
    - `date_start` (required, date `YYYY-MM-DD`)
    - `date_end` (required, date `YYYY-MM-DD`, debe ser posterior a `date_start`)
    - `adults` (optional, integer)
    - `children` (optional, integer)
    - `rooms` (optional, integer)
    - `room_type_ids[]` (optional, array de IDs de tipos de habitación)
    - Otros parámetros adicionales se aceptan y se pasan al PMS si se proporcionan.
  - Respuesta: `{ "data": [ ...availability... ] }` (estructura depende del PMS)

Ejemplo cURL:
```bash
curl -G "https://TU_DOMINIO/api/v1/call-manager/availability/by-room-type" \
  -H "Authorization: Bearer {TOKEN}" \
  -H "Accept: application/json" \
  --data-urlencode "establecimiento_id=12" \
  --data-urlencode "date_start=2025-10-20" \
  --data-urlencode "date_end=2025-10-23" \
  --data-urlencode "adults=2" \
  --data-urlencode "children=1"
```

Notas:
- El endpoint es agnóstico de PMS y usa `HotelApiService::availsByRoomType`.
- Si el PMS devuelve un error, la API lo propagará con HTTP 500.

## 5) Respuestas y errores comunes
- 200: OK
- 401: No autenticado (falta o es inválido el token)
- 403: Prohibido (falta permiso o el hotel no está autorizado para el usuario)
- 422: Validación (faltan params requeridos — p.ej. `establecimiento_id`)
- 500: Error interno o error propagado por el PMS

## 5) Consejos de uso (Postman/Insomnia)
- Configura una colección con el header `Authorization: Bearer {{token}}` y `Accept: application/json`.
- Define variables de entorno: `baseUrl`, `token`, `establecimiento_id`.
- Construye el flujo: 1) search → 2) folio detail (tomando `id` del resultado) → 3) reservation detail.

## 6) Preguntas frecuentes
- ¿Cómo obtengo `establecimiento_id`? Debe existir en `establecimientos.id` de tu base.
- ¿Qué formato de fechas aceptar? `YYYY-MM-DD` para `date_start` y `date_end`.
- ¿Respuestas paginadas? Estos endpoints retornan colecciones completas según la lógica actual; si se requiere paginación, se puede extender.
