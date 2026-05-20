<!DOCTYPE html>
<html>
    <head>
        <title>PDF de Ejemplo</title>
    </head>
    <body>
  
        <style>
            html{
                font-family: 'Verdana', sans-serif;
            }
            .centro {
                text-align: center;
            }
            .parte td {
                height:30px
            }
        </style>
        <div style="text-align: center;">
            <h3>Test</h3>
            {{ url('/img/no_docu_1.png') }}

            <img src="{{ url('/img/no_docu_1.png') }}" />

            <img src="{{ $firma }}" />
        </div>
            
        
    </body>
</html>