<object data="{{ url($url_pdf) }}" type="application/pdf"
    width="100%" height="700px">
    <param name="src" value="{{ url($url_pdf) }}">
    
    <param name="type" value="application/pdf">
    <p>El navegador no puede mostrar este PDF. Puedes <a
            href="{{ url($url_pdf) }}">descargarlo aquí</a>.</p>
</object>
