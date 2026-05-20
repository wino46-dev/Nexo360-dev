<script>
    window.instanciaPhone = "";
    window.registro = "";
    window.llamadaEstado = "";
    //
    window.config = {
        webrtcServer: "wss://gateway.norvoz.es:8989",
    }//

    Vue.component('neo-call', {
        template: `<div style="position:relative">
                <div class="conte_video" style="position:relative" >
                    <video ref="etiquetaVideo" class="video" autoplay playsinline></video>
                    <div class="conte_video_btn">
                        <div class="container">
                            <div class="row">
                                <div class="col col-12 text-center" >
                                    <button type="button" class="btn btn-danger" v-if="!verBotonLlamar" @click="colgar()">
                                        Colgar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <video id="video_local"  class="video_local_class border" autoplay></video>

                </div>
                <!--<div class="page3_callname text-center mt-4 mb-4">
                    Lo atiende: <b> Pedro Martí</b>
                </div> -->



                <!--
                <div class="container" style="text-align: center" :style="{ visibility: verBotonLlamar ? 'visible' : 'hidden' }">

                    <div id="header" >
                        <div id="boton_llamada" style="cursor:pointer">
                            <img @click="llamar()" src="/img/Boton Assist.gif" alt="boton llamar" style="width: 50px !important" />
                        </div>

                    </div>


                </div> -->
                <audio ref="etiquetaAudio" autoplay />
            </div>`,
        data() {
            return {

                snackbar: false,
                snack_color: "",
                snack_text: "",
                myInstance: "",
                verBotonLlamar: true,
                verBotonColgarEnLlamada: false,
                verTeclado: false,
                verEtiquetaVideo: false,
                sipIdentity: "",
                displayName: "",
                sipRegistrar: "",
                token:"",
                username: "",
                password: "",
                sipIdentityDestino: "",
            };
        },

        methods: {
            llamar() {
                this.verEtiquetaVideo = false;
                //this.$store.getters.getinstanciaPhone.doCall(this.sipIdentityDestino);
                console.log(window.instanciaPhone);
                window.instanciaPhone.doCall(this.sipIdentityDestino, true)
                this.verBotonLlamar = false;
            },
            llamar_inversa(sipIdentityDestino) {

                this.verEtiquetaVideo = false;

                window.instanciaPhone.doCall('sip:' + sipIdentityDestino, true)
                this.verBotonLlamar = false;
            },
            colgar() {
                //this.$store.getters.getinstanciaPhone.doHangup();
                window.instanciaPhone.doHangup()
                this.verBotonLlamar = true;
            },

            loginProvision(){
                //let $this = this;
                /*const datos_usuario = {
                    email: 'norvoz@config.com',
                    password: 'asklfh@e!oifreczxc321424.sh',
                };
                const goLogin = async () => {
                    let config = {
                        headers: {
                            Accept: "application/json",
                        },
                    };
                    try {

                        return await axios.post(
                            this.urlLoginProvision,
                            datos_usuario,
                            config
                        );
                    } catch (error) {
                        console.log("Se ha producido un error al loguearse en el sistema de provisión");
                        console.log(error);
                    }
                };
                const processgoLoginResult = async () => {
                    const login_result = await goLogin();
                    if (login_result) {
                        this.token=login_result.data.access_token
                        this.datosProvision()
                    }
                };
                processgoLoginResult();*/
                this.datosProvision();
            },
            datosProvision() {

                this.sipIdentity = "sip:{{ $caller->sip_identity }}";
                this.displayName = "{{ $caller->display_name }}";
                this.sipRegistrar = "sip:{{ $caller->sip_registar }}";
                this.username = "{{ $caller->username }}";
                this.password = "{{ $caller->password }}";
                this.sipIdentityDestino = "sip:{{ $caller->sip_identity_destino }}";
                this.registrar();

               // procesarDatosProvision();
            },
            registrar() {
                if (!window.registro) {
                    const debug = true;
                    const videoTagRef = this.$refs.etiquetaVideo;
                    const audioTagRef = this.$refs.etiquetaAudio;
                    const sipPluginConfig = {
                        sipIdentity: this.sipIdentity,
                        displayName: this.displayName,
                        sipRegistrar: this.sipRegistrar,
                        username: this.username,
                        secret: this.password,
                    };

                    this.myInstance = new PhoneCommander(
                        sipPluginConfig,
                        debug,
                        videoTagRef,
                        audioTagRef
                    );
                    this.myInstance.connect();
                    window.instanciaPhone = this.myInstance
                    //this.$store.commit("SET_INSTANCIAPHONE", this.myInstance);
                }
            },
            estadoLlamada() {
                let estadoLLamada = window.llamadaEstado;
                if (estadoLLamada == "accepted") {
                    this.verBotonLlamar = false;
                    this.verBotonColgarEnLlamada = true;
                    this.verEtiquetaVideo = true;
                }
                if (estadoLLamada == "hangup") {
                    this.verBotonLlamar = true;
                    this.verBotonColgarEnLlamada = false;
                }
            },
        },
        mounted() {
            this.loginProvision();
        },
    });


</script>
