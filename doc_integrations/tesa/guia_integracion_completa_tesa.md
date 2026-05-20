# Guía de Integración Completa: SH-360 + Tesa (SmartAir)

Esta guía describe el proceso de configuración y conexión entre el sistema central SH-360 y el software de cerraduras Tesa SmartAir.

---

## 1. Configuración del Servidor (Backend SH-360)

### A. Variables de Entorno (.env)
Asegurarse de que el servidor SH-360 tenga definido el evento específico para Tesa para evitar conflictos con otros grabadores. El brazo obtendrá este valor dinámicamente:
```env
# Nombre del evento que SH-360 emitirá por Pusher
PUSHER_EVENT_CARD_TESA="WriteCardTesa"

# (Opcional) Canal de Pusher si es distinto al de defecto
PUSHER_CHANEL="alda_events"
```

### B. Configuración en el Backoffice
1.  **Perfil del Tótem:** En el panel de administración, ir a la sección de Tótems.
2.  **Configurador de Grabador:**
    -   **Software de Gestión:** Seleccionar o escribir `tesa`.
    -   **JSON Grabador:** Introducir los datos de conexión al servidor SmartAir local:
        ```json
        {
          "soap_wsdl": "https://<IP_SERVIDOR_TESA>:8181/ServerPlatform/GuestsWebService?wsdl",
          "operator_name": "director",
          "operator_password": "PASSWORD_DE_TESA",
          "encoder_name": "DESKTOP 3"
        }
        ```
    *Nota: Se recomienda incluir el `encoder_name` (ej: "DESKTOP 3") para usarlo con el método `encoderGetByNameorIP`.*
    *Nota: Estos datos son los que el brazo recibirá en el campo `json_grabador` del evento Pusher y en su perfil inicial.*

---

## 2. Implementación del "Brazo" (Middleware Python)

El "Brazo" es una aplicación Python que corre en el hotel (localmente) y escucha las órdenes del servidor.

### Flujo de Trabajo del Brazo:
1.  **Autenticación:** El brazo hace login en la API de SH-360 con credenciales de Tótem.
2.  **Sincronización:** Obtiene el `profile` de la API, que incluye las llaves de Pusher y el JSON de configuración de Tesa.
3.  **Suscripción:** Se conecta a Pusher y escucha el evento `WriteCardTesa`.
4.  **Ejecución:** Al recibir el evento:
    -   Extrae los datos de la estancia (habitación, fechas).
    -   Llama al servicio SOAP de Tesa (`authenticateAndWriteCard`).
5.  **Respuesta:** Envía un `PUT` a la API de SH-360 indicando si la grabación fue correcta (`Success`) o falló (`Fail`).

---

## 3. Funcionamiento de la Grabación (Paso a Paso)

1.  **Check-in:** El recepcionista o el huésped (en el Tótem) inicia la grabación de tarjeta.
2.  **Generación del Registro:** El sistema crea un registro en la tabla `grabacion_tarjeta` con los datos de la estancia.
3.  **Evento:** SH-360 detecta que el proveedor es `tesa` y lanza un evento Pusher al canal `alda_events` con el nombre `WriteCardTesa`.
    -   *Origen del Payload:* El payload se construye en `GrabacionTarjetaController@store` uniendo los datos del formulario de grabación con la configuración del establecimiento y el tótem.
4.  **Recepción:** El Brazo Python recibe el mensaje al instante.
5.  **Grabación Física:** El Brazo invoca al servidor Tesa SmartAir vía SOAP.
6.  **Finalización:**
    -   El Brazo actualiza el registro en SH-360 vía API.
    -   SH-360 lanza un evento de respuesta (`WriteCardResponse`).
    -   La interfaz del usuario se actualiza confirmando que la tarjeta está lista.

---

## 4. Detalle del Payload de Pusher (Origen y Configuración)

El mensaje que recibe el brazo contiene campos que provienen de distintas partes del sistema:

| Campo | Origen / Dónde se configura | Descripción |
| :--- | :--- | :--- |
| `sesion_id` | API de Entrada (Check-in) | ID de la sesión actual del huésped. |
| `emisor_id` | Usuario Autenticado | ID del usuario/recepcionista que lanza la orden. |
| `receptor_id` | Perfil del Tótem | ID del usuario tipo "Totem" que debe procesar la orden. |
| `transaccion_id` | Tabla `grabacion_tarjeta` | ID incremental del registro de grabación generado. |
| `status` | Lógica de Negocio | Estado inicial (normalmente `pending`). |
| `provider` | Backoffice -> Establecimiento | Se configura en la ficha del Establecimiento (campo `proveedor_cerradura`). |
| `tipo_grabador` | Backoffice -> Tótem | Valor `tesa` configurado en el `software_gestion` del grabador. |
| `json_grabador` | Backoffice -> Tótem | Configuración SOAP (WSDL, User, Pass) definida en el grabador. |
| `room_no` | Datos de Estancia | Número de habitación obtenido del PMS o formulario. |
| `room_no_2` | Datos de Estancia | Segunda habitación (si aplica para la misma tarjeta). |
| `room_no_3` | Datos de Estancia | Tercera habitación (si aplica para la misma tarjeta). |
| `safe_box` | Datos de Estancia | Identificador de **Caja Fuerte** o **Puerta de Seguridad**. |
| `date_in` / `date_out` | Datos de Estancia | Fechas de estancia (Formato YYYY-MM-DD). |
| `time_in` / `time_out` | Datos de Estancia | Horas de check-in/out (Formato HH:MM:SS). |
| `common_doors` | Modelo `ZonaComun` (Backoffice) | Listado de zonas comunes separadas por comas. |
| `seq_mode` | Lógica de Grabación | **Modo de Secuencia:** Indica si es una tarjeta nueva (0) o una copia (1). |

### Consulta de Datos Completos vía API

Si el brazo necesita obtener más información del registro de grabación que la que viaja en el evento Pusher, puede consultar el recurso directamente:

-   **Endpoint:** `GET /api/v1/grabacion-tarjeta/{transaccion_id}`
-   **Autenticación:** Bearer Token.
-   **Respuesta:** Devuelve el objeto completo del modelo `GrabacionTarjetum`. A continuación se detalla la estructura exacta del JSON:

#### Ejemplo de Estructura de Respuesta API:
```json
{
  "data": {
    "id": 5678,
    "emisor_id": 45,
    "receptor_id": 94,
    "sesion_id": 123,
    "status": "pending",
    "date_in": "2026-01-28",
    "time_in": "14:00",
    "date_out": "2026-01-29",
    "time_out": "11:00",
    "room_no": "101",
    "room_no_2": "102",
    "room_no_3": null,
    "safe_box": "S-55",
    "common_doors": "ZONA_SPA,GYM",
    "card_qty": 1,
    "seq_mode": 0,
    "uid_card": null,
    "folio_id": 1001,
    "created_at": "2026-01-28 14:30:00",
    "updated_at": "2026-01-28 14:30:00",
    "deleted_at": null,
    "receptor": {
      "id": 94,
      "name": "Totem Reception",
      "email": "totem1@hotel.com",
      "totem_id": 42,
      "totem": {
        "id": 42,
        "establecimiento_id": 10,
        "totem_configuracion_grabadors": [
          {
            "id": 36,
            "totem_id": 42,
            "software_gestion": "tesa",
            "json_grabador": "{\"soap_wsdl\": \"...\", \"operator_name\": \"...\"}",
            "user_host": "10.253.99.234",
            "user_port": 8181
          }
        ]
      }
    }
  }
}
```

### Configuración de Zonas Comunes (`common_doors`)

Para que el campo `common_doors` llegue con datos al brazo de Python, se deben configurar previamente en el Backoffice:

1.  **Definición de Zonas:** En el menú de **Zonas Comunes**, se deben crear los registros vinculados al establecimiento.
    -   **Modelo:** `ZonaComun`.
    -   **Campos clave:** `nombre` (para visualización) y `codigo` (el valor técnico que se enviará a la cerradura).
    -   **Zonas Globales:** Si se marca una zona como `global`, aparecerá seleccionada por defecto en el formulario de grabación.
2.  **Selección en el Check-in:** Durante el proceso de grabación de tarjeta en el Call Manager, el usuario puede seleccionar o deseleccionar las zonas comunes disponibles para ese hotel.
3.  **Procesamiento:** Al guardar, el sistema concatena los `codigo` de las zonas seleccionadas y los guarda en el campo `common_doors` del modelo `GrabacionTarjetum`.

### Configuración del Campo `provider`
Es importante que en la configuración del **Establecimiento** en el Backoffice, el campo "Proveedor Cerradura" coincida con lo esperado por el sistema (en este caso, suele ser el ID interno del proveedor).

---

## 5. Diagnóstico y Logs

Para un seguimiento detallado de los fallos, consulta la [Guía de Notificación de Errores a la API](guia_notificacion_errores_api.md).

-   **Logs de SH-360:** Revisar `storage/logs/laravel.log`.
-   **Logs del Brazo:** El programa Python debe generar sus propios logs de conexión SOAP.
-   **Errores Comunes:**
    -   `Connection Timeout (8181)`: El Brazo no llega a la IP del servidor Tesa.
    -   `Unauthorized`: Las credenciales del `operator_name` en el JSON son incorrectas.
    -   `Pusher Connection Error`: El Brazo no tiene salida a internet o las llaves de Pusher en el `.env` son erróneas.
