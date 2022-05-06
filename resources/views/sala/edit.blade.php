@extends('main')
@section('content')
    <form method="POST" action="/salas/{{ $sala->id }}">
        @csrf
        @method('patch')
        @include('sala.partials.form', ['title' => "Editar"])
    </form>
@endsection

@section('javascripts_bottom')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('.categorias_select').select2();
        });
    </script>
@stop
