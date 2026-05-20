<div id="tabletStatusBar" class="tablet-status-bar">
    <div class="container-fluid d-flex justify-content-center">
        <div class="status-item">
            <button id="statusOcr" type="button" class="btn btn-outline-secondary btn-sm status-btn">
                <i class="fa fa-id-card-o"></i> OCR Capturado
            </button>
            <span class="arrow"><i class="fa fa-long-arrow-right"></i></span>
        </div>
        <div class="status-item">
            <button id="statusPago" type="button" class="btn btn-outline-secondary btn-sm status-btn">
                <i class="fa fa-credit-card"></i> Pago Reserva
            </button>
            <span class="arrow"><i class="fa fa-long-arrow-right"></i></span>
        </div>
        <div class="status-item">
            <button id="statusTarjeta" type="button" class="btn btn-outline-secondary btn-sm status-btn">
                <i class="fa fa-key"></i> Tarjeta Grabada
            </button>
        </div>
    </div>
</div>

<script>
    // Helpers globales para actualizar estados desde eventos reales
    window.setOcrStatus = function(done) {
        var el = document.getElementById('statusOcr');
        if (!el) return;
        el.classList.toggle('btn-success', !!done);
        el.classList.toggle('btn-outline-secondary', !done);
    };
    window.setPaymentStatus = function(done) {
        var el = document.getElementById('statusPago');
        if (!el) return;
        el.classList.toggle('btn-success', !!done);
        el.classList.toggle('btn-outline-secondary', !done);
    };
    window.setCardStatus = function(done) {
        var el = document.getElementById('statusTarjeta');
        if (!el) return;
        el.classList.toggle('btn-success', !!done);
        el.classList.toggle('btn-outline-secondary', !done);
    };
</script>
