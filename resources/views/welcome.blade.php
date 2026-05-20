@if(auth()->user()->internalUser())
    <script type="text/javascript">
        window.location = "{{ url('/admin') }}  ";//here double curly bracket
    </script>
@else
    <script type="text/javascript">
        window.location = "{{ url('/home') }}  ";//here double curly bracket
    </script>
@endif
<!DOCTYPE html>

