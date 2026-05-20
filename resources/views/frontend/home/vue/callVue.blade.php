
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.2/axios.min.js"></script>
<script src="https://unpkg.com/vuex@4.0.0/dist/vuex.global.js"></script>

<script src="https://webrtchacks.github.io/adapter/adapter-latest.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/howler/2.2.4/howler.min.js"></script>
<script src="/js/janus.js"></script>


<div id="call_conte">    
    <neo-call ref="childComponentRef"></neo-call>
</div>
<script>    
    Vue.config.delimiters = ['@{{', '}}'];
</script>

@include('frontend.home.vue.PhoneCommander')
@include('frontend.home.vue.Phone')

<script>
    // Acceder a la cámara web

    var miVueApp = new Vue({
        el: '#call_conte', // Monta la instancia en el elemento con el ID 'app'
        data: {
            
        },
        mounted() {
            
        },
        methods: {            
            llamada_iniciar() {
                this.$refs.childComponentRef.llamar();
            },
            llamada_inversa_iniciar(sipIdentityDestino) {
                this.$refs.childComponentRef.llamar_inversa(sipIdentityDestino);
            },
            llamada_colgar(){
                this.$refs.childComponentRef.colgar();
            }
        }
    });

</script>


<style>
    .conte_video{
        
        width: 100%;
        height: 500px;
        z-index:5000;
        background-color: white;
    }
    .video {
        top: 0;
        position: absolute;
        right: 0;
        left: 0;
        bottom: 1px;        
        object-fit: cover;
        width:100%;
        height:100%;
        
    }

    .conte_video_btn{
        position: absolute;
        bottom: 10px;
        right: 0px;
        left: 0;        
    }
    .video_local_class{
        position:absolute;
        top:20px;
        right:20px;        
        width:150px;

    }

</style>

