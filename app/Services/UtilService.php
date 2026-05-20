<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str as Str;

use Carbon\Carbon;

class UtilService
{

    public static function drowDownList($name, $list = [], $value, $atributes = ['class' => 'form-control'])
    {

        $dropdown = '<select';
        $dropdown .= ' name="' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '"';
        if (!empty($atributes['id'])) {
            //if ($id) {
            $dropdown .= ' id="' . htmlspecialchars($atributes['id'], ENT_QUOTES, 'UTF-8') . '"';
        }

        if (!empty($atributes['class'])) {
            $dropdown .= ' class="' . htmlspecialchars($atributes['class'], ENT_QUOTES, 'UTF-8') . '"';
        }
        if (!empty($atributes['required'])) {
            $dropdown .= ' required ';
        }
        $dropdown .= '>';

        if (!empty($atributes['placeholder'])) {
            $dropdown .= "<option value=\"\" >" . $atributes['placeholder'] . "</option>";
        }

        foreach ($list as $id => $opcion) {
            $selected = ($id == $value) ? 'selected' : '';
            $dropdown .= "<option value=\"$id\" $selected>$opcion</option>";
        }

        $dropdown .= '</select>';
        $dropdown .= '<label class="error" id="' . htmlspecialchars($atributes['id'], ENT_QUOTES, 'UTF-8') . '-error" style="display:none" for="' . htmlspecialchars($atributes['id'], ENT_QUOTES, 'UTF-8') . '">Este campo es obligatorio.</label>';

        return $dropdown;
    }

    public static function determinarFormatoFecha($fecha)
    {
        try {

            $parsedDate = Carbon::createFromFormat('d/m/Y', $fecha);
            if ($parsedDate->format('d/m/Y') === $fecha) {
                return 'dd/mm/YYYY';
            }
        } catch (\Exception $e) {
        }

        try {

            $parsedDate = Carbon::createFromFormat('Y-m-d', $fecha);
            if ($parsedDate->format('Y-m-d') === $fecha) {
                return 'YYYY-mm-dd';
            }
        } catch (\Exception $e) {
        }

        // Si no se puede parsear en ninguno de los formatos, devolver null o un valor indicativo de que no es reconocido
        return null;
    }

    public static function documents_list()
    {
        return [
            6 => 'DNI',
            3 => 'Documento Identificación',
            1 => 'Pasaporte',
            2 => 'Carnét Condudir',
            8 => 'Permiso Residencia Español',
            5 => 'Permiso Residencia Europeo',
        ];
    }

    public static function countries_all()
    {
        return [
            ["id" => "3", "country" => "Afganistán", "country_code" => "AF", "code" => "AFG"],
            ["id" => "6", "country" => "Albania", "country_code" => "AL", "code" => "ALB"],
            ["id" => "57", "country" => "Alemania", "country_code" => "DE", "code" => "DEU"],
            ["id" => "1", "country" => "Andorra", "country_code" => "AD", "code" => "AND"],
            ["id" => "8", "country" => "Angola", "country_code" => "AO", "code" => "AGO"],
            ["id" => "5", "country" => "Anguilla", "country_code" => "AI", "code" => "AIA"],
            ["id" => "4", "country" => "Antigua y Barbuda", "country_code" => "AG", "code" => "ATG"],
            ["id" => "9", "country" => "Antártida", "country_code" => "AQ", "code" => "ATA"],
            ["id" => "192", "country" => "Arabia Saudita", "country_code" => "SA", "code" => "SAU"],
            ["id" => "62", "country" => "Argelia", "country_code" => "DZ", "code" => "DZA"],
            ["id" => "10", "country" => "Argentina", "country_code" => "AR", "code" => "ARG"],
            ["id" => "7", "country" => "Armenia", "country_code" => "AM", "code" => "ARM"],
            ["id" => "14", "country" => "Aruba", "country_code" => "AW", "code" => "ABW"],
            ["id" => "13", "country" => "Australia", "country_code" => "AU", "code" => "AUS"],
            ["id" => "12", "country" => "Austria", "country_code" => "AT", "code" => "AUT"],
            ["id" => "16", "country" => "Azerbaiyán", "country_code" => "AZ", "code" => "AZE"],
            ["id" => "32", "country" => "Bahamas", "country_code" => "BS", "code" => "BHS"],
            ["id" => "19", "country" => "Bangladés", "country_code" => "BD", "code" => "BGD"],
            ["id" => "18", "country" => "Barbados", "country_code" => "BB", "code" => "BRB"],
            ["id" => "23", "country" => "Baréin", "country_code" => "BH", "code" => "BHR"],
            ["id" => "37", "country" => "Belice", "country_code" => "BZ", "code" => "BLZ"],
            ["id" => "25", "country" => "Benín", "country_code" => "BJ", "code" => "BEN"],
            ["id" => "27", "country" => "Bermuda", "country_code" => "BM", "code" => "BMU"],
            ["id" => "36", "country" => "Bielorrusia", "country_code" => "BY", "code" => "BLR"],
            ["id" => "29", "country" => "Bolivia", "country_code" => "BO", "code" => "BOL"],
            ["id" => "17", "country" => "Bosnia y Herzegovina", "country_code" => "BA", "code" => "BIH"],
            ["id" => "35", "country" => "Botsuana", "country_code" => "BW", "code" => "BWA"],
            ["id" => "31", "country" => "Brasil", "country_code" => "BR", "code" => "BRA"],
            ["id" => "28", "country" => "Brunéi Darusalam", "country_code" => "BN", "code" => "BRN"],
            ["id" => "22", "country" => "Bulgaria", "country_code" => "BG", "code" => "BGR"],
            ["id" => "21", "country" => "Burkina Faso", "country_code" => "BF", "code" => "BFA"],
            ["id" => "24", "country" => "Burundi", "country_code" => "BI", "code" => "BDI"],
            ["id" => "33", "country" => "Bután", "country_code" => "BT", "code" => "BTN"],
            ["id" => "20", "country" => "Bélgica", "country_code" => "BE", "code" => "BEL"],
            ["id" => "52", "country" => "Cabo Verde", "country_code" => "CV", "code" => "CPV"],
            ["id" => "116", "country" => "Camboya", "country_code" => "KH", "code" => "KHM"],
            ["id" => "47", "country" => "Camerún", "country_code" => "CM", "code" => "CMR"],
            ["id" => "38", "country" => "Canadá", "country_code" => "CA", "code" => "CAN"],
            ["id" => "186", "country" => "Catar", "country_code" => "QA", "code" => "QAT"],
            ["id" => "214", "country" => "Chad", "country_code" => "TD", "code" => "TCD"],
            ["id" => "56", "country" => "Chequia", "country_code" => "CZ", "code" => "CZE"],
            ["id" => "46", "country" => "Chile", "country_code" => "CL", "code" => "CHL"],
            ["id" => "48", "country" => "China", "country_code" => "CN", "code" => "CHN"],
            ["id" => "55", "country" => "Chipre", "country_code" => "CY", "code" => "CYP"],
            ["id" => "236", "country" => "Ciudad del Vaticano", "country_code" => "VA", "code" => "VAT"],
            ["id" => "49", "country" => "Colombia", "country_code" => "CO", "code" => "COL"],
            ["id" => "118", "country" => "Comoras", "country_code" => "KM", "code" => "COM"],
            ["id" => "42", "country" => "Congo", "country_code" => "CG", "code" => "COG"],
            ["id" => "120", "country" => "Corea del Norte", "country_code" => "KP", "code" => "PRK"],
            ["id" => "121", "country" => "Corea del Sur", "country_code" => "KR", "code" => "KOR"],
            ["id" => "50", "country" => "Costa Rica", "country_code" => "CR", "code" => "CRI"],
            ["id" => "44", "country" => "Costa de Marfil", "country_code" => "CI", "code" => "CIV"],
            ["id" => "97", "country" => "Croacia", "country_code" => "HR", "code" => "HRV"],
            ["id" => "51", "country" => "Cuba", "country_code" => "CU", "code" => "CUB"],
            ["id" => "53", "country" => "Curaçao", "country_code" => "CW", "code" => "CUW"],
            ["id" => "59", "country" => "Dinamarca", "country_code" => "DK", "code" => "DNK"],
            ["id" => "60", "country" => "Dominica", "country_code" => "DM", "code" => "DMA"],
            ["id" => "63", "country" => "Ecuador", "country_code" => "EC", "code" => "ECU"],
            ["id" => "65", "country" => "Egipto", "country_code" => "EG", "code" => "EGY"],
            ["id" => "209", "country" => "El Salvador", "country_code" => "SV", "code" => "SLV"],
            ["id" => "2", "country" => "Emiratos Árabes Unidos", "country_code" => "AE", "code" => "ARE"],
            ["id" => "67", "country" => "Eritrea", "country_code" => "ER", "code" => "ERI"],
            ["id" => "201", "country" => "Eslovaquia", "country_code" => "SK", "code" => "SVK"],
            ["id" => "199", "country" => "Eslovenia", "country_code" => "SI", "code" => "SVN"],
            ["id" => "68", "country" => "España", "country_code" => "ES", "code" => "ESP"],
            ["id" => "233", "country" => "Estados Unidos de América", "country_code" => "US", "code" => "USA"],
            ["id" => "64", "country" => "Estonia", "country_code" => "EE", "code" => "EST"],
            ["id" => "212", "country" => "Esuatini", "country_code" => "SZ", "code" => "SWZ"],
            ["id" => "69", "country" => "Etiopía", "country_code" => "ET", "code" => "ETH"],
            ["id" => "190", "country" => "Federación de Rusia", "country_code" => "RU", "code" => "RUS"],
            ["id" => "176", "country" => "Filipinas", "country_code" => "PH", "code" => "PHL"],
            ["id" => "70", "country" => "Finlandia", "country_code" => "FI", "code" => "FIN"],
            ["id" => "71", "country" => "Fiyi", "country_code" => "FJ", "code" => "FJI"],
            ["id" => "75", "country" => "Francia", "country_code" => "FR", "code" => "FRA"],
            ["id" => "76", "country" => "Gabón", "country_code" => "GA", "code" => "GAB"],
            ["id" => "84", "country" => "Gambia", "country_code" => "GM", "code" => "GMB"],
            ["id" => "78", "country" => "Georgia", "country_code" => "GE", "code" => "GEO"],
            ["id" => "80", "country" => "Ghana", "country_code" => "GH", "code" => "GHA"],
            ["id" => "81", "country" => "Gibraltar", "country_code" => "GI", "code" => "GIB"],
            ["id" => "77", "country" => "Granada", "country_code" => "GD", "code" => "GRD"],
            ["id" => "88", "country" => "Grecia", "country_code" => "GR", "code" => "GRC"],
            ["id" => "83", "country" => "Groenlandia", "country_code" => "GL", "code" => "GRL"],
            ["id" => "86", "country" => "Guadalupe", "country_code" => "GP", "code" => "GLP"],
            ["id" => "91", "country" => "Guam", "country_code" => "GU", "code" => "GUM"],
            ["id" => "90", "country" => "Guatemala", "country_code" => "GT", "code" => "GTM"],
            ["id" => "79", "country" => "Guayana Francesa", "country_code" => "GF", "code" => "GUF"],
            ["id" => "82", "country" => "Guernsey", "country_code" => "GG", "code" => "GGY"],
            ["id" => "85", "country" => "Guinea", "country_code" => "GN", "code" => "GIN"],
            ["id" => "87", "country" => "Guinea Ecuatorial", "country_code" => "GQ", "code" => "GNQ"],
            ["id" => "92", "country" => "Guinea-Bisáu", "country_code" => "GW", "code" => "GNB"],
            ["id" => "93", "country" => "Guyana", "country_code" => "GY", "code" => "GUY"],
            ["id" => "98", "country" => "Haití", "country_code" => "HT", "code" => "HTI"],
            ["id" => "96", "country" => "Honduras", "country_code" => "HN", "code" => "HND"],
            ["id" => "94", "country" => "Hong Kong", "country_code" => "HK", "code" => "HKG"],
            ["id" => "99", "country" => "Hungría", "country_code" => "HU", "code" => "HUN"],
            ["id" => "104", "country" => "India", "country_code" => "IN", "code" => "IND"],
            ["id" => "100", "country" => "Indonesia", "country_code" => "ID", "code" => "IDN"],
            ["id" => "106", "country" => "Irak", "country_code" => "IQ", "code" => "IRQ"],
            ["id" => "101", "country" => "Irlanda", "country_code" => "IE", "code" => "IRL"],
            ["id" => "107", "country" => "Irán", "country_code" => "IR", "code" => "IRN"],
            ["id" => "34", "country" => "Isla Bouvet", "country_code" => "BV", "code" => "BVT"],
            ["id" => "162", "country" => "Isla Norfolk", "country_code" => "NF", "code" => "NFK"],
            ["id" => "103", "country" => "Isla de Man", "country_code" => "IM", "code" => "IMN"],
            ["id" => "54", "country" => "Isla de Navidad", "country_code" => "CX", "code" => "CXR"],
            ["id" => "108", "country" => "Islandia", "country_code" => "IS", "code" => "ISL"],
            ["id" => "123", "country" => "Islas Caimán", "country_code" => "KY", "code" => "CYM"],
            ["id" => "39", "country" => "Islas Cocos (Keeling)", "country_code" => "CC", "code" => "CCK"],
            ["id" => "45", "country" => "Islas Cook", "country_code" => "CK", "code" => "COK"],
            ["id" => "74", "country" => "Islas Feroe", "country_code" => "FO", "code" => "FRO"],
            ["id" => "89", "country" => "Islas Georgias del Sur y Sandwich del Sur", "country_code" => "GS", "code" => "SGS"],
            ["id" => "95", "country" => "Islas Heard y McDonald", "country_code" => "HM", "code" => "HMD"],
            ["id" => "72", "country" => "Islas Malvinas", "country_code" => "FK", "code" => "FLK"],
            ["id" => "148", "country" => "Islas Marianas del Norte", "country_code" => "MP", "code" => "MNP"],
            ["id" => "142", "country" => "Islas Marshall", "country_code" => "MH", "code" => "MHL"],
            ["id" => "180", "country" => "Islas Pitcairn", "country_code" => "PN", "code" => "PCN"],
            ["id" => "193", "country" => "Islas Salomón", "country_code" => "SB", "code" => "SLB"],
            ["id" => "213", "country" => "Islas Turcas y Caicos", "country_code" => "TC", "code" => "TCA"],
            ["id" => "239", "country" => "Islas Vírgenes (Británicas)", "country_code" => "VG", "code" => "VGB"],
            ["id" => "240", "country" => "Islas Vírgenes (EE.UU.)", "country_code" => "VI", "code" => "VIR"],
            ["id" => "15", "country" => "Islas Åland", "country_code" => "AX", "code" => "ALA"],
            ["id" => "102", "country" => "Israel", "country_code" => "IL", "code" => "ISR"],
            ["id" => "109", "country" => "Italia", "country_code" => "IT", "code" => "ITA"],
            ["id" => "111", "country" => "Jamaica", "country_code" => "JM", "code" => "JAM"],
            ["id" => "113", "country" => "Japón", "country_code" => "JP", "code" => "JPN"],
            ["id" => "110", "country" => "Jersey", "country_code" => "JE", "code" => "JEY"],
            ["id" => "112", "country" => "Jordania", "country_code" => "JO", "code" => "JOR"],
            ["id" => "124", "country" => "Kazajistán", "country_code" => "KZ", "code" => "KAZ"],
            ["id" => "114", "country" => "Kenia", "country_code" => "KE", "code" => "KEN"],
            ["id" => "115", "country" => "Kirguistán", "country_code" => "KG", "code" => "KGZ"],
            ["id" => "117", "country" => "Kiribati", "country_code" => "KI", "code" => "KIR"],
            ["id" => "250", "country" => "Kosovo", "country_code" => "XK", "code" => "XKX"],
            ["id" => "122", "country" => "Kuwait", "country_code" => "KW", "code" => "KWT"],
            ["id" => "131", "country" => "Lesoto", "country_code" => "LS", "code" => "LSO"],
            ["id" => "134", "country" => "Letonia", "country_code" => "LV", "code" => "LVA"],
            ["id" => "130", "country" => "Liberia", "country_code" => "LR", "code" => "LBR"],
            ["id" => "135", "country" => "Libia", "country_code" => "LY", "code" => "LBY"],
            ["id" => "128", "country" => "Liechtenstein", "country_code" => "LI", "code" => "LIE"],
            ["id" => "132", "country" => "Lituania", "country_code" => "LT", "code" => "LTU"],
            ["id" => "133", "country" => "Luxemburgo", "country_code" => "LU", "code" => "LUX"],
            ["id" => "126", "country" => "Líbano", "country_code" => "LB", "code" => "LBN"],
            ["id" => "147", "country" => "Macao", "country_code" => "MO", "code" => "MAC"],
            ["id" => "143", "country" => "Macedonia del Norte", "country_code" => "MK", "code" => "MKD"],
            ["id" => "141", "country" => "Madagascar", "country_code" => "MG", "code" => "MDG"],
            ["id" => "157", "country" => "Malasia", "country_code" => "MY", "code" => "MYS"],
            ["id" => "155", "country" => "Malaui", "country_code" => "MW", "code" => "MWI"],
            ["id" => "154", "country" => "Maldivas", "country_code" => "MV", "code" => "MDV"],
            ["id" => "152", "country" => "Malta", "country_code" => "MT", "code" => "MLT"],
            ["id" => "144", "country" => "Malí", "country_code" => "ML", "code" => "MLI"],
            ["id" => "136", "country" => "Marruecos", "country_code" => "MA", "code" => "MAR"],
            ["id" => "149", "country" => "Martinica", "country_code" => "MQ", "code" => "MTQ"],
            ["id" => "153", "country" => "Mauricio", "country_code" => "MU", "code" => "MUS"],
            ["id" => "150", "country" => "Mauritania", "country_code" => "MR", "code" => "MRT"],
            ["id" => "246", "country" => "Mayotte", "country_code" => "YT", "code" => "MYT"],
            ["id" => "73", "country" => "Micronesia", "country_code" => "FM", "code" => "FSM"],
            ["id" => "138", "country" => "Moldavia", "country_code" => "MD", "code" => "MDA"],
            ["id" => "146", "country" => "Mongolia", "country_code" => "MN", "code" => "MNG"],
            ["id" => "139", "country" => "Montenegro", "country_code" => "ME", "code" => "MNE"],
            ["id" => "151", "country" => "Montserrat", "country_code" => "MS", "code" => "MSR"],
            ["id" => "158", "country" => "Mozambique", "country_code" => "MZ", "code" => "MOZ"],
            ["id" => "145", "country" => "Myanmar", "country_code" => "MM", "code" => "MMR"],
            ["id" => "156", "country" => "México", "country_code" => "MX", "code" => "MEX"],
            ["id" => "137", "country" => "Mónaco", "country_code" => "MC", "code" => "MCO"],
            ["id" => "159", "country" => "Namibia", "country_code" => "NA", "code" => "NAM"],
            ["id" => "168", "country" => "Nauru", "country_code" => "NR", "code" => "NRU"],
            ["id" => "167", "country" => "Nepal", "country_code" => "NP", "code" => "NPL"],
            ["id" => "164", "country" => "Nicaragua", "country_code" => "NI", "code" => "NIC"],
            ["id" => "163", "country" => "Nigeria", "country_code" => "NG", "code" => "NGA"],
            ["id" => "169", "country" => "Niue", "country_code" => "NU", "code" => "NIU"],
            ["id" => "166", "country" => "Noruega", "country_code" => "NO", "code" => "NOR"],
            ["id" => "160", "country" => "Nueva Caledonia", "country_code" => "NC", "code" => "NCL"],
            ["id" => "170", "country" => "Nueva Zelanda", "country_code" => "NZ", "code" => "NZL"],
            ["id" => "161", "country" => "Níger", "country_code" => "NE", "code" => "NER"],
            ["id" => "171", "country" => "Omán", "country_code" => "OM", "code" => "OMN"],
            ["id" => "177", "country" => "Pakistán", "country_code" => "PK", "code" => "PAK"],
            ["id" => "184", "country" => "Palaos", "country_code" => "PW", "code" => "PLW"],
            ["id" => "182", "country" => "Palestina", "country_code" => "PS", "code" => "PSE"],
            ["id" => "172", "country" => "Panamá", "country_code" => "PA", "code" => "PAN"],
            ["id" => "175", "country" => "Papúa Nueva Guinea", "country_code" => "PG", "code" => "PNG"],
            ["id" => "185", "country" => "Paraguay", "country_code" => "PY", "code" => "PRY"],
            ["id" => "165", "country" => "Países Bajos", "country_code" => "NL", "code" => "NLD"],
            ["id" => "173", "country" => "Perú", "country_code" => "PE", "code" => "PER"],
            ["id" => "174", "country" => "Polinesia Francesa", "country_code" => "PF", "code" => "PYF"],
            ["id" => "178", "country" => "Polonia", "country_code" => "PL", "code" => "POL"],
            ["id" => "183", "country" => "Portugal", "country_code" => "PT", "code" => "PRT"],
            ["id" => "181", "country" => "Puerto Rico", "country_code" => "PR", "code" => "PRI"],
            ["id" => "231", "country" => "Reino Unido", "country_code" => "GB", "code" => "GBR"],
            ["id" => "40", "country" => "República Centroafricana", "country_code" => "CF", "code" => "CAF"],
            ["id" => "125", "country" => "República Democrática Popular Lao", "country_code" => "LA", "code" => "LAO"],
            ["id" => "41", "country" => "República Democrática del Congo", "country_code" => "CD", "code" => "COD"],
            ["id" => "61", "country" => "República Dominicana", "country_code" => "DO", "code" => "DOM"],
            ["id" => "211", "country" => "República Árabe Siria", "country_code" => "SY", "code" => "SYR"],
            ["id" => "187", "country" => "Reunión", "country_code" => "RE", "code" => "REU"],
            ["id" => "191", "country" => "Ruanda", "country_code" => "RW", "code" => "RWA"],
            ["id" => "188", "country" => "Rumania", "country_code" => "RO", "code" => "ROU"],
            ["id" => "244", "country" => "Samoa", "country_code" => "WS", "code" => "WSM"],
            ["id" => "11", "country" => "Samoa Americana", "country_code" => "AS", "code" => "ASM"],
            ["id" => "26", "country" => "San Bartolomé", "country_code" => "BL", "code" => "BLM"],
            ["id" => "119", "country" => "San Cristóbal y Nieves", "country_code" => "KN", "code" => "KNA"],
            ["id" => "203", "country" => "San Marino", "country_code" => "SM", "code" => "SMR"],
            ["id" => "179", "country" => "San Pierre y Miquelon", "country_code" => "PM", "code" => "SPM"],
            ["id" => "237", "country" => "San Vicente y las Granadinas", "country_code" => "VC", "code" => "VCT"],
            ["id" => "198", "country" => "Santa Elena, Ascensión y Tristán de Acuña", "country_code" => "SH", "code" => "SHN"],
            ["id" => "127", "country" => "Santa Lucía", "country_code" => "LC", "code" => "LCA"],
            ["id" => "208", "country" => "Santo Tomé y Príncipe", "country_code" => "ST", "code" => "STP"],
            ["id" => "204", "country" => "Senegal", "country_code" => "SN", "code" => "SEN"],
            ["id" => "189", "country" => "Serbia", "country_code" => "RS", "code" => "SRB"],
            ["id" => "194", "country" => "Seychelles", "country_code" => "SC", "code" => "SYC"],
            ["id" => "202", "country" => "Sierra Leona", "country_code" => "SL", "code" => "SLE"],
            ["id" => "197", "country" => "Singapur", "country_code" => "SG", "code" => "SGP"],
            ["id" => "205", "country" => "Somalia", "country_code" => "SO", "code" => "SOM"],
            ["id" => "129", "country" => "Sri Lanka", "country_code" => "LK", "code" => "LKA"],
            ["id" => "247", "country" => "Sudáfrica", "country_code" => "ZA", "code" => "ZAF"],
            ["id" => "195", "country" => "Sudán", "country_code" => "SD", "code" => "SDN"],
            ["id" => "207", "country" => "Sudán del Sur", "country_code" => "SS", "code" => "SSD"],
            ["id" => "196", "country" => "Suecia", "country_code" => "SE", "code" => "SWE"],
            ["id" => "43", "country" => "Suiza", "country_code" => "CH", "code" => "CHE"],
            ["id" => "206", "country" => "Surinam", "country_code" => "SR", "code" => "SUR"],
            ["id" => "200", "country" => "Svalbard y Jan Mayen", "country_code" => "SJ", "code" => "SJM"],
            ["id" => "66", "country" => "Sáhara occidental", "country_code" => "EH", "code" => "ESH"],
            ["id" => "217", "country" => "Tailandia", "country_code" => "TH", "code" => "THA"],
            ["id" => "227", "country" => "Taiwán", "country_code" => "TW", "code" => "TWN"],
            ["id" => "228", "country" => "Tanzania", "country_code" => "TZ", "code" => "TZA"],
            ["id" => "218", "country" => "Tayikistán", "country_code" => "TJ", "code" => "TJK"],
            ["id" => "223", "country" => "Timor Oriental", "country_code" => "TL", "code" => "TLS"],
            ["id" => "216", "country" => "Togo", "country_code" => "TG", "code" => "TGO"],
            ["id" => "219", "country" => "Tokelau", "country_code" => "TK", "code" => "TKL"],
            ["id" => "222", "country" => "Tonga", "country_code" => "TO", "code" => "TON"],
            ["id" => "225", "country" => "Trinidad y Tobago", "country_code" => "TT", "code" => "TTO"],
            ["id" => "220", "country" => "Turkmenistán", "country_code" => "TM", "code" => "TKM"],
            ["id" => "226", "country" => "Tuvalu", "country_code" => "TV", "code" => "TUV"],
            ["id" => "221", "country" => "Túnez", "country_code" => "TN", "code" => "TUN"],
            ["id" => "224", "country" => "Türkiye", "country_code" => "TR", "code" => "TUR"],
            ["id" => "229", "country" => "Ucrania", "country_code" => "UA", "code" => "UKR"],
            ["id" => "230", "country" => "Uganda", "country_code" => "UG", "code" => "UGA"],
            ["id" => "234", "country" => "Uruguay", "country_code" => "UY", "code" => "URY"],
            ["id" => "235", "country" => "Uzbekistán", "country_code" => "UZ", "code" => "UZB"],
            ["id" => "242", "country" => "Vanuatu", "country_code" => "VU", "code" => "VUT"],
            ["id" => "238", "country" => "Venezuela", "country_code" => "VE", "code" => "VEN"],
            ["id" => "241", "country" => "Vietnam", "country_code" => "VN", "code" => "VNM"],
            ["id" => "243", "country" => "Wallis y Futuna", "country_code" => "WF", "code" => "WLF"],
            ["id" => "245", "country" => "Yemen", "country_code" => "YE", "code" => "YEM"],
            ["id" => "58", "country" => "Yibuti", "country_code" => "DJ", "code" => "DJI"],
            ["id" => "248", "country" => "Zambia", "country_code" => "ZM", "code" => "ZMB"],
            ["id" => "249", "country" => "Zimbabue", "country_code" => "ZW", "code" => "ZWE"],
        ];
    }
}
