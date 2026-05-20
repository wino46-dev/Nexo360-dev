<script>
    /*
     RESUMEN Y PARÁMETROS (2025-09)
     Objetivo: Llamadas SIP vía Janus con VÍDEO VP8 anunciado desde el primer INVITE/ANSWER (requisito 3CX).

     Flujo del fichero (mapa rápido):
     1) Inicialización
        - connect() -> createSession() -> attachPlugin(): abre sesión Janus y adjunta el plugin SIP.
        - sessionCreated(): realiza el REGISTER SIP con los datos de this.userConfig.
     2) Gestión de eventos SIP (onMessage)
        - calling/progress/accepted/updatingcall/hangup/incomingcall: se gestionan estados y JSEP.
        - En progress/accepted/updatingcall se aplica handleRemoteJsep con la JSEP remota cuando procede.
     3) Saliente (doCall)
        - Crea OFFER con audio+vídeo VP8 y ajusta la SDP local con adjustLocalSdpVP8() ANTES de enviarla a 3CX.
        - establishingCall(): envía el INVITE (request: "call") con autoaccept_reinvites.
     4) Entrante (incomingCall -> acceptCall)
        - incomingCall() guarda la oferta remota (si existe) y llama a acceptCall().
        - acceptCall(): crea ANSWER (o una OFFER si la entrante fue "offerless") con audio+vídeo VP8
          y ajusta la SDP con adjustLocalSdpVP8() ANTES de enviar el "accept" a 3CX.
     5) Re-INVITE/UPDATE (updatingcall)
        - Si 3CX agrega vídeo a posteriori, se añade pista local y se responde con ANSWER ajustada por
          adjustLocalSdpVP8().
     6) Medios
        - onlocaltrack/onremotetrack: adjuntan previsualización local y remoto (audio/video) a los elementos.
        - onCleanUp(): libera recursos, detiene tracks y limpia estados.

     Dónde se parametriza (this.userConfig):
       - forceVP8PT: PT dinámico deseado para VP8 (por defecto '96'). Usado en adjustLocalSdpVP8().
       - videoDirection: 'sendrecv' | 'sendonly' | 'recvonly' | 'inactive' (por defecto 'sendrecv'). Usado en adjustLocalSdpVP8().
       - videoBandwidthKbps: b=AS a anunciar en m=video (por defecto 2048). Usado en adjustLocalSdpVP8().
       - videoBitrateKbps: bitrate de captura que pedimos a Janus (por defecto 800000). Usado al crear tracks en doCall/acceptCall/updatingcall.
       - videoFramerate: framerate de captura (por defecto 25). Usado al crear tracks en doCall/acceptCall/updatingcall.

     Notas:
     - adjustLocalSdpH264() queda como legado y no se usa por defecto en el flujo VP8.
     - Las trazas console.* pueden estar en inglés por costumbre de depuración, pero los COMENTARIOS son en español.
    */
     class PhoneCommander {

             static _toB64(uint8arr){
                 try{
                     let str = '';
                     for(let i=0;i<uint8arr.length;i++) str += String.fromCharCode(uint8arr[i]);
                     return btoa(str);
                 }catch(e){ return null; }
             }
        constructor(
            userConfig,
            debug,
            videoTagRef,
            audioTagRef,
            onIncomingCall,
            cleanUp
        ) {
            this.debug = debug;
            this.userConfig = userConfig;
            this.janus = undefined;
            this.sipCall = undefined;
            this.jsep = undefined;
            this.registered = "";
            this.llamadaEstado = "false";
            this.videoTagRef = videoTagRef;
            this.audioTagRef = audioTagRef;
            this.onIncomingCall = onIncomingCall;
            this.cleanUp = cleanUp; // when remote or local hangup callback
            this.offerlessInvite = false;
            this.doAudio = true;
            this.doVideo = true;
            this.localTracks = {};
            this.h264SpropParamSets = null;
            this._spropProbeActive = false;
            this.remoteVideoActive = false;
            this.remoteAudioActive = false;
            this.pendingVideoUpgrade = false;
            this.remoteMedia = { audioStream: null, videoStream: null, audioTrack: null, videoTrack: null };
            this.ringtone = new Howl({
                src: ['sound/ringtone.wav'],
                loop: true,
            });
            this.callInProgress = false;
        } //

        sessionCreated(pluginHandle) {
            this.sipCall = pluginHandle;
            const register = {
                request: "register",
                username: this.userConfig.sipIdentity,
                display_name: this.userConfig.displayName,
                proxy: this.userConfig.sipRegistrar,
                authuser: this.userConfig.username,
                secret: this.userConfig.secret,
            };
            this.sipCall.send({ message: register });
            this.registered = true;
        }

        goPageTotem(page){
            Fancybox.close();
            if(page == 1){
                $('#page1_container').show();
                $('#page_llamada_container').hide();
                $('#page2_container').hide();
                $('#page3_container').hide();
            }else if(page == 11){
                $('#page1_container').hide();
                $('#page_llamada_container').show();
                $('#page2_container').hide();
                $('#page3_container').hide();
            }else if(page == 2){
                $('#page1_container').hide();
                $('#page_llamada_container').hide();
                $('#page2_container').show();
                $('#page3_container').hide();
            }else if(page == 3){
                $('#page1_container').hide();
                $('#page_llamada_container').hide();
                $('#page2_container').hide();
                $('#page3_container').show();

                setTimeout(function(){
                    fancysh();
                }, 500);

            }
        }

        onMessage(msg, jsep) {
            // Guardar en caché la última JSEP enviada por Janus para usarla en eventos posteriores (progress/accepted/updatingcall)
            if (jsep) this.jsep = jsep;
            Janus.debug(" ::: Got a message :::", msg);
            const { error, callId, result } = msg;

            if (error) {
                // Reset status
                if (this.registered) this.sipCall.hangup();
                alert(error);
                console.log(error);
                return;
            }

            const { event } = result;

            if (!result || !event) return;
            if (event === "registration_failed") {
                Janus.warn(`Registration failed: ${result.code} ${result.reason}`);
                //store.commit("SET_REGISTRO", false);
                window.registro = false;
                return;
            }

            if (event === "registered") {
                Janus.log(`Successfully registered as ${result.username}!`);
                if (!this.registered) {
                    this.registered = true;
                }
                //store.commit("SET_REGISTRO", true);
                window.registro = true;
                return;
            }

            if (event === "calling") {
                try{ console.info('[TOTEM][CALL][OUTGOING] calling...'); }catch(e){}
                Janus.log("Waiting for the peer to answer...");
                {{ $totem->mostrar_pagina_2 }}
                @if (!empty($totem->mostrar_pagina_2))
                    this.goPageTotem(2);
                @else
                    this.goPageTotem(3);
                @endif

            }

            if (event === "incomingcall") this.incomingCall(msg.call_id, result, jsep);

            if (event === "progress") {
                try{ console.info('[TOTEM][CALL][PROGRESS] ringing/progress...'); }catch(e){}
                // Usar la JSEP proporcionada por Janus cuando esté disponible; si no, usar la almacenada en caché
                const remoteJsep = jsep || this.jsep;
                if (!remoteJsep) return;
                try{
                    this.sipCall.handleRemoteJsep({
                        jsep: remoteJsep,
                        error: (e) => {
                            console.log(e);
                            this.doHangup();
                        },
                    });
                }catch(e){
                    Janus.warn('Ignoring remote progress JSEP while stable:', e && e.message ? e.message : e);
                }
            }

            if (event === "notify") {
                //store.commit("SET_LLAMADAESTADO", "transferenciaNotificada");
                window.llamadaEstado = "transferenciaNotificada";
            }

            if (event === "hangup") {
                try{ console.warn('[TOTEM][CALL][HANGUP]', result && result.reason ? result.reason : ''); }catch(e){}
                window.llamadaEstado = "intermedio";
                window.llamadaEstado = "hangup";
                this.llamadaEstado = "hangup";
                this.callInProgress = false;
                try{
                    const el = document.getElementById('video_local');
                    if(el && el.srcObject){ el.srcObject.getTracks().forEach(t=>t.stop()); el.srcObject = null; }
                }catch(e){}
                this.goPageTotem(1);
            }

            if (event === "accepted") {
                // Asegurar que la interfaz de llamada se muestre cuando la llamada es aceptada
                try { this.goPageTotem(3); } catch(e) {}
                try{
                    console.group('[TOTEM][CALL][ACCEPTED]');
                    console.log('Event:', 'accepted');
                    if (jsep && jsep.sdp) {
                        console.log('Remote SDP (answer): has video?', jsep.sdp.indexOf('m=video ')>-1);
                        const rtpmap = jsep.sdp.match(/^a=rtpmap:.*$/gm);
                        console.log('Remote a=rtpmap:', rtpmap);
                        const fmtp = jsep.sdp.match(/^a=fmtp:.*$/gm);
                        console.log('Remote a=fmtp:', fmtp);
                    }
                    console.groupEnd();
                }catch(e){}
                //store.commit("SET_LLAMADAESTADO", "intermedio");
                //store.commit("SET_LLAMADAESTADO", "accepted");
                window.llamadaEstado = "intermedio";
                window.llamadaEstado = "accepted";

                this.llamadaEstado = "accepted";
                const remoteJsepAccepted = jsep || this.jsep;
                if (remoteJsepAccepted) {
                    try{
                        this.sipCall.handleRemoteJsep({
                            jsep: remoteJsepAccepted,
                            error: () => this.doHangup(),
                        });
                    }catch(e){
                        // Evitar 'Cannot set remote answer in state stable' ignorando respuestas duplicadas/tardías
                        Janus.warn('Ignoring duplicate/late remote answer:', e && e.message ? e.message : e);
                    }
                }
                this.sipCall.callId = msg.call_id;

            }

            if (event === "updatingcall") {
                try {
                    console.group('[TOTEM][CALL][RE-INVITE / UPDATE]');
                    console.log('Event:', 'updatingcall');
                    const js = jsep || this.jsep;
                    if (js && js.sdp) {
                        console.log('Remote UPDATE has video?', js.sdp.indexOf('m=video ') > -1);
                        const rtpmap = js.sdp.match(/^a=rtpmap:.*$/gm);
                        console.log('UPDATE a=rtpmap:', rtpmap);
                        const fmtp = js.sdp.match(/^a=fmtp:.*$/gm);
                        console.log('UPDATE a=fmtp:', fmtp);
                    }
                    console.groupEnd();
                } catch (e) {}

                if (!this.jsep) {
                    this.jsep = jsep || this.jsep;
                }
                if (!this.jsep || !this.jsep.sdp) {
                    Janus.warn("Updating call without JSEP; ignoring update event to avoid crash");
                    return;
                }

                this.doAudio = this.jsep.sdp.indexOf("m=audio ") > -1;
                this.doVideo = this.jsep.sdp.indexOf("m=video ") > -1;
                // Detectar solicitud remota de activar/elevar VÍDEO (p. ej., la cola habilita vídeo para la extensión)
                if (!this.remoteVideoActive && this.doVideo) {
                    this.pendingVideoUpgrade = true;
                    try { console.warn('[TOTEM][VIDEO-UPGRADE] Requested by 3CX (UPDATE with m=video)'); } catch(e) {}
                }

                let tracks = [];
                tracks.push({ type: "audio", capture: true, recv: true });

                // 👉 Si el remoto ahora pide vídeo y no tenemos pista local, la activamos
                if (this.jsep.sdp.indexOf("m=video ") > -1) {
                    if (!this.localTracks.video) {
                        navigator.mediaDevices.getUserMedia({ video: true })
                            .then(cam => {
                                const vtrack = cam.getVideoTracks()[0];
                                this.localTracks.video = vtrack;
                            })
                            .catch(e => {
                                console.warn("No se pudo abrir la cámara para el re-INVITE de vídeo:", e);
                            });
                    }
                    tracks.push({
                        type: "video",
                        capture: true,
                        recv: true,
                        codec: "vp8",
                        bitrate: (this.userConfig?.videoBitrateKbps||800000),
                        framerate: (this.userConfig?.videoFramerate||25)
                    });
                }

                this.sipCall.createAnswer({
                    jsep: this.jsep,
                    tracks: tracks,
                    success: (IncomingJsep) => {
                        Janus.debug(
                            `Got SDP ${this.jsep.type}! audio=${this.doAudio}, video=${this.doVideo}:`,
                            IncomingJsep
                        );
                        try {
                            console.group('[TOTEM][ANSWER][UPDATE] Local Answer');
                            console.log('Type:', IncomingJsep && IncomingJsep.type);
                            if (IncomingJsep && IncomingJsep.sdp) {
                                const audioLine = (IncomingJsep.sdp.match(/m=audio[^\n]*/)||[''])[0];
                                const videoLine = (IncomingJsep.sdp.match(/m=video[^\n]*/)||[''])[0];
                                console.log('m=audio:', audioLine);
                                console.log('m=video:', videoLine);
                                const rtpmap = IncomingJsep.sdp.match(/^a=rtpmap:.*$/gm);
                                console.log('a=rtpmap:', rtpmap);
                                const fmtp = IncomingJsep.sdp.match(/^a=fmtp:.*$/gm);
                                console.log('a=fmtp:', fmtp);
                            }
                            console.groupEnd();
                        } catch(e){}

                        // Ajusta el SDP local antes de enviar a 3CX: forzamos VP8 y vídeo activo desde el inicio
                        IncomingJsep = this.adjustLocalSdpVP8(IncomingJsep, {
                            forceVP8PT: this.userConfig?.forceVP8PT || '96',
                            videoDir: this.userConfig?.videoDirection || 'sendrecv',
                            addBandwidth: true,
                            bandwidthKbps: this.userConfig?.videoBandwidthKbps || 2048
                        });
                        // Verificación explícita de parámetros VP8 solicitados por 3CX
                        this.verifyVP8Params(IncomingJsep, this.userConfig?.forceVP8PT || '96');
                        const body = { request: "update", call_id: this.sipCall && this.sipCall.callId ? this.sipCall.callId : undefined };
                        this.sipCall.send({ message: body, jsep: IncomingJsep });
                    },
                    error: (error) => {
                        Janus.error("WebRTC error:", error);
                        const body = { request: "decline", code: 480, call_id: this.sipCall && this.sipCall.callId ? this.sipCall.callId : undefined };
                        this.sipCall.send({ message: body });
                    },
                });

                this.goPageTotem(3);
            }
        }

        // Función que llamaremos cuando ocurra el evento "incomingcall"
        // Esta función captura los datos de la llamada entrante, incluyendo video y audio
        // Finalmente, llama a la función acceptCall() para aceptar la llamada
        incomingCall(callId, {
            // Log incoming call
            username, referred_by, srtp,
        }, jsep) {
            // Guardar en caché la oferta JSEP para responder más tarde
            if (jsep) this.jsep = jsep;
            console.log('****** incomingCall');
            Janus.log(`Incoming call from ${username}!`);
            // Guardamos el identificador de llamada para correlacionar peticiones al plugin SIP/3CX
            try { this.sipCall.callId = callId; } catch(e) {}
            this.nombreLLamadaEntrante = `${username}`

            if (jsep) {
                // ¿Qué se ha negociado (audio/vídeo)?
                this.doAudio = (jsep.sdp.indexOf('m=audio ') > -1);
                this.doVideo = (jsep.sdp.indexOf('m=video ') > -1);
                this.doVideo = true;
                Janus.debug(`Audio ${this.doAudio ? 'has' : 'has NOT'} been negotiated`);
                Janus.debug(`Video ${this.doVideo ? 'has' : 'has NOT'} been negotiated`);
            } else {
                Janus.log("This call doesn't contain an offer... we'll need to provide one ourselves");
                this.offerlessInvite = true;
                // Si quieres ofrecer vídeo al responder a una llamada sin oferta (offerless), pon esto a true
                this.doVideo = true;
            }
            // ¿Es el resultado de una transferencia?
            let transfer = '';
            if (referred_by) {
                transfer = ` (referred by ${referred_by})`;
                transfer = transfer.replace(new RegExp('<', 'g'), '&lt');
                // eslint-disable-next-line no-unused-vars
                transfer = transfer.replace(new RegExp('>', 'g'), '&gt');
            }
            // ¿Se indicó algún tipo de seguridad? Si falta el atributo "srtp" significa RTP sin cifrar (plain RTP)
            let rtpType = '';
            if (srtp === 'sdes_optional') rtpType = ' (SDES-SRTP offered)';
            // eslint-disable-next-line no-unused-vars
            else if (srtp === 'sdes_mandatory') rtpType = ' (SDES-SRTP mandatory)';
            // Notify user
            this.acceptCall()
        }

        onLocalTrack(track, on) {
            try{ console.log('[TOTEM][MEDIA][LOCAL TRACK]', track && track.kind, 'on=', on); }catch(e){}
            if(!on) return;
            // Attach local preview to #video_local when video track is available
            try{
                if(track.kind === 'video'){
                    const el = document.getElementById('video_local');
                    if(el){
                        const stream = new MediaStream([track]);
                        Janus.attachMediaStream(el, stream);
                        el.muted = true;
                        el.play && el.play().catch(()=>{});
                    }
                    try{ this.startH264SpropProbe(); }catch(e){}
                }
            }catch(e){
                Janus.warn('Error attaching local preview', e);
            }
        }

        onRemoteTrack(track, mid, on) {
            try{ console.log('[TOTEM][MEDIA][REMOTE TRACK]', track.kind, 'on=', on, 'mid=', mid); }catch(e){}
            console.log('track.kind', track.kind);
            if (track.kind === "audio") {
                if(on && !this.remoteAudioActive){ this.remoteAudioActive = true; console.info('[TOTEM][MEDIA] Remote AUDIO = ON'); }
                if(!on && this.remoteAudioActive){ this.remoteAudioActive = false; console.info('[TOTEM][MEDIA] Remote AUDIO = OFF'); }
                const audioEl = this.audioTagRef;
                if(on){
                    // Maintain a persistent stream for audio
                    if(!this.remoteMedia.audioStream){ this.remoteMedia.audioStream = new MediaStream(); }
                    // Replace existing track if needed
                    if(this.remoteMedia.audioTrack){
                        try{ this.remoteMedia.audioStream.removeTrack(this.remoteMedia.audioTrack); }catch(e){}
                    }
                    this.remoteMedia.audioTrack = track;
                    try{ this.remoteMedia.audioStream.addTrack(track); }catch(e){}
                    Janus.attachMediaStream(audioEl, this.remoteMedia.audioStream);
                }
            } else {
                if(on && !this.remoteVideoActive){
                    this.remoteVideoActive = true;
                    console.info('[TOTEM][MEDIA] Remote VIDEO = ON');
                    // Asegurar que la UI de llamada sea visible en cuanto empiece a fluir el vídeo remoto
                    try { this.goPageTotem(3); } catch(e) {}
                    if(this.pendingVideoUpgrade){
                        try{ console.warn('[TOTEM][VIDEO-UPGRADE] Completed (remote video flowing)'); }catch(e){}
                        this.pendingVideoUpgrade = false;
                    }
                }
                if(!on && this.remoteVideoActive){ this.remoteVideoActive = false; console.info('[TOTEM][MEDIA] Remote VIDEO = OFF'); }
                const videoEl = this.videoTagRef;
                if(on){
                    // Maintain a persistent stream for video to avoid initial rendering glitches
                    if(!this.remoteMedia.videoStream){ this.remoteMedia.videoStream = new MediaStream(); }
                    // Replace existing video track
                    if(this.remoteMedia.videoTrack){
                        try{ this.remoteMedia.videoStream.removeTrack(this.remoteMedia.videoTrack); }catch(e){}
                    }
                    this.remoteMedia.videoTrack = track;
                    try{ this.remoteMedia.videoStream.addTrack(track); }catch(e){}
                    Janus.attachMediaStream(videoEl, this.remoteMedia.videoStream);
                    try {
                        videoEl.playsInline = true;
                        // No silenciar el vídeo remoto; asegurar que la reproducción se inicie
                        const p = videoEl.play && videoEl.play();
                        if(p && typeof p.then === 'function') p.catch(()=>{});
                    } catch(e){}
                }
            }

        }

        onCleanUp() {
            Janus.log(" ::: Got a cleanup notification :::");
            if (this.ringtone.playing()) this.ringtone.stop();
            this.callInProgress = false;
            try{
                const el = document.getElementById('video_local');
                if(el && el.srcObject){ el.srcObject.getTracks().forEach(t=>t.stop()); el.srcObject = null; }
            }catch(e){}
            // Detach remote media streams
            try{
                if(this.remoteMedia.videoStream){ this.remoteMedia.videoStream.getTracks().forEach(t=>t.stop()); }
                if(this.remoteMedia.audioStream){ this.remoteMedia.audioStream.getTracks().forEach(t=>t.stop()); }
                this.remoteMedia = { audioStream: null, videoStream: null, audioTrack: null, videoTrack: null };
                if(this.videoTagRef){ this.videoTagRef.srcObject = null; }
                if(this.audioTagRef){ this.audioTagRef.srcObject = null; }
                this.remoteVideoActive = false;
                this.remoteAudioActive = false;
                this.pendingVideoUpgrade = false;
            }catch(e){}
            if (this.sipCall && this.sipCall.callId)
                this.sipCall = { ...this.sipCall, callId: null };
        }

        numberRandom(){
            let random = Math.random();
            random = random * 10000000 + 1;
            random = Math.trunc(random);
            return random;
        }

        attachPlugin() {

            this.janus.attach({
                plugin: "janus.plugin.sip",
                opaqueId: 'siptest-'+ this.numberRandom(), // generar numero aleatorio
                success: this.sessionCreated.bind(this),
                error: (error) => Janus.error("  -- Error attaching plugin...", error),
                consentDialog: () => Janus.log("consent dialog"),
                iceState: (state) => {
                    Janus.log(`ICE state changed to ${state}`)
                    if(state === 'failed' || state === 'disconnected'){
                        console.warn('[TOTEM][ICE]', 'state:', state, '-> hanging up');
                        try{ this.sipCall && this.sipCall.hangup(); }catch(e){}
                    }
                },
                mediaState: (medium, on) => {
                    Janus.log(`Janus ${on ? "started" : "stopped"} receiving our ${medium}`);
                    try{ console.info('[TOTEM][MEDIA STATE]', medium, on?'ON':'OFF'); }catch(e){}
                },
                webrtcState: (on) => {
                    Janus.log(`Janus says our WebRTC PeerConnection is ${on ? "up" : "down"} now`);
                    try{ console.info('[TOTEM][PC]', on ? 'UP' : 'DOWN'); }catch(e){}
                },
                onmessage: this.onMessage.bind(this),
                onlocaltrack: this.onLocalTrack.bind(this),
                onremotetrack: this.onRemoteTrack.bind(this),
                oncleanup: this.onCleanUp.bind(this),
            });
        }

        createSession() {
            if (!Janus.isWebrtcSupported()) {
                console.error("No WebRTC support...");
                return;
            }
            const janusInstance = new Janus({
                server: window.config.webrtcServer,
                success: this.attachPlugin.bind(this),
                error: (error) => Janus.error(error),
                destroyed: () => console.log("destroyed"),
            });
            this.janus = janusInstance;
        }

        acceptCall() {
            const sipcallAction = this.offerlessInvite
                ? this.sipCall.createOffer
                : this.sipCall.createAnswer;

            if (this.ringtone.playing()) this.ringtone.stop();

            const params = {
                tracks: [
                    { type: "audio", capture: true, recv: true },
                    // Forzar VIDEO desde el inicio en VP8 (requerido por 3CX)
                    // Configurable: usa this.userConfig.videoBitrateKbps y this.userConfig.videoFramerate si existen
                    { type: "video", capture: true, recv: true, codec: "vp8", bitrate: (this.userConfig?.videoBitrateKbps||800000), framerate: (this.userConfig?.videoFramerate||25) },
                ],
                success: (IncomingJsep) => {
                    Janus.debug(
                        `Got SDP ${this.jsep && this.jsep.type ? this.jsep.type : 'answer'}! audio=${this.doAudio}, video=${this.doVideo}:`,
                        IncomingJsep
                    );
                    try {
                        console.group('[TOTEM][ANSWER][ACCEPT] Local Answer');
                        console.log('Type:', IncomingJsep && IncomingJsep.type);
                        if (IncomingJsep && IncomingJsep.sdp) {
                            const audioLine = (IncomingJsep.sdp.match(/m=audio[^\n]*/)||[''])[0];
                            const videoLine = (IncomingJsep.sdp.match(/m=video[^\n]*/)||[''])[0];
                            console.log('m=audio:', audioLine);
                            console.log('m=video:', videoLine);
                            const rtpmap = IncomingJsep.sdp.match(/^a=rtpmap:.*$/gm);
                            console.log('a=rtpmap:', rtpmap);
                            const fmtp = IncomingJsep.sdp.match(/^a=fmtp:.*$/gm);
                            console.log('a=fmtp:', fmtp);
                        }
                        console.groupEnd();
                    } catch(e){}
                    // Ajustar la SDP local para forzar VP8 en vídeo desde el inicio (3CX)
                    IncomingJsep = this.adjustLocalSdpVP8(IncomingJsep, {
                        forceVP8PT: this.userConfig?.forceVP8PT || '96',
                        videoDir: this.userConfig?.videoDirection || 'sendrecv',
                        addBandwidth: true,
                        bandwidthKbps: this.userConfig?.videoBandwidthKbps || 2048
                    });
                    // Verificación explícita de parámetros VP8 solicitados por 3CX
                    this.verifyVP8Params(IncomingJsep, this.userConfig?.forceVP8PT || '96');
                    const body = { request: "accept", call_id: this.sipCall && this.sipCall.callId ? this.sipCall.callId : undefined };
                    this.sipCall.send({ message: body, jsep: IncomingJsep });
                },
                error: (error) => {
                    // Fallback: si falla la apertura de cámara, reintentar ANTES de declinar con vídeo recvonly (sin captura)
                    try{ console.error('WebRTC error en accept (createAnswer/Offer):', error && (error.message||error.name)||error); }catch(e){}
                    const name = (error && (error.name || (error.message||'').split(':')[0])) || '';
                    const gUMError = /NotAllowedError|NotFoundError|NotReadableError|OverconstrainedError|AbortError/i.test(name);
                    if (!this._retryAcceptRecvonly && gUMError) {
                        this._retryAcceptRecvonly = true;
                        try{ console.warn('[TOTEM][FALLBACK] Reintentando respuesta con vídeo recvonly (sin captura)'); }catch(e){}
                        const fallbackParams = {
                            tracks: [
                                { type: 'audio', capture: true, recv: true },
                                { type: 'video', capture: false, recv: true, codec: 'vp8' }
                            ],
                            success: (IncomingJsep2) => {
                                IncomingJsep2 = this.adjustLocalSdpVP8(IncomingJsep2, {
                                    forceVP8PT: this.userConfig?.forceVP8PT || '96',
                                    videoDir: 'recvonly',
                                    addBandwidth: true,
                                    bandwidthKbps: this.userConfig?.videoBandwidthKbps || 2048
                                });
                                this.verifyVP8Params(IncomingJsep2, this.userConfig?.forceVP8PT || '96');
                                const body2 = { request: 'accept', call_id: this.sipCall && this.sipCall.callId ? this.sipCall.callId : undefined };
                                this.sipCall.send({ message: body2, jsep: IncomingJsep2 });
                            },
                            error: (e2) => {
                                console.error('WebRTC error (fallback recvonly) en accept ...', e2 && (e2.message || e2));
                                const body = { request: 'decline', code: 480, call_id: this.sipCall && this.sipCall.callId ? this.sipCall.callId : undefined };
                                this.sipCall.send({ message: body });
                            },
                        };
                        if (!this.offerlessInvite) fallbackParams.jsep = this.jsep;
                        return sipcallAction(fallbackParams);
                    }
                    Janus.error("WebRTC error:", error);
                    const body = { request: "decline", code: 480, call_id: this.sipCall && this.sipCall.callId ? this.sipCall.callId : undefined };
                    this.sipCall.send({ message: body });
                },
            };
            // Solo pasar una JSEP cuando estemos respondiendo a una oferta real; en llamadas sin oferta (offerless) no se debe pasar jsep
            if (!this.offerlessInvite) {
                params.jsep = this.jsep;
            }
            sipcallAction(params);
        }

        declineCall() {
            if (this.ringtone.playing()) this.ringtone.stop();
            const body = { request: "decline", call_id: this.sipCall && this.sipCall.callId ? this.sipCall.callId : undefined };
            this.sipCall.send({ message: body });
        }

        hold() {
            const body = {
                request: "hold",
                call_id: this.sipCall && this.sipCall.callId ? this.sipCall.callId : undefined,
            };
            this.sipCall.send({ message: body });
        }

        unhold() {
            const body = {
                request: "unhold",
                call_id: this.sipCall && this.sipCall.callId ? this.sipCall.callId : undefined,
            };
            this.sipCall.send({ message: body });
        }

        doHangup() {
            try{ console.warn('[TOTEM][CALL][LOCAL HANGUP]'); }catch(e){}
            if (this.ringtone.playing()) this.ringtone.stop();
            const hangupMsg = { request: "hangup", call_id: this.sipCall && this.sipCall.callId ? this.sipCall.callId : undefined };
            try{ this.sipCall.send({ message: hangupMsg }); }catch(e){}
            try{ this.sipCall.hangup(); }catch(e){}
            this.callInProgress = false;
        }


        /**
          * Corrige el SDP para vídeo en VP8 para 3CX desde el inicio.
          * - Fuerza m=video en modo sendrecv (configurable) y protocolo SAVPF con rtcp-mux
          * - Coloca VP8 (PT dinámico) el primero en la m=video; opcionalmente elimina otros códecs
          * - Añade/ordena rtpmap/fmtp necesarios; puede añadir b=AS para ancho de banda
          * - Limpia líneas conflictivas (extmap/rtcp-fb) si es necesario
          *
          * Configuración por variables (ejemplos):
          *   this.userConfig.forceVP8PT = '96';             // PT que queremos para VP8
          *   this.userConfig.videoDirection = 'sendrecv';   // 'sendrecv' | 'sendonly' | 'recvonly' | 'inactive'
          *   this.userConfig.videoBandwidthKbps = 2048;     // b=AS a anunciar en SDP
          *   this.userConfig.videoBitrateKbps = 800000;     // bitrate de captura (Janus)
          *   this.userConfig.videoFramerate = 25;           // framerate de captura (Janus)
          */
         adjustLocalSdpVP8(jsep, opts = {}) {
            if (!jsep || !jsep.sdp) return jsep;
            const {
                forceVP8PT = '96',
                videoDir = 'sendrecv',
                addBandwidth = true,
                bandwidthKbps = 2048,
                keepH264 = false,           // si true, dejamos H264 después de VP8 como fallback
                addImageAttr = false,
                imageSend = '[x=1280,y=720]',
                imageRecv = '[x=1280,y=720]'
            } = opts;

            let sdp = jsep.sdp;
            const CRLF = '\r\n';

            function getSection(s, kind) {
                const m = s.match(new RegExp(`(^|\\r?\\n)m=${kind}[^\\r\\n]*`, 'i'));
                if (!m) return null;
                const start = m.index + (m[1] ? m[1].length : 0);
                const rest  = s.slice(start);
                const next  = rest.search(/\r?\nm=/i);
                const end   = next === -1 ? s.length : start + next;
                return { start, end, text: s.slice(start, end) };
            }
            function replaceSection(s, bounds, newText) {
                return s.slice(0, bounds.start) + newText + s.slice(bounds.end);
            }

            // Asegura SAVPF en vídeo
            sdp = sdp.replace(/(m=video\s+\d+\s+)(UDP\/TLS\/RTP\/SAVP)(F?)/i, (_, pre) => `${pre}UDP/TLS/RTP/SAVPF`);
            const vsec = getSection(sdp, 'video');
            if (vsec) {
                let v = vsec.text;

                // Limpieza básica para evitar rechazos
                v = v.replace(/^a=extmap:.*\r?\n/gmi, '')
                     .replace(/^a=rtcp-fb:.*\r?\n/gmi, '');

                // Dirección y rtcp-mux
                const videoHeader = /(^m=video[^\r\n]*\r?\n)/i;
                v = v.replace(/^a=(sendrecv|sendonly|recvonly|inactive)\s*$/gmi, '');
                if (videoHeader.test(v)) {
                    v = v.replace(videoHeader, (m) => `${m}a=${videoDir}${CRLF}a=rtcp-mux${CRLF}`);
                } else {
                    v += `${CRLF}a=${videoDir}${CRLF}a=rtcp-mux${CRLF}`;
                }

                // Mapas RTP
                const mLine = v.match(/^m=video\s+\d+\s+\S+\s+([^\r\n]+)$/im);
                if (mLine) {
                    let pts = mLine[1].trim().split(/\s+/).filter(Boolean);
                    const rtpmap = Array.from(v.matchAll(/^a=rtpmap:(\d+)\s+([^\s\/]+)\/(\d+)/gmi));
                    const codecByPT = new Map(rtpmap.map(m => [m[1], m[2].toUpperCase()]));
                    const mappedPTs = new Set(rtpmap.map(m => m[1]));

                    // Purga PTs inválidos o sin rtpmap; deja solo dinámicos 96-127
                    pts = pts.filter(pt => mappedPTs.has(pt)).filter(pt => { const n = +pt; return n >= 96 && n <= 127; });
                    v = v.replace(/^m=video[^\r\n]*$/im, line => `${line.replace(/\s+[0-9\s]+$/, '')} ${pts.join(' ')}`);

                    // Localiza VP8 o crea uno nuevo con PT deseado
                    let vp8PT = null;
                    for (const [pt, c] of codecByPT.entries()) if (c === 'VP8') { vp8PT = pt; break; }
                    if (!vp8PT) {
                        if (!pts.includes(forceVP8PT)) {
                            pts.unshift(forceVP8PT);
                            v = v.replace(/^m=video[^\r\n]*$/im, line => `${line.replace(/\s+[0-9\s]+$/, '')} ${pts.join(' ')}`);
                        }
                        v += (v.endsWith(CRLF) ? '' : CRLF) + `a=rtpmap:${forceVP8PT} VP8/90000${CRLF}`;
                        vp8PT = forceVP8PT;
                    }

                    // Bandwidth b=AS
                    if (addBandwidth && !/^b=AS:/im.test(v)) {
                        v = v.replace(/(^m=video[^\r\n]*\r?\n)/i, `$1b=AS:${bandwidthKbps}${CRLF}`);
                    }

                    // imageattr opcional
                    if (addImageAttr && !new RegExp(`^a=imageattr:${vp8PT}\\b`, 'im').test(v)) {
                        v += `${CRLF}a=imageattr:${vp8PT} send ${imageSend} recv ${imageRecv}`;
                    }

                    // Si no queremos H264, lo eliminamos de m=video y de atributos
                    if (!keepH264) {
                        for (const [pt, c] of codecByPT.entries()) {
                            if (c === 'H264') {
                                v = v.replace(new RegExp(`^a=rtpmap:${pt}.*\\r?\\n`, 'gmi'), '');
                                v = v.replace(new RegExp(`^a=fmtp:${pt}.*\\r?\\n`, 'gmi'), '');
                                pts = pts.filter(x => x !== pt);
                            }
                        }
                    }

                    // Reordenar: VP8 primero, luego lo que quede
                    const want = [vp8PT, ...pts.filter(pt => pt !== vp8PT)];
                    v = v.replace(/^m=video[^\r\n]*$/im, line => `${line.replace(/\s+[0-9\s]+$/, '')} ${Array.from(new Set(want)).join(' ')}`);

                    sdp = replaceSection(sdp, vsec, v);
                }
            }

            // Limpieza global
            sdp = sdp.replace(/^a=extmap:.*\r?\n/gmi, '')
                     .replace(/^a=rtcp-fb:.*\r?\n/gmi, '');

            return { ...jsep, sdp };
         }

         // Verificador de parámetros VP8 requeridos por 3CX
         // Comprueba que existan:
         //  - atributo de medios a=rtpmap
         //  - formato dinámico (PT) esperado (por defecto 96) en m=video
         //  - mime/type VP8
         //  - sample rate 90000
         // Devuelve un objeto con flags y escribe logs de diagnóstico.
         verifyVP8Params(jsep, expectedPT = (this.userConfig?.forceVP8PT || '96')) {
             try {
                 const sdp = jsep && jsep.sdp ? jsep.sdp : '';
                 const hasRtpmap = /^(a=rtpmap:\d+\s+[^\s]+\/\d+)/gmi.test(sdp);
                 const mVideo = (sdp.match(/^m=video[^\r\n]*/mi) || [''])[0];
                 const pts = (mVideo.match(/\s(\d+(?:\s+\d+)*)$/) || [,''])[1].trim().split(/\s+/).filter(Boolean);
                 const hasExpectedPT = pts.includes(String(expectedPT));
                 const rtpmapLine = (sdp.match(new RegExp(`^a=rtpmap:${expectedPT}\\s+([^\\s/]+)/(\\d+)`, 'mi')) || []);
                 const mime = (rtpmapLine[1] || '').toUpperCase();
                 const rate = rtpmapLine[2] || '';
                 const isVP8 = (mime === 'VP8');
                 const is90000 = (rate === '90000');
                 const ok = !!(hasRtpmap && hasExpectedPT && isVP8 && is90000);
                 console.group('[TOTEM][SDP][CHECK][VP8]');
                 console.log('PT esperado:', expectedPT);
                 console.log('a=rtpmap presente:', hasRtpmap);
                 console.log('m=video incluye PT esperado:', hasExpectedPT);
                 console.log('mime/type (rtpmap) es VP8:', isVP8);
                 console.log('sample rate es 90000:', is90000);
                 console.log('Resultado final OK:', ok);
                 console.groupEnd();
                 return { ok, hasRtpmap, hasExpectedPT, isVP8, is90000, mime: mime || null, rate: rate || null };
             } catch (e) {
                 try { console.warn('[TOTEM][SDP][CHECK][VP8] Error al verificar', e); } catch(_) {}
                 return { ok: false, error: true };
             }
         }

        /**
          * Corrige el SDP para vídeo:
          * - Asegura H264 con a=rtpmap y a=fmtp válidos (packetization-mode=1; profile-level-id)
          * - Elimina PTs de m=video que no tengan a=rtpmap (p.ej. 39 Unassigned)
          * - Ordena H264 primero en m=video (luego VP8 y el resto)
          * - Asegura a=rtcp-mux y añade rtcp-fb básicos (nack/pli) para H264/VP8
          *
          */

         adjustLocalSdpH264(jsep, opts = {}) {
             // LEGADO: esta función queda para compatibilidad futura; no se usa en los flujos VP8 actuales
             if (!jsep || !jsep.sdp) return jsep;

             // If includeSprop=true and no sprop provided, try to extract SPS/PPS from the browser encoder
             // by forcing a tiny H264 keyframe and reading an H264 Annex B sample if Insertable Streams are available.
             // If not available, we keep existing behavior (fallback default SPS/PPS).

             const {
                 // VIDEO
                 forceH264PT   = '99',
                 profileLevelId= '640028', // 640028 Higline --  usa '42e01f' si prefieres Baseline
                 videoDir      = 'sendrecv',
                 addBandwidth  = true,
                 bandwidthKbps = 4096,
                 addImageAttr  = true,
                 includeSprop = true,             // En 3CX v20 (colas) enviar sprop-parameter-sets en el INVITE/answers
                 spropParameterSets = 'Z0KADJWgUH5A,aM4Ecg==',
                 imageSend     = '[x=1920,y=1080]',
                 imageRecv     = '[x=1920,y=1080]',
                 keepDTMF      = true
             } = opts;

             let sdp = jsep.sdp;
             const CRLF = '\r\n';

             // Helpers
             function getSection(sdpText, kind) {
                 const m = sdpText.match(new RegExp(`(^|\\r?\\n)m=${kind}[^\\r\\n]*`, 'i'));
                 if (!m) return null;
                 const start = m.index + (m[1] ? m[1].length : 0);
                 const rest  = sdpText.slice(start);
                 const next  = rest.search(/\r?\nm=/i);
                 const end   = next === -1 ? sdpText.length : start + next;
                 return { start, end, text: sdpText.slice(start, end) };
             }
             function replaceSection(sdpText, bounds, newText) {
                 return sdpText.slice(0, bounds.start) + newText + sdpText.slice(bounds.end);
             }

             /* ============== VIDEO ============== */
             sdp = sdp.replace(/(m=video\s+\d+\s+)(UDP\/TLS\/RTP\/SAVP)(F?)/i, (_, pre) => `${pre}UDP/TLS/RTP/SAVPF`);
             const vsec = getSection(sdp, 'video');
             if (vsec) {
                 let v = vsec.text;

                 // Quitar extmap y rtcp-fb (todo)
                 v = v.replace(/^a=extmap:.*\r?\n/gmi, '')
                     .replace(/^a=rtcp-fb:.*\r?\n/gmi, '');

                 // Asegurar a=sendrecv debajo de m=video y a=rtcp-mux
                 const videoHeader = /(^m=video[^\r\n]*\r?\n)/i;
                 v = v.replace(/^a=(sendrecv|sendonly|recvonly|inactive)\s*$/gmi, '');
                 if (videoHeader.test(v)) {
                     v = v.replace(videoHeader, (m) => `${m}a=${videoDir}${CRLF}a=rtcp-mux${CRLF}`);
                 } else {
                     v += `${CRLF}a=${videoDir}${CRLF}a=rtcp-mux${CRLF}`;
                 }

                 // PTs y mapas
                 const mLine = v.match(/^m=video\s+\d+\s+\S+\s+([^\r\n]+)$/im);
                 if (mLine) {
                     let pts = mLine[1].trim().split(/\s+/).filter(Boolean);
                     const rtpmap = Array.from(v.matchAll(/^a=rtpmap:(\d+)\s+([^\s/]+)\/(\d+)/gmi));
                     const fmtp   = new Map(Array.from(v.matchAll(/^a=fmtp:(\d+)\s+([^\r\n]+)/gmi)).map(m => [m[1], m[2]]));
                     const codecByPT = new Map(rtpmap.map(m => [m[1], m[2].toUpperCase()]));
                     const mappedPTs = new Set(rtpmap.map(m => m[1]));

                     // Purga PTs sin rtpmap y no dinámicos
                     pts = pts.filter(pt => mappedPTs.has(pt)).filter(pt => {
                         const n = +pt; return n >= 96 && n <= 127;
                     });
                     v = v.replace(/^m=video[^\r\n]*$/im, line => `${line.replace(/\s+[0-9\s]+$/, '')} ${pts.join(' ')}`);

                     // Asegurar H264
                     let h264PT = null;
                     for (const [pt, c] of codecByPT.entries()) if (c === 'H264') { h264PT = pt; break; }
                     if (!h264PT) {
                         if (!pts.includes(forceH264PT)) {
                             pts.unshift(forceH264PT);
                             v = v.replace(/^m=video[^\r\n]*$/im, line => `${line.replace(/\s+[0-9\s]+$/, '')} ${pts.join(' ')}`);
                         }
                         v += (v.endsWith(CRLF) ? '' : CRLF) + `a=rtpmap:${forceH264PT} H264/90000${CRLF}`;
                         h264PT = forceH264PT;
                     }

                     // fmtp (orden y espacios correctos)
                    // Intenta conservar sprop-parameter-sets que ya haya generado el navegador
                    const existingFmtpLine = fmtp.get(h264PT) || '';
                    const existingSprop = (existingFmtpLine.match(/sprop-parameter-sets=([^;\r\n]+)/i)||[])[1] || '';
                    const makeFmtpH264 = () => {
                        const parts = [
                            'packetization-mode=1',
                            `profile-level-id=${profileLevelId}`,
                            'level-asymmetry-allowed=1'
                        ];
                        const wantedSprop = (spropParameterSets && spropParameterSets.trim()) ? spropParameterSets.trim() : existingSprop;
                        if (includeSprop && wantedSprop) {
                            // Validación básica del base64 (SPS,PPS)
                            const [sps, pps] = String(wantedSprop).split(',');
                            const b64ok = (s) => /^[A-Za-z0-9+/=]+$/.test(s || '');
                            if (b64ok(sps) && b64ok(pps)) {
                                parts.push(`sprop-parameter-sets=${sps.replace(/\s+/g,'')},${pps.replace(/\s+/g,'')}`);
                            }
                        }
                        return parts.join('; ');
                    };
                    const fmtpH264 = makeFmtpH264();


                     if (fmtp.has(h264PT)) {
                         v = v.replace(new RegExp(`^a=fmtp:${h264PT}\\s+.*$`, 'im'), `a=fmtp:${h264PT} ${fmtpH264}`);
                     } else {
                         v += (v.endsWith(CRLF) ? '' : CRLF) + `a=fmtp:${h264PT} ${fmtpH264}`;
                     }

                     // Forzar PT=99 si era otro
                     if (h264PT !== forceH264PT) {
                         v = v.replace(/^m=video[^\r\n]*$/im, line => {
                             const head = line.replace(/\s+[0-9\s]+$/, '');
                             const cur  = line.split(/\s+/).slice(3);
                             return `${head} ${cur.map(x => x === h264PT ? forceH264PT : x).join(' ')}`;
                         });
                         v = v.replace(new RegExp(`^a=rtpmap:${h264PT}\\b`, 'gmi'), `a=rtpmap:${forceH264PT}`);
                         v = v.replace(new RegExp(`^a=fmtp:${h264PT}\\b`, 'gmi'),   `a=fmtp:${forceH264PT}`);
                         h264PT = forceH264PT;
                     }

                     // (Opcional) b=AS
                     if (addBandwidth && !/^b=AS:/im.test(v)) {
                         v = v.replace(/(^m=video[^\r\n]*\r?\n)/i, `$1b=AS:${bandwidthKbps}${CRLF}`);
                     }

                     // Añadir imageattr con el PT de vídeo que presentamos (99)
                     if (addImageAttr && !new RegExp(`^a=imageattr:${h264PT}\\b`, 'im').test(v)) {
                         v += `${CRLF}a=imageattr:${h264PT} send ${imageSend} recv ${imageRecv}`;
                     }

                     // Reconstruir m=video solo con PTs que tengan rtpmap (dejamos 99)
                     const rtpmapPTs = new Set(Array.from(v.matchAll(/^a=rtpmap:(\d+)/gmi)).map(m => m[1]));
                     const finalPts  = [h264PT].filter(pt => rtpmapPTs.has(pt));
                     v = v.replace(/^m=video[^\r\n]*$/im, line => `${line.replace(/\s+[0-9\s]+$/, '')} ${finalPts.join(' ')}`);

                     // Normaliza espaciado
                     v = v.replace(/;\s+/g, '; ').replace(/,\s+/g, ',');

                     sdp = replaceSection(sdp, vsec, v);
                 }
             }

             /* ============== AUDIO ============== */
             const asec = getSection(sdp, 'audio');
             if (asec) {
                 let a = asec.text;

                 // Quitar extmap (y cualquier rtcp-fb que quede por si acaso)
                 a = a.replace(/^a=extmap:.*\r?\n/gmi, '')
                     .replace(/^a=rtcp-fb:.*\r?\n/gmi, '');

                 // Asegurar a=sendrecv justo tras m=audio
                 const audioHeader = /(^m=audio[^\r\n]*\r?\n)/i;
                 a = a.replace(/^a=(sendrecv|sendonly|recvonly|inactive)\s*$/gmi, '');
                 if (audioHeader.test(a)) {
                     a = a.replace(audioHeader, (m) => `${m}a=sendrecv${CRLF}`);
                 } else {
                     a += `${CRLF}a=sendrecv${CRLF}`;
                 }

                 const mLineA = a.match(/^m=audio\s+\d+\s+\S+\s+([^\r\n]+)$/im);
                 if (mLineA) {
                     let ptsA = mLineA[1].trim().split(/\s+/).filter(Boolean);

                     const mapsA = Array.from(a.matchAll(/^a=rtpmap:(\d+)\s+([^\s/]+)\/(\d+)/gmi));
                     const codecByPTA = new Map(mapsA.map(m => [m[1], m[2].toUpperCase()]));
                     const mappedPTsA = new Set(mapsA.map(m => m[1]));

                     // PTs estáticos
                     const ptPCMU = '0', ptPCMA = '8', ptG729 = '18', ptG722 = '9';

                     // Asegurar rtpmap de G729 (algunos equipos lo requieren)
                     if (!/^a=rtpmap:18\s+G729\/8000/im.test(a)) a += `${CRLF}a=rtpmap:18 G729/8000`;

                     // Quitar OPUS/RED/CN
                     const bad = ['OPUS','RED','CN'];
                     for (const [pt, c] of codecByPTA.entries()) {
                         if (bad.includes(c)) {
                             a = a.replace(new RegExp(`^a=rtpmap:${pt}.*\\r?\\n`, 'gmi'), '');
                             a = a.replace(new RegExp(`^a=fmtp:${pt}.*\\r?\\n`, 'gmi'), '');
                             ptsA = ptsA.filter(x => x !== pt);
                         }
                     }

                     // DTMF
                     let dtmfPTs = [];
                     for (const [pt, c] of codecByPTA.entries()) if (c === 'TELEPHONE-EVENT') dtmfPTs.push(pt);
                     if (!keepDTMF) dtmfPTs = [];

                     // Orden final: PCMA(8), G729(18), PCMU(0), G722(9), DTMF
                     const want = [];
                     [ptPCMA, ptG729, ptPCMU, ptG722].forEach(pt => {
                         if (ptsA.includes(pt) || mappedPTsA.has(pt)) want.push(pt);
                     });
                     dtmfPTs.forEach(pt => { if (!want.includes(pt)) want.push(pt); });

                     a = a.replace(/^m=audio[^\r\n]*$/im, line => `${line.replace(/\s+[0-9\s]+$/, '')} ${want.join(' ')}`);

                     sdp = replaceSection(sdp, asec, a);
                 }
             }

             // Por si quedara algún extmap o rtcp-fb perdido en otra sección, purgamos en todo el SDP
             sdp = sdp.replace(/^a=extmap:.*\r?\n/gmi, '')
                 .replace(/^a=rtcp-fb:.*\r?\n/gmi, '');

             return { ...jsep, sdp };
         }

        establishingCall(jsep) {
            try {
                console.group('[TOTEM][INVITE] Enviando INVITE a 3CX (vista completa)');
                const sdp = (jsep && jsep.sdp) ? jsep.sdp : '';
                const from = this.userConfig?.sipIdentity || '';
                const display = this.userConfig?.displayName || '';
                const to = this.callingUri || '';
                const contact = from;
                const registrar = this.userConfig?.sipRegistrar || '';
                const viaHost = (registrar || '').replace(/^sip:/i,'').replace(/;.*$/,'');
                const invite =
`INVITE ${to} SIP/2.0
Via: SIP/2.0/WSS ${viaHost};branch=z9hG4bK-${(Math.random().toString(36).slice(2))}
From: "${display}" <${from}>;tag=${(Math.random().toString(36).slice(2))}
To: <${to}>
Contact: <${contact}>
Max-Forwards: 70
CSeq: 1 INVITE
Call-ID: ${(Date.now())}-${(Math.random().toString(36).slice(2))}@${viaHost}
Supported: replaces, outbound
Content-Type: application/sdp
Content-Length: ${sdp.length}

${sdp}`;
                console.log(invite);
                console.log('autoaccept_reinvites:', true);
                console.groupEnd();
            } catch(e) {}
            Janus.debug("Got SDP!", jsep);
            this.jsep = jsep;
            const body = {
                request: "call",
                uri: this.callingUri,
                autoaccept_reinvites: true,
            };
            this.sipCall.send({ message: body, jsep });
        }

        doCall(uri, doVideo = false, referId) {
            if(this.callInProgress){
                Janus.warn('Call already in progress, ignoring doCall');
                return;
            }

            this.callingUri = uri;
            let tracks = [];
            tracks.push({ type: "audio", capture: true, recv: true });
            // VÍDEO desde el inicio en VP8
            // Puedes ajustar bitrate y framerate con userConfig.videoBitrateKbps y userConfig.videoFramerate
            tracks.push({
                type: "video",
                capture: true,
                recv: true,
                codec: "vp8",
                bitrate: (this.userConfig?.videoBitrateKbps||800000),
                framerate: (this.userConfig?.videoFramerate||25),
            });

            this.sipCall.createOffer({
                tracks,
                success: (jsep) => {
                    // Forzar VP8 desde el inicio en la OFERTA local
                    jsep = this.adjustLocalSdpVP8(jsep, {
                        forceVP8PT: this.userConfig?.forceVP8PT || '96',
                        videoDir: this.userConfig?.videoDirection || 'sendrecv',
                        addBandwidth: true,
                        bandwidthKbps: this.userConfig?.videoBandwidthKbps || 2048
                    });

                    // Verificación explícita de parámetros VP8 solicitados por 3CX
                    this.verifyVP8Params(jsep, this.userConfig?.forceVP8PT || '96');

                    // ==== INSPECTOR SDP ====
                    try {
                        const sdp = jsep?.sdp || '';
                        const CRLF = /\r\n|\n/;
                        const lines = sdp.split(CRLF);

                        const sect = { audio: null, video: null };
                        let current = null, buf = [];
                        const flush = () => {
                            if (!current) return;
                            const text = buf.join('\n');
                            sect[current.kind] = { m: current.m, text };
                        };
                        for (const l of lines) {
                            if (/^m=audio/i.test(l) || /^m=video/i.test(l)) {
                                flush();
                                const kind = /^m=audio/i.test(l) ? 'audio' : 'video';
                                current = { kind, m: l };
                                buf = [l];
                            } else if (current) {
                                buf.push(l);
                            }
                        }
                        flush();

                        function parseM(mline) {
                            if (!mline) return null;
                            const m = mline.match(/^m=(audio|video)\s+(\d+)\s+(\S+)\s+(.*)$/i);
                            if (!m) return null;
                            const kind = m[1].toLowerCase();
                            const port = m[2];
                            const proto = m[3];
                            const pts = m[4] ? m[4].trim().split(/\s+/) : [];
                            return { kind, port, proto, pts };
                        }

                        function rtpMaps(text) {
                            const maps = new Map();
                            const fmtp = new Map();
                            (text.match(/^a=rtpmap:\d+.*$/gmi) || []).forEach(l => {
                                const mm = l.match(/^a=rtpmap:(\d+)\s+([^\s/]+)\/(\d+)/i);
                                if (mm) maps.set(mm[1], { codec: mm[2].toUpperCase(), rate: mm[3] });
                            });
                            (text.match(/^a=fmtp:\d+.*$/gmi) || []).forEach(l => {
                                const mm = l.match(/^a=fmtp:(\d+)\s+(.*)$/i);
                                if (mm) fmtp.set(mm[1], mm[2]);
                            });
                            return { maps, fmtp };
                        }

                        // 👉 helper para dirección (sendrecv/recvonly/sendonly/inactive)
                        function _dir(text) {
                            const m = (text.match(/^a=(sendrecv|sendonly|recvonly|inactive)/gmi) || [])[0];
                            return m ? m.replace('a=','') : 'sendrecv';
                        }

                        const a = sect.audio ? parseM(sect.audio.m) : null;
                        const v = sect.video ? parseM(sect.video.m) : null;
                        const aMap = sect.audio ? rtpMaps(sect.audio.text) : { maps:new Map(), fmtp:new Map() };
                        const vMap = sect.video ? rtpMaps(sect.video.text) : { maps:new Map(), fmtp:new Map() };

                        console.group('[TOTEM][INVITE][LOCAL OFFER]');
                        console.log('Type:', jsep?.type);
                        console.log(sdp);

                        // Resumen
                        console.group('Resumen');
                        console.log('Audio ofrecido:', !!a);
                        console.log('Video ofrecido:', !!v);
                        if (a) console.log(`[AUDIO] proto=${a.proto} pts=${a.pts.join(', ')}`);
                        if (v) console.log(`[VIDEO] proto=${v.proto} pts=${v.pts.join(', ')}`);
                        if (sect.audio) console.log('dir(audio)=', _dir(sect.audio.text));
                        if (sect.video) console.log('dir(video)=', _dir(sect.video.text));
                        console.groupEnd();

                        // Línea m=video y detalles de códecs locales
                        if (v) {
                            const mVideoLocal = (sdp.match(/^m=video[^\r\n]*/m) || ['(no m=video)'])[0];
                            console.group('SDP VIDEO (local)');
                            console.log('m=video (local):', mVideoLocal);
                            const rtpmapH264 = sdp.match(/^a=rtpmap:\d+\s+H264\/90000.*$/gmi) || [];
                            const fmtpH264   = sdp.match(/^a=fmtp:\d+.*H264.*$/gmi) || [];
                            console.log('H264 rtpmap:', rtpmapH264);
                            console.log('H264 fmtp:', fmtpH264);
                            console.groupEnd();
                        }

                        // Audio
                        if (a) {
                            console.group('Audio codecs (PT -> codec)');
                            a.pts.forEach(pt => {
                                const m = aMap.maps.get(pt);
                                console.log(`${pt} -> ${m ? `${m.codec}/${m.rate}` : 'UNMAPPED ❌'}`);
                            });
                            console.groupEnd();
                        }

                        // Video
                        if (v) {
                            console.group('Video codecs (PT -> codec)');
                            const unmapped = [];
                            v.pts.forEach(pt => {
                                const m = vMap.maps.get(pt);
                                if (!m) { unmapped.push(pt); }
                                console.log(`${pt} -> ${m ? `${m.codec}/${m.rate}` : 'UNMAPPED ❌'}`);
                            });
                            if (unmapped.length) console.error('PTs de vídeo sin rtpmap:', unmapped.join(', '));
                            console.groupEnd();

                            // Checks específicos H264
                            const h264PT = [...vMap.maps.entries()].find(([,val]) => val.codec === 'H264')?.[0];
                            console.group('Checks H264');
                            console.log('H264 PT:', h264PT || 'no');
                            if (h264PT) {
                                const fmtp = vMap.fmtp.get(h264PT) || '';
                                console.log('fmtp:', fmtp || '(faltaba fmtp)');
                                console.log('packetization-mode=1:', /packetization-mode=1\b/i.test(fmtp));
                                console.log('profile-level-id:', (fmtp.match(/profile-level-id=([0-9a-fA-F]+)/)||[])[1] || '(no)');
                            }
                            console.log('rtcp-mux:', /^a=rtcp-mux$/mi.test(sect.video.text));
                            console.groupEnd();
                        }

                        console.groupEnd();
                    } catch (e) {
                        console.warn('Error logging local offer SDP', e);
                    }
                    // ==== FIN INSPECTOR ====

                    this.callInProgress = true;
                    this.establishingCall(jsep);
                },
                error: (err) => {
                    // Fallback: si falla la cámara/micrófono, reintentar con vídeo en modo recvonly (capture:false)
                    try{ console.error('WebRTC error en createOffer:', err && (err.message||err.name)||err); }catch(e){}
                    const name = (err && (err.name || (err.message||'').split(':')[0])) || '';
                    const gUMError = /NotAllowedError|NotFoundError|NotReadableError|OverconstrainedError|AbortError/i.test(name);
                    if (!this._retryOfferRecvonly && gUMError) {
                        this._retryOfferRecvonly = true;
                        try{ console.warn('[TOTEM][FALLBACK] Reintentando oferta con vídeo recvonly (sin captura)'); }catch(e){}
                        const tracksFallback = [
                            { type: 'audio', capture: true, recv: true },
                            { type: 'video', capture: false, recv: true, codec: 'vp8' }
                        ];
                        return this.sipCall.createOffer({
                            tracks: tracksFallback,
                            success: (jsep2) => {
                                jsep2 = this.adjustLocalSdpVP8(jsep2, {
                                    forceVP8PT: this.userConfig?.forceVP8PT || '96',
                                    videoDir: 'recvonly',
                                    addBandwidth: true,
                                    bandwidthKbps: this.userConfig?.videoBandwidthKbps || 2048
                                });
                                this.verifyVP8Params(jsep2, this.userConfig?.forceVP8PT || '96');
                                this.callInProgress = true;
                                this.establishingCall(jsep2);
                            },
                            error: (e2) => {
                                console.error('WebRTC error (fallback recvonly) ...', e2 && (e2.message || e2));
                            }
                        });
                    }
                    console.error('WebRTC error...', err?.message || err);
                },
            });
        }

        disconnect() {
            this.janus.destroy();
            //store.commit("SET_REGISTRO", false);
            window.registro = false;
        }

        connect() {
            Janus.init({
                debug: this.debug,
                dependencies: Janus.useDefaultDependencies({ adapter }),
                callback: this.createSession.bind(this),
            });
        }

        dtmfEnvio(tecla) {
            if (this.llamadaEstado === "accepted") {
                const body = {
                    request: "dtmf_info",
                    digit: tecla.toString(),
                    call_id: this.sipCall && this.sipCall.callId ? this.sipCall.callId : undefined,
                };
                this.sipCall.send({ message: body });
            }
        }
        isConnected() {
            return this.janus.isConnected();
        }

        reconnect() {
            this.janus.reconnect();
        }
        getSessionId() {
            return this.janus.getSessionId();
        }
        getCallStatus() {
            return {
                inCall: !!this.callInProgress,
                estado: this.llamadaEstado,
                remoteVideo: !!this.remoteVideoActive,
                remoteAudio: !!this.remoteAudioActive,
                pendingVideoUpgrade: !!this.pendingVideoUpgrade
            };
        }
    }

</script>
