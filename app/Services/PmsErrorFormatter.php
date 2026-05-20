<?php

namespace App\Services;

class PmsErrorFormatter
{
    /**
     * Construye un mensaje para UI con límite de longitud, saneando HTML.
     *
     * @param string $prefix Texto inicial (ej.: "Error al validar documento…")
     * @param string|null $pmsMessage Mensaje devuelto por el PMS (puede venir con HTML)
     * @param int $limit Límite máximo de caracteres (default 600)
     * @return string Mensaje listo para mostrar en UI
     */
    public static function forUi(string $prefix = '', ?string $pmsMessage = null, int $limit = 600): string
    {
        $prefix = trim((string) $prefix);
        $detail = trim(strip_tags((string) ($pmsMessage ?? '')));

        // Normalizar espacios
        $detail = preg_replace('/\s+/u', ' ', $detail ?? '');

        $message = $prefix;
        if ($detail !== '') {
            $message = rtrim($prefix, ' :') . ': ' . $detail;
        }

        if (mb_strlen($message) > $limit) {
            $message = mb_substr($message, 0, $limit) . '…';
        }

        return $message !== '' ? $message : __('Error inesperado con el PMS');
    }
}
