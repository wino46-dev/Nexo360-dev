<!-- <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script> -->
<br />
<div id="alice-onboarding-mount" >CARGANDO</div>
<br />
<script>
    
    var alice = null;
    var documento_id = null;
    var onboardingCommands = null
    $('#alice-onboarding-mount').html('<br />' + loading1);
    
    $(document).ready(function(){
        startOnboarding();
    });

    async function startOnboarding(pusher_capture) {
      
      let config = new aliceonboarding.OnboardingConfig().withCustomLocalization({
        language: "{{ $data['language'] }}"
      });
      
      let userToken = "{{ $data['user_token'] }}";

      aliceonboarding.OnboardingCommands.setEnvironment("sandbox")
      console.log(config);

      onboardingCommands = await aliceonboarding.OnboardingCommands.makeOnboardingCommands({
        idSelector: "alice-onboarding-mount",
        userToken: userToken,
        config: config
      });

      addDocument(onboardingCommands, '{{ $data['document_id'] }}', '{{ $data['side'] }}')

      /*onboardingCommands.getDocumentsSupported({
        onSuccess: (suportedDocuments) => {
          // onSuccess
          console.log(suportedDocuments);
        },
        onError: (error) => {
          //onError
          console.error(error);
        }
      });
      console.log(onboardingCommands);
      console.log(aliceonboarding.DocumentType);
      
      onboardingCommands.createDocument({
        documentType: aliceonboarding.DocumentType.{{ $data['document_type'] }},
        issuingCountry: "{{ $data['country'] }}",
        onSuccess: (result) => {
          console.log(result.document_id); // returns the document id of the created document
          addDocument(onboardingCommands, result.document_id, '{{ $data['side'] }}')
          documento_id = result.document_id;
        },
        onError: (error) => {
          console.error(error);
        }
      });
      */
      
    }

    function capturar(side){
      //alert(tipo + ' ' + documento_id)
      console.log(onboardingCommands)
      addDocument(onboardingCommands, documento_id, side)
    }

    function addDocument(onboardingCommands, document_id, side) {
      let alice_data = @json($data);
      
      onboardingCommands.addDocument({
        onSuccess: (result) => {
          console.log('onSuccess', document_id, side);
          Toast.fire({
              icon: "success",
              title: 'Enviado correctamente'
          });
          alice_event_get_document_success(side, alice_data);
          $('#alice_modal').modal('hide');
          console.log(result);
        },
        onError: (error) => {
          console.log('onError', document_id, side);
          console.error(error);
        },
        onCancel: (cancel) => {
          console.log('onCancel', document_id, side);
          console.error(cancel);
        },
        documentId: document_id, //with the document_id of the previously created document, look at the previous command.
        documentType: aliceonboarding.DocumentType.{{ strtoupper($data['document_type']) }},
        issuingCountry: "{{ $data['country'] }}",
        documentSide: side //(front or back)
      });
    }


    

  </script>