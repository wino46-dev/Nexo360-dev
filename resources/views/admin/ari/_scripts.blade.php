<script>
    (function(){
        var reservationRecommendationsUrl = @json(route((request()->is('external*') ? 'external' : 'admin').'.call-manager.reservation-recommendations'));
        var recommendationsUrl = @json(route((request()->is('external*') ? 'external' : 'admin').'.call-manager.recommendations'));
        var csrfToken = @json(csrf_token());

        // --- Utilidades ARI ---
        function esc(s){
            return $('<div>').text(s == null ? '' : String(s)).html();
        }

        function nl2brEsc(s){
            return esc(s).replace(/\n/g, '<br>');
        }

        function nowTime(){
            try{
                var d = new Date();
                return d.toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'});
            }catch(e){
                return '';
            }
        }

        function prettyBool(v){
            if (v === true) return 'Sí';
            if (v === false) return 'No';
            return v;
        }

        function prettyValue(v){
            if (v === null || v === undefined) return '';
            if (Array.isArray(v)) return v.filter(function(x){ return x !== null && x !== undefined && x !== ''; }).join(', ');
            if (typeof v === 'boolean') return prettyBool(v);
            return String(v);
        }

        function labelForKey(key){
            var map = {
                // basicInfo
                'apertura': 'Apertura',
                'cierre': 'Cierre',
                'acceso_fuera_de_horario': 'Acceso fuera de horario',
                'ubicacion_llaves': 'Ubicación llaves',
                // infoHotel
                'direccion': 'Dirección',
                'telefono': 'Teléfono',
                'email': 'Email',
                'descripcion': 'Descripción',
                'parking': 'Parking',
                'mascotas': 'Mascotas'
            };
            return map[key] || key;
        }

        function toHumanText(v){
            if (v == null) return '';
            if (typeof v === 'string') {
                if (v.indexOf('\\u') !== -1) {
                    try {
                        var decoded = JSON.parse('"' + v.replace(/"/g, '\\"') + '"');
                        return decoded;
                    } catch (e) {
                        return v;
                    }
                }
                return v;
            }
            if (typeof v === 'number' || typeof v === 'boolean') return String(v);
            try { return JSON.stringify(v, null, 2); } catch(e) { return String(v); }
        }

        // --- Chat Widget Logic ---
        function setChatStatus(text){
            $('#ari_chat_status').html('<span class="text-muted">'+esc(text || '')+'</span>');
        }

        function scrollChatToBottom(){
            var el = document.getElementById('ari_chat_messages');
            if (!el) return;
            el.scrollTop = el.scrollHeight;
        }

        function addChatMessage(kind, text){
            var $c = $('#ari_chat_messages');
            if (!$c.length) return;
            kind = kind || 'assistant';
            var cls = (kind === 'system') ? 'ari-system' : 'ari-assistant';
            var html = '';
            html += '<div class="ari-chat-msg '+cls+'">';
            html += '  <div class="ari-chat-bubble">'+nl2brEsc(text || '')+'<div class="ari-chat-meta">'+esc(nowTime())+'</div></div>';
            html += '</div>';
            $c.append(html);
            scrollChatToBottom();

            if (kind !== 'system' && !isChatOpen() && window.ariChat && typeof window.ariChat.open === 'function') {
                $('#ari_chat_widget').addClass('ari-attention');
                window.ariChat.open(true, false);
            }
        }

        function isChatOpen(){
            return $('#ari_chat_widget').hasClass('ari-open');
        }

        function setChatUnread(n){
            n = parseInt(n || 0, 10);
            if (isNaN(n) || n < 0) n = 0;
            var $b = $('#ari_chat_badge');
            if (!$b.length) return;
            if (n <= 0) {
                $b.hide().text('0');
            } else {
                $b.text(String(n)).show();
            }
            window.ariChatUnread = n;
        }

        function incChatUnread(){
            setChatUnread((window.ariChatUnread || 0) + 1);
        }

        window.ariChatUnread = 0;

        window.ariChat = {
            open: function(resetUnread, fromUser){
                $('#ari_chat_widget').addClass('ari-open');
                if (resetUnread) setChatUnread(0);
                if (fromUser) {
                    $('#ari_chat_widget').removeClass('ari-attention');
                }
                scrollChatToBottom();
            },
            close: function(){
                $('#ari_chat_widget').removeClass('ari-open');
            },
            clear: function(){
                $('#ari_chat_messages').empty();
                setChatStatus('Listo');
                setChatUnread(0);
                $('#ari_chat_widget').removeClass('ari-attention');
            },
            addSystem: function(text){
                addChatMessage('system', text);
            },
            addAssistant: function(text){
                addChatMessage('assistant', text);
            },
            pushHotelAdvice: function(advice){
                advice = (advice == null ? '' : String(advice)).trim();
                if (!advice) return;
                if (advice.indexOf('\\u') !== -1) {
                    try {
                        advice = JSON.parse('"' + advice.replace(/"/g, '\\"') + '"');
                    } catch (e) {}
                }
                $('#ari_chat_context').text('Recomendación inicial del hotel');
                var msg = '[Consejo profesional]\n' + advice;
                addChatMessage('assistant', msg);
                setChatStatus('Listo');
                if (!isChatOpen()) incChatUnread();
            },
            pushReservationRecommendations: function(data) {
                if (!data) return;

                function normalizeTypeLabel(t){
                    t = (t == null ? '' : String(t)).trim();
                    if (!t) return 'recomendación';
                    var pretty = t.replace(/_/g, ' ');
                    return pretty.charAt(0).toUpperCase() + pretty.slice(1);
                }

                setChatStatus('Listo');

                if (data && typeof data === 'object' && Array.isArray(data.recommendations)) {
                    var total = (typeof data.recommendations_sent === 'number') ? data.recommendations_sent : data.recommendations.length;
                    var st = data.status ? String(data.status) : '';
                    var header = 'Recomendaciones generadas' + (st ? (' ('+st+')') : '') + ': ' + total;
                    addChatMessage('system', header);

                    if (data.recommendations.length === 0) {
                        addChatMessage('assistant', 'No hay recomendaciones para esta reserva.');
                    } else {
                        for (var i = 0; i < data.recommendations.length; i++) {
                            var item = data.recommendations[i] || {};
                            var label = normalizeTypeLabel(item.type);
                            var body = toHumanText(item.recommendation);
                            var msg = '[' + label + ']\n' + (body || '—');
                            addChatMessage('assistant', msg);
                        }
                    }
                } else {
                    var text = '';
                    if (typeof data === 'string') {
                        text = data;
                    } else if (data && typeof data === 'object') {
                        if (typeof data.recommendation === 'string') {
                            text = data.recommendation;
                        } else if (typeof data.message === 'string') {
                            text = data.message;
                        } else if (typeof data.professionalAdvice === 'string') {
                            text = data.professionalAdvice;
                        } else {
                            text = toHumanText(data) || 'Recomendación recibida.';
                        }
                    }
                    addChatMessage('assistant', text);
                }

                if (!isChatOpen()) {
                    this.open(true);
                }
            },
            requestReservationRecommendations: function(folio, reservation){
                var rid = reservation && (reservation.id || reservation.reservationId || reservation.reservation_id);
                var key = String(rid || '');
                if (key && window.ariChatLastReservationKey === key && window.ariChatRequestInFlight) {
                    return;
                }
                window.ariChatLastReservationKey = key;

                var folioId = folio && (folio.id || folio.folioId || folio.folio_id);
                var folioName = folio && (folio.name || folio.folioName);
                var caption = 'Reserva seleccionada';
                if (folioId || folioName || rid) {
                    caption = 'Folio ' + (folioId ? ('#'+folioId) : '') + (folioName ? (' · '+folioName) : '') + (rid ? (' · Reserva ID '+rid) : '');
                }
                $('#ari_chat_context').text(caption);

                setChatStatus('Generando recomendaciones…');
                window.ariChatRequestInFlight = true;
                addChatMessage('system', 'Analizando la reserva y preparando sugerencias…');

                $.ajax({
                    url: reservationRecommendationsUrl,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    },
                    data: JSON.stringify({
                        folio: folio || null,
                        reservation: reservation || null
                    }),
                    cache: false
                }).done(function(resp){
                    window.ariChatRequestInFlight = false;
                    if (!resp || resp.ok !== true) {
                        setChatStatus('Error');
                        addChatMessage('system', (resp && resp.message) ? resp.message : 'No se pudo obtener la recomendación.');
                        if (!isChatOpen()) incChatUnread();
                        return;
                    }

                    this.pushReservationRecommendations(resp.data);

                }.bind(this)).fail(function(xhr){
                    window.ariChatRequestInFlight = false;
                    setChatStatus('Error');
                    var msg = 'No se pudo conectar con el asistente.';
                    try{
                        var r = JSON.parse(xhr.responseText);
                        if (r && r.message) msg = r.message;
                    }catch(e){}
                    addChatMessage('system', msg);
                    if (!isChatOpen()) incChatUnread();
                });
            }
        };

        // --- Info Card Logic ---
        function renderKeyValueTable(obj){
            if(!obj || typeof obj !== 'object') return '<div class="text-muted">Sin datos</div>';
            var rows = '';
            Object.keys(obj).forEach(function(k){
                var v = prettyValue(obj[k]);
                if (v === '') return;
                rows += '<tr><td>'+esc(labelForKey(k))+'</td><td>'+esc(v)+'</td></tr>';
            });
            if(!rows) return '<div class="text-muted">Sin datos</div>';
            return '<div class="table-responsive"><table class="table table-sm table-borderless mb-0 ari-kv"><tbody>'+rows+'</tbody></table></div>';
        }

        function renderARIList(items, columns){
            if(!Array.isArray(items) || items.length === 0) return '<div class="text-muted">Sin datos</div>';
            var thead = '<tr>';
            columns.forEach(function(c){ thead += '<th class="text-muted" style="font-weight:600">'+esc(c.label)+'</th>'; });
            thead += '</tr>';
            var tbody = '';
            items.forEach(function(it){
                tbody += '<tr>';
                columns.forEach(function(c){
                    var v = it && (it[c.key] !== undefined ? it[c.key] : '');
                    tbody += '<td>'+esc(prettyValue(v))+'</td>';
                });
                tbody += '</tr>';
            });
            return '<div class="table-responsive"><table class="table table-sm table-striped mb-0"><thead>'+thead+'</thead><tbody>'+tbody+'</tbody></table></div>';
        }

        function renderRecommendations(data){
            data = data || {};
            window.ariRecommendationsAdvice = data.professionalAdvice || '';

            var metaBadges = '';
            if (data.source) metaBadges += '<span class="badge badge-light mr-2">Fuente: '+esc(data.source)+'</span>';
            if (data.is_cached !== undefined) metaBadges += '<span class="badge badge-'+(data.is_cached ? 'success' : 'secondary')+' mr-2">'+(data.is_cached ? 'Cache' : 'No cache')+'</span>';
            if (data.hasRagInfo !== undefined) metaBadges += '<span class="badge badge-'+(data.hasRagInfo ? 'info' : 'secondary')+'">'+(data.hasRagInfo ? 'RAG' : 'Sin RAG')+'</span>';

            var html = '';
            if (metaBadges) {
                html += '<div class="mb-3">'+metaBadges+'</div>';
            }

            var apertura = data.basicInfo && data.basicInfo.apertura ? prettyValue(data.basicInfo.apertura) : '';
            var cierre = data.basicInfo && data.basicInfo.cierre ? prettyValue(data.basicInfo.cierre) : '';
            var horario = (apertura && cierre) ? (apertura + ' – ' + cierre) : '';
            if (data.hotelName || horario) {
                html += '<div class="d-flex align-items-center justify-content-between flex-wrap mb-3">';
                html += '  <div class="mr-2">';
                html += '    <div class="font-weight-bold" style="font-size:1.08rem">'+esc(data.hotelName || '')+'</div>';
                html += '  </div>';
                html += '  <div class="mt-2 mt-md-0">';
                if (horario) html += '<span class="badge badge-pill badge-info"><i class="fa fa-clock-o" aria-hidden="true"></i> ' + esc(horario) + '</span>';
                html += '  </div>';
                html += '</div>';
            }

            html += '<div class="row">';
            html += '  <div class="col-lg-6 mb-3">';
            html += '    <div class="ari-rec-card p-3 h-100">';
            html += '      <div class="ari-rec-card-title mb-2"><i class="fa fa-building-o text-primary" aria-hidden="true"></i> Datos del hotel</div>';
            html += '      '+renderKeyValueTable(data.infoHotel);
            html += '    </div>';
            html += '  </div>';

            html += '  <div class="col-lg-6 mb-3">';
            html += '    <div class="ari-rec-card p-3 h-100">';
            html += '      <div class="ari-rec-card-title mb-2"><i class="fa fa-info-circle text-success" aria-hidden="true"></i> Información clave</div>';
            html += '      '+renderKeyValueTable(data.basicInfo);
            html += '    </div>';
            html += '  </div>';
            html += '</div>';

            html += '<div class="row">';
            html += '  <div class="col-lg-6 mb-3">';
            html += '    <div class="ari-rec-card p-3 h-100">';
            html += '      <div class="ari-rec-card-title mb-2"><i class="fa fa-map-marker text-danger" aria-hidden="true"></i> Ubicaciones</div>';
            html +=        renderARIList(data.ubicaciones, [
                                {key:'nombre', label:'Nombre'},
                                {key:'tipo', label:'Tipo'},
                                {key:'piso', label:'Piso'},
                                {key:'zona_comun', label:'Zona común'}
                            ]);
            html += '    </div>';
            html += '  </div>';
            html += '  <div class="col-lg-6 mb-3">';
            html += '    <div class="ari-rec-card p-3 h-100">';
            html += '      <div class="ari-rec-card-title mb-2"><i class="fa fa-shopping-bag text-warning" aria-hidden="true"></i> Servicios</div>';
            html +=        renderARIList(data.servicios, [
                                {key:'nombre', label:'Nombre'},
                                {key:'precio', label:'Precio'},
                                {key:'tipo', label:'Tipo'},
                                {key:'por_persona', label:'Por persona'},
                                {key:'por_dia', label:'Por día'}
                            ]);
            html += '    </div>';
            html += '  </div>';
            html += '</div>';

            return html;
        }

        function setRecommendationsSubtitleDefault(){
            $('#ari_recommendations_subtitle').text('').addClass('d-none');
        }

        function setARIInfoLoading(){
            $('#ari_recommendations_content').html('<div class="text-muted">Cargando recomendaciones...</div>');
        }
        function setARIInfoError(msg){
            $('#ari_recommendations_content').html('<div class="alert alert-danger mb-0">'+esc(msg || 'Error al cargar recomendaciones')+'</div>');
        }

        function showARIInfo(){
            $('#ari_recommendations_content').show();
            setRecommendationsSubtitleDefault();
            var $btn = $('#ari_recommendations_toggle_btn');
            if ($btn.length) {
                $btn.removeClass('btn-success btn-danger').addClass('btn-danger');
                $btn.html('<i class="fa fa-eye-slash" aria-hidden="true"></i> Ocultar');
            }
        }
        function hideARIInfo(){
            $('#ari_recommendations_content').hide();
            setRecommendationsSubtitleDefault();
            var $btn = $('#ari_recommendations_toggle_btn');
            if ($btn.length) {
                $btn.removeClass('btn-success btn-danger').addClass('btn-success');
                $btn.html('<i class="fa fa-eye" aria-hidden="true"></i> Mostrar Información del Hotel');
            }
        }

        window.callManagerToggleRecommendations = function(){
            if (!$('#ari_recommendations_content').length) return;
            if ($('#ari_recommendations_content').is(':visible')) {
                hideARIInfo();
            } else {
                showARIInfo();
            }
        };

        window.callManagerLoadRecommendations = function(options){
            options = options || {};
            var wasVisible = $('#ari_recommendations_content').is(':visible');
            setARIInfoLoading();
            $.ajax({
                url: recommendationsUrl,
                method: 'POST',
                data: { _token: csrfToken },
                cache: false,
                success: function(resp){
                    if (!resp || resp.ok !== true) {
                        setARIInfoError(resp && resp.message ? resp.message : 'Respuesta inválida del servidor');
                        return;
                    }
                    $('#ari_recommendations_content').html(renderRecommendations(resp.data));

                    if (!options.skipHotelAdvice) {
                        try {
                            var advice = (window.ariRecommendationsAdvice || '').toString().trim();
                            if (advice && window.ariChat && typeof window.ariChat.pushHotelAdvice === 'function') {
                                if (!window.ariChatHotelAdviceSent) {
                                    window.ariChat.pushHotelAdvice(advice);
                                    window.ariChatHotelAdviceSent = true;
                                }
                            }
                        } catch (e) { console.warn('[ARI] No se pudo enviar el consejo al chat', e); }
                    }

                    // En la página de reservas, queremos mostrar recomendaciones de cross-selling y wiki
                    // pero ARI las devuelve en el endpoint de reserva.
                    // Sin embargo, el endpoint inicial ya devuelve datos que podemos procesar.
                    if (options.includeExtraRecommendations) {
                         if (window.ariChat && typeof window.ariChat.pushReservationRecommendations === 'function') {
                             if (resp.data && resp.data.recommendations) {
                                 window.ariChat.pushReservationRecommendations(resp.data);
                             } else if (resp.data) {
                                 // Si no hay array de recomendaciones pero sí hay datos, intentamos construir una recomendación manual
                                 // basada en los servicios y wiki disponibles en la ficha.
                                 var manualRecs = [];
                                 if (resp.data.servicios && resp.data.servicios.length > 0) {
                                     var servText = "Servicios disponibles:\n" + resp.data.servicios.map(function(s){
                                         return "- " + s.nombre + ": " + s.precio + "€";
                                     }).join("\n");
                                     manualRecs.push({ type: 'cross_selling', recommendation: servText });
                                 }
                                 if (resp.data.basicInfo) {
                                     var b = resp.data.basicInfo;
                                     var wikiText = "Información de recepción:\n" +
                                                    "Apertura: " + (b.apertura || '—') + " | Cierre: " + (b.cierre || '—') + "\n" +
                                                    "Acceso: " + (b.acceso_fuera_de_horario || '—') + "\n" +
                                                    "Ubicación llaves: " + (b.ubicacion_llaves || '—');
                                     manualRecs.push({ type: 'wiki_recepcion', recommendation: wikiText });
                                 }

                                 if (manualRecs.length > 0) {
                                     window.ariChat.pushReservationRecommendations({
                                         status: 'extracted',
                                         recommendations_sent: manualRecs.length,
                                         recommendations: manualRecs
                                     });
                                 }
                             }
                         }
                    }

                    if (!wasVisible) hideARIInfo();
                    if (options.onSuccess) options.onSuccess(resp.data);
                },
                error: function(xhr){
                    var msg = 'Error al cargar recomendaciones.';
                    try { if (xhr && xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message; } catch (e) {}
                    setARIInfoError(msg);
                }
            });
        };

        // --- Interaction Logic ---
        $(function(){
            setChatStatus('Listo');
            setChatUnread(0);
            try {
                $('#ari_chat_widget').on('mousedown touchstart wheel keydown', '.ari-chat-panel, .ari-chat-header, .ari-chat-body, .ari-chat-footer', function(){
                    $('#ari_chat_widget').removeClass('ari-attention');
                });
            } catch (e) {}
        });
    })();
</script>
