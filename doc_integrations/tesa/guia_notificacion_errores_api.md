# Guía de Notificación de Errores a la API (SH-360)

Este documento detalla cómo los programas externos (como el "brazo" de Tesa) deben reportar errores técnicos o de ejecución a la API central de SH-360. El uso de este endpoint permite un diagnóstico centralizado desde el Backoffice.

## 1. Endpoint de Control de Errores

- **URL:** `POST /api/v1/control-errors`
- **Autenticación:** Requiere cabecera `Authorization: Bearer <access_token>` (el mismo token obtenido en el login del Tótem).
- **Content-Type:** `application/json`

---

## 2. Estructura del Payload (JSON)

La API espera los siguientes campos para registrar un error:

| Campo | Tipo | Requerido | Descripción |
| :--- | :--- | :--- | :--- |
| `origen` | String | **Sí** | Identificador del sistema que reporta (ej: `BrazoTesaPython`, `SH360TotemApp`). |
| `tipo` | String | **Sí** | Categoría del error (ej: `SOAP_CONNECTION`, `AUTH_ERROR`, `WRITE_TIMEOUT`). |
| `mensaje` | String | **Sí** | Resumen corto del error. **Importante:** Incluir aquí el `transaccion_id` si el error ocurre durante una grabación. |
| `descripcion` | String | No | Detalle completo del error. Se recomienda pegar aquí el stacktrace, la respuesta XML del servidor SOAP o el log de la excepción. |

### Ejemplo de Payload (Error de conexión SOAP):
```json
{
  "origen": "BrazoTesaPython",
  "tipo": "SOAP_CONNECTION",
  "mensaje": "No se pudo conectar con el servidor Tesa en 10.253.99.234 (Transacción: 5678)",
  "descripcion": "urllib3.exceptions.ConnectTimeoutError: (connect timeout=30) - Full stacktrace: ..."
}
```

### Ejemplo de Payload (Error de respuesta de la cerradura):
```json
{
  "origen": "BrazoTesaPython",
  "tipo": "WRITE_FAILURE",
  "mensaje": "La cerradura devolvió un código de error 0x01 (Transacción: 5678)",
  "descripcion": "Respuesta SOAP recibida: <xml>...</xml>"
}
```

---

## 3. Respuestas de la API

- **201 Created:** El error ha sido registrado con éxito.
- **422 Unprocessable Entity:** Faltan campos requeridos o el formato no es válido.
- **401 Unauthorized:** El token de autenticación es inválido o ha expirado.

---

## 4. Mejores Prácticas

1.  **Reportar antes de fallar:** Si el error ocurre durante una grabación de tarjeta, se recomienda enviar el log a este endpoint **antes** de realizar el `PUT` a `/api/v1/grabacion-tarjeta/{id}` con `status: Fail`. Esto asegura que el administrador tenga el detalle técnico disponible de inmediato.
2.  **Incluir IDs:** Siempre incluye el `transaccion_id` en el campo `mensaje` para que el backend pueda filtrar y mostrar el error específico al recepcionista en el Call Manager.
3.  **No abusar:** Reportar solo errores críticos, excepciones no controladas o fallos de hardware. No es necesario reportar "advertencias" o logs informativos normales.

---

## 5. Visualización en el Backoffice

Los errores reportados por este endpoint aparecen en el menú **"Control de Errores"** de SH-360, donde se pueden filtrar por origen y fecha para facilitar el soporte técnico.
