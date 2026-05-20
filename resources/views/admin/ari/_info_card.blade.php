<div id="ari_recommendations_container" class="mb-3">
    <div class="card border-0 shadow-sm">
        <div class="card-body p-3">
            <div class="ari-rec-header d-flex align-items-start justify-content-between flex-wrap">
                <div>
                    <div class="h5 mb-0 ari-rec-title">
                        <i class="fa fa-lightbulb-o text-warning" aria-hidden="true"></i>
                        Datos Clave del Hotel
                    </div>
                    <div id="ari_recommendations_subtitle" class="text-muted ari-rec-subtitle d-none"></div>
                </div>
                <div class="mt-2 mt-md-0">
                    <button type="button" id="ari_recommendations_toggle_btn" class="btn btn-sm btn-success" onclick="if(window.callManagerToggleRecommendations){window.callManagerToggleRecommendations();}">
                        <i class="fa fa-eye" aria-hidden="true"></i> Mostrar Información del Hotel
                    </button>
                </div>
            </div>

            <div id="ari_recommendations_content" class="mt-3" style="display:none">
                <div class="text-muted">Cargando recomendaciones...</div>
            </div>
        </div>
    </div>
</div>
