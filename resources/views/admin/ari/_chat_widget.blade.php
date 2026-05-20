<div id="ari_chat_widget" aria-live="polite">
    <div class="ari-chat-fab position-relative" onclick="if(window.ariChat && window.ariChat.open){window.ariChat.open(true, true);}">
        <i class="fa fa-comments" aria-hidden="true" style="font-size:20px"></i>
        <span class="ari-chat-badge" id="ari_chat_badge">0</span>
    </div>

    <div class="ari-chat-panel">
        <div class="ari-chat-header">
            <div>
                <p class="ari-chat-title mb-0"><i class="fa fa-magic mr-1" aria-hidden="true"></i> Asistente IA</p>
                <p class="ari-chat-subtitle mb-0" id="ari_chat_context">Recomendaciones para la reserva seleccionada</p>
            </div>
            <div class="text-right">
                <button type="button" class="btn btn-sm btn-light" onclick="if(window.ariChat && window.ariChat.close){window.ariChat.close();}">
                    <i class="fa fa-times" aria-hidden="true"></i>
                </button>
            </div>
        </div>
        <div class="ari-chat-body" id="ari_chat_messages"></div>
        <div class="ari-chat-footer">
            <div class="d-flex align-items-center justify-content-between">
                <div class="ari-chat-status" id="ari_chat_status">
                    <span class="text-muted">Listo</span>
                </div>
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="if(window.ariChat && window.ariChat.clear){window.ariChat.clear();}">
                    <i class="fa fa-trash" aria-hidden="true"></i> Limpiar
                </button>
            </div>
        </div>
    </div>
</div>
