<style>
    /* Responsive específico para modo tablet (ancho entre 768px y 1024px) */
    @media (min-width: 768px) and (max-width: 1024px) {
        body.tablet-mode .tablet-two-col {
            display: flex;
            gap: 12px;
        }

        body.tablet-mode .tablet-two-col > [class^="col-"],
        body.tablet-mode .tablet-two-col > [class*=" col-"] {
            /* Forzar 50/50 en tablet sin romper desktop */
            flex: 0 0 50% !important;
            max-width: 50% !important;
        }

        /* Tarjeta flotante para documentos/imágenes si se usan en tablet */
        body.tablet-mode #photoView.card {
            position: fixed !important;
            z-index: 1050;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 90vw;
            max-width: 800px;
            display: none; /* Se controla por JS */
        }

        /* Barra inferior de estados (breadcrumbs) en tablet */
        .tablet-status-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #ffffff;
            border-top: 1px solid rgba(0,0,0,0.1);
            box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
            padding: 8px 10px;
            z-index: 1040;
            display: none; /* visible sólo en tablet-mode */
        }

        body.tablet-mode .tablet-status-bar { display: block; }

        .tablet-status-bar .status-item {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-right: 8px;
        }

        .tablet-status-bar .status-btn {
            pointer-events: none;
            min-width: 140px;
        }

        .tablet-status-bar .arrow {
            color: #6c757d;
        }
    }

    /* Asegurar que los modales emergen centrados (refuerzo) */
    .modal.show .modal-dialog {
        margin-top: 10vh;
    }
</style>
