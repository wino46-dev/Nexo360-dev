<script>
    
    var next_click=document.querySelectorAll(".next_button");
    var main_form=document.querySelectorAll(".main");
    var step_list = document.querySelectorAll(".progress-bar li");
    var num = document.querySelector(".step-number");
    let formnumber=0;
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const step = urlParams.get('step')

    $("#modalPush").prependTo("body");
    $("#modalImagenes").prependTo("body");
    $("#modalDocumentos").prependTo("body");
    $("#modalPagoReserva").prependTo("body");
    $("#modalGrabacionTarjeta").prependTo("body");
    $("#modalParteViajeros").prependTo("body");



    if(parseInt(step) === 1){

        $('.progress-bar>li').removeClass('active');
        $('#pushli').addClass('active');
        $('.right-side>div').removeClass('active');
        $('#divpushli').addClass('active');
        formnumber=1;

    }else if(parseInt(step) === 2){

        $('.progress-bar>li').removeClass('active');
        $('#pushli').addClass('active');
        $('#imagesli').addClass('active');
        $('.right-side>div').removeClass('active');
        $('#divimagesli').addClass('active');
        formnumber=2;
    }else if(parseInt(step) === 3){

        $('.progress-bar>li').removeClass('active');
        $('#pushli').addClass('active');
        $('#imagesli').addClass('active');
        $('#capturasli').addClass('active');
        $('.right-side>div').removeClass('active');
        $('#divcapturasli').addClass('active');
        formnumber=3;

    }else if(parseInt(step) === 4){

        $('.progress-bar>li').removeClass('active');
        $('#pushli').addClass('active');
        $('#imagesli').addClass('active');
        $('#capturasli').addClass('active');
        $('#pagosli').addClass('active');
        $('.right-side>div').removeClass('active');
        $('#divpagosli').addClass('active');
        formnumber=4;

    }else if(parseInt(step) === 5){

        $('.progress-bar>li').removeClass('active');
        $('#pushli').addClass('active');
        $('#imagesli').addClass('active');
        $('#capturasli').addClass('active');
        $('#pagosli').addClass('active');
        $('#cardsli').addClass('active');
        $('.right-side>div').removeClass('active');
        $('#divcardsli').addClass('active');
        formnumber=5;

    }else if(parseInt(step) === 6){

        $('.progress-bar>li').removeClass('active');
        $('#pushli').addClass('active');
        $('#imagesli').addClass('active');
        $('#capturasli').addClass('active');
        $('#pagosli').addClass('active');
        $('#cardsli').addClass('active');
        $('#signsli').addClass('active');
        $('.right-side>div').removeClass('active');
        $('#divsingsli').addClass('active');
        formnumber=6;

    }

    next_click.forEach(function(next_click_form){
        next_click_form.addEventListener('click',function(){
            if(!validateform()){
                return false
            }
            formnumber++;
            updateform();
            progress_forward();
            contentchange();
        });
    });

    var back_click=document.querySelectorAll(".back_button");
    back_click.forEach(function(back_click_form){
        back_click_form.addEventListener('click',function(){
            formnumber--;
            updateform();
            progress_backward();
            contentchange();
        });
    });

    $('#pushli').click(function(e) {
        e.preventDefault();
        $('.progress-bar>li').removeClass('active');
        $('#pushli').addClass('active');
        $('.right-side>div').removeClass('active');
        $('#divpushli').addClass('active');
        formnumber=1;


    });

    $('#imagesli').click(function(e) {
        e.preventDefault();
        $('.progress-bar>li').removeClass('active');
        $('#pushli').addClass('active');
        $('#imagesli').addClass('active');
        $('.right-side>div').removeClass('active');
        $('#divimagesli').addClass('active');
        formnumber=2;

    });

    $('#capturasli').click(function(e) {
        e.preventDefault();
        $('.progress-bar>li').removeClass('active');
        $('#pushli').addClass('active');
        $('#imagesli').addClass('active');
        $('#capturasli').addClass('active');
        $('.right-side>div').removeClass('active');
        $('#divcapturasli').addClass('active');
        formnumber=3;

    });

    $('#pagosli').click(function(e) {
        e.preventDefault();
        $('.progress-bar>li').removeClass('active');
        $('#pushli').addClass('active');
        $('#imagesli').addClass('active');
        $('#capturasli').addClass('active');
        $('#pagosli').addClass('active');
        $('.right-side>div').removeClass('active');
        $('#divpagosli').addClass('active');
        formnumber=4;

    });

    $('#cardsli').click(function(e) {
        e.preventDefault();
        $('.progress-bar>li').removeClass('active');
        $('#pushli').addClass('active');
        $('#imagesli').addClass('active');
        $('#capturasli').addClass('active');
        $('#pagosli').addClass('active');
        $('#cardsli').addClass('active');
        $('.right-side>div').removeClass('active');
        $('#divcardsli').addClass('active');
        formnumber=5;

    });

    $('#signsli').click(function(e) {
        e.preventDefault();
        $('.progress-bar>li').removeClass('active');
        $('#pushli').addClass('active');
        $('#imagesli').addClass('active');
        $('#capturasli').addClass('active');
        $('#pagosli').addClass('active');
        $('#cardsli').addClass('active');
        $('#signsli').addClass('active');
        $('.right-side>div').removeClass('active');
        $('#divsingsli').addClass('active');
        formnumber=6;

    });


    var username=document.querySelector("#user_name");
    var shownname=document.querySelector(".shown_name");


    var submit_click=document.querySelectorAll(".submit_button");
    submit_click.forEach(function(submit_click_form){
        submit_click_form.addEventListener('click',function(){
            shownname.innerHTML= username.value;
            formnumber++;
            updateform();
        });
    });





    function updateform(){
        main_form.forEach(function(mainform_number){
            mainform_number.classList.remove('active');
        })
        main_form[formnumber].classList.add('active');
    }

    function progress_forward(){
        // step_list.forEach(list => {

        //     list.classList.remove('active');

        // });


        num.innerHTML = formnumber+1;
        step_list[formnumber].classList.add('active');
    }

    function progress_backward(){
        if(formnumber <= 5){
            var form_num = formnumber+1;
        }else{
            var form_num = formnumber+1;
        }

        step_list[form_num].classList.remove('active');
        num.innerHTML = form_num;
    }

    var step_num_content=document.querySelectorAll(".step-number-content");

    function contentchange(){
        step_num_content.forEach(function(content){
            content.classList.remove('active');
            content.classList.add('d-none');
        });

    }


    function validateform(){
        validate=true;
        var validate_inputs=document.querySelectorAll(".main.active input");
        validate_inputs.forEach(function(vaildate_input){
            vaildate_input.classList.remove('warning');
            if(vaildate_input.hasAttribute('require')){
                if(vaildate_input.value.length==0){
                    validate=false;
                    vaildate_input.classList.add('warning');
                }
            }
        });
        return validate;

    }

    
    
</script>

