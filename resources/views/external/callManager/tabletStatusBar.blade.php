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
    // Activar modo tablet según ancho de ventana
    (function() {
        function updateTabletMode() {
            var w = window.innerWidth || document.documentElement.clientWidth;
            var isTablet = w >= 768 && w <= 1024;
            document.body.classList.toggle('tablet-mode', !!isTablet);
        }
        window.addEventListener('resize', updateTabletMode);
        document.addEventListener('DOMContentLoaded', updateTabletMode);
        updateTabletMode();
    })();

    // Utilidades para actualizar estados desde cualquier listener/evento
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

    // Hooks genéricos: si el sistema emite eventos personalizados podemos enlazarlos aquí
    // window.addEventListener('ocr:captured', () => setOcrStatus(true));
    // window.addEventListener('payment:completed', () => setPaymentStatus(true));
    // window.addEventListener('card:written', () => setCardStatus(true));
</script>
