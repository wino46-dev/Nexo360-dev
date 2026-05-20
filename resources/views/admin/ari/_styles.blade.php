<style>
    /* Recomendaciones (ARI) – estilo moderno sin dependencias nuevas */
    #ari_recommendations_container .ari-rec-header {
        background: transparent;
        border: 1px solid rgba(0,0,0,.06);
        border-radius: .75rem;
        padding: 12px 14px;
    }
    #ari_recommendations_container .ari-rec-title {
        font-weight: 700;
        letter-spacing: .2px;
    }
    #ari_recommendations_container .ari-rec-subtitle {
        font-size: .9rem;
        line-height: 1.25;
    }
    #ari_recommendations_container .ari-rec-card {
        border: 1px solid rgba(0,0,0,.06);
        border-radius: .75rem;
        background: #fff;
    }
    #ari_recommendations_container .ari-rec-card .ari-rec-card-title {
        font-weight: 700;
    }
    #ari_recommendations_container .ari-kv td {
        padding: .25rem .25rem;
        vertical-align: top;
    }
    #ari_recommendations_container .ari-kv td:first-child {
        width: 170px;
        color: #6c757d;
        font-weight: 600;
    }
    #ari_recommendations_container .badge {
        font-weight: 600;
    }

    /* ARI Chat (reserva) – widget flotante */
    #ari_chat_widget {
        position: fixed;
        right: 18px;
        bottom: 18px;
        z-index: 1040; /* por debajo de modales bootstrap (1050), por encima del contenido */
        font-family: inherit;
    }

    /* Atención visual: borde rojo intermitente hasta que el usuario interactúe */
    @keyframes ariChatPulseBorder {
        0%   { box-shadow: 0 16px 50px rgba(0,0,0,.22), 0 0 0 0 rgba(220,53,69,.0); }
        50%  { box-shadow: 0 16px 50px rgba(0,0,0,.22), 0 0 0 4px rgba(220,53,69,.35); }
        100% { box-shadow: 0 16px 50px rgba(0,0,0,.22), 0 0 0 0 rgba(220,53,69,.0); }
    }
    #ari_chat_widget.ari-attention .ari-chat-panel {
        border-color: rgba(220,53,69,.85);
        animation: ariChatPulseBorder .9s ease-in-out infinite;
    }
    #ari_chat_widget .ari-chat-fab {
        width: 54px;
        height: 54px;
        border-radius: 999px;
        box-shadow: 0 10px 30px rgba(0,0,0,.18);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        user-select: none;
        background: linear-gradient(135deg, rgba(13,110,253,1), rgba(102,16,242,1));
        color: #fff;
        border: 1px solid rgba(255,255,255,.22);
    }
    #ari_chat_widget .ari-chat-fab .ari-chat-badge {
        position: absolute;
        right: -4px;
        top: -4px;
        min-width: 20px;
        height: 20px;
        padding: 0 6px;
        border-radius: 999px;
        font-size: 12px;
        line-height: 20px;
        background: #dc3545;
        color: #fff;
        text-align: center;
        box-shadow: 0 6px 16px rgba(0,0,0,.18);
        display: none;
    }
    #ari_chat_widget .ari-chat-panel {
        /* Abierto: ~30% de pantalla (con límites para que no quede ni enano ni gigante) */
        width: 30vw;
        min-width: 360px;
        max-width: calc(100vw - 36px);
        height: 460px;
        max-height: calc(100vh - 140px);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 16px 50px rgba(0,0,0,.22);
        background: #fff;
        border: 1px solid rgba(0,0,0,.08);
        display: none;
    }
    #ari_chat_widget.ari-open .ari-chat-panel { display: block; }
    #ari_chat_widget.ari-open .ari-chat-fab { display: none; }
    #ari_chat_widget .ari-chat-header {
        padding: 12px 14px;
        background: linear-gradient(135deg, rgba(13,110,253,.10), rgba(102,16,242,.08));
        border-bottom: 1px solid rgba(0,0,0,.06);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }
    #ari_chat_widget .ari-chat-title {
        font-weight: 800;
        letter-spacing: .2px;
        font-size: 1rem;
        margin: 0;
    }
    #ari_chat_widget .ari-chat-subtitle {
        font-size: .82rem;
        color: #6c757d;
        margin: 0;
        line-height: 1.2;
    }
    #ari_chat_widget .ari-chat-body {
        padding: 12px;
        height: calc(460px - 110px);
        max-height: calc(100vh - 250px);
        overflow: auto;
        background: radial-gradient(circle at 20% 0%, rgba(13,110,253,.05), transparent 40%),
                    radial-gradient(circle at 80% 10%, rgba(25,135,84,.05), transparent 40%),
                    #fff;
    }
    #ari_chat_widget .ari-chat-footer {
        padding: 10px 12px;
        border-top: 1px solid rgba(0,0,0,.06);
        background: #fff;
    }
    #ari_chat_widget .ari-chat-msg {
        margin-bottom: 10px;
        display: flex;
    }
    #ari_chat_widget .ari-chat-msg.ari-assistant { justify-content: flex-start; }
    #ari_chat_widget .ari-chat-msg.ari-system { justify-content: center; }
    #ari_chat_widget .ari-chat-bubble {
        max-width: 88%;
        border-radius: 14px;
        padding: 10px 12px;
        line-height: 1.25;
        font-size: .92rem;
        border: 1px solid rgba(0,0,0,.06);
        box-shadow: 0 6px 18px rgba(0,0,0,.06);
        white-space: normal;
    }
    #ari_chat_widget .ari-chat-msg.ari-assistant .ari-chat-bubble {
        background: #f8f9ff;
        border-color: rgba(102,16,242,.12);
    }
    #ari_chat_widget .ari-chat-msg.ari-system .ari-chat-bubble {
        background: #f8f9fa;
        color: #6c757d;
        font-size: .84rem;
        border-style: dashed;
        box-shadow: none;
    }
    #ari_chat_widget .ari-chat-meta {
        margin-top: 6px;
        font-size: .72rem;
        color: #6c757d;
    }
    #ari_chat_widget .ari-chat-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: .82rem;
        color: #6c757d;
    }
</style>
