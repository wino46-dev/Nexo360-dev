<p>Hola,</p>

<p>Adjuntamos el archivo con la exportación solicitada.</p>

<ul>
    <li><strong>Conjunto:</strong> {{ $datasetLabel }}</li>
    <li><strong>Rango de fechas:</strong> {{ $dateStart }} — {{ $dateEnd }}</li>
    @if($requester)
        <li><strong>Solicitado por:</strong> {{ $requester->name }} ({{ $requester->email }})</li>
    @endif
    <li><strong>Archivo:</strong> {{ $filename }}</li>
</ul>

<p>Este archivo contiene los registros exportados en el periodo indicado. Si no esperabas este correo o detectas algún problema con el contenido, por favor responde a este mensaje o contacta con el soporte técnico.</p>

<p>Gracias por utilizar SH-360.</p>

<p>Un saludo,<br>
El equipo de SH-360</p>
