<script>
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-center",
        "timeOut": "5000"
    };
</script>

@if (session('success'))
    <script>
        toastr.success(@json(session('success')));
    </script>
@endif

@if (session('error'))
    <script>
        toastr.error(@json(session('error')));
    </script>
@endif

@if ($errors->any())
    @foreach ($errors->all() as $error)
        <script>
            toastr.error(@json($error));
        </script>
    @endforeach
@endif
