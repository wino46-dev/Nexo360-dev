<div class="row pt-3">
    <div class="col-3  pb-3">
        <span id="reloj_header"></span><br />
        <span id="fecha_header"></span>
    </div>
    <div class="col-6 text-center  pb-3" style="font-size:120%">
        {{ $establecimiento->nombre }}
    </div>
    <div class="col-3 text-right">
        <table style="width:100%">
            <tr>
                <td>MIN. 17ºC<br />MÁX. 22ºC</td>
                <td><img src="/img/tmp_sol.jpg" style="width:60px" onclick="temporizadorNotificar()" /></td>
            </tr>
        </table>
    </div>
</div>