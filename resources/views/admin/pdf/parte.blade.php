<?php
$show_accept_personal_data = true;
?>
<!DOCTYPE html>
<html>
    <head>
        <title>{{ __('parte.title') }}</title>
    </head>
    <body>
        <style>
            .container{
                font-family: 'Verdana', sans-serif;
            }
            .centro {
                text-align: center;
            }
            .parte td {
                height:30px
            }

        </style>
        <div class="container">

            {{-- Try Sociedad-specific header first: resources/views/admin/pdf/sociedades/{sociedad_id}/parte_header.blade.php --}}
            @includeFirst([
                'admin.pdf.sociedades.' . ($sociedad->id ?? 'default') . '.parte_header',
                'admin.pdf.parte_header'
            ], compact('checkin', 'establecimiento','reservation','show_accept_personal_data'))

            <br />
            <div  style="text-align: center;" >
                <div style="height:90px">
                    <img src="{{ $firma_img }}" style="height:90px" />
                </div>
                <div>
                    <?php
                        $timestamp = strtotime($checkin['created_at']);
                        echo $fechaFormateada = date("d", $timestamp) .' de ' .date("F", $timestamp). ' de ' . date("Y", $timestamp);
                    ?>
                </div>
            </div>
            <div  style="text-align: center;margin-top:5px" >{{ __('parte.signature') }}</div>
            <br /><br />
            <?php
                $locale = app()->getLocale();
                $legalHtml = $sociedad->parte_viajero_html;
                if ($locale === 'en' && !empty($sociedad->parte_viajero_html_en)) {
                    $legalHtml = $sociedad->parte_viajero_html_en;
                } elseif ($locale === 'es' && !empty($sociedad->parte_viajero_html_es)) {
                    $legalHtml = $sociedad->parte_viajero_html_es;
                } elseif (!empty($sociedad->parte_viajero_html_es)) {
                    $legalHtml = $sociedad->parte_viajero_html_es;
                }
            ?>
            <div style="border:1px solid #CCC; padding:5px">
                {!! $legalHtml !!}
            </div>
        </div>
    </body>
</html>
