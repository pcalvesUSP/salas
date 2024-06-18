@if ($reserva->parent_id != null and ($reserva->parent_id != $reserva->id))
    <div class="alert alert-danger" role="alert">
        Atenção: Esta reserva faz parte de um grupo e você está editando somente esta instância!<br><br>
        Se deseja editar todas reservas do grupo simultaneamente
        <a href="/reservas/{{ $reserva->parent_id }}/edit">clique aqui</a>
    </div>
@endif

@if ($reserva->parent_id != null and ($reserva->parent_id == $reserva->id))
    <div class="alert alert-danger" role="alert">
        Atenção: Você está editando um grupo de reservas simultaneamente!
    </div>
@endif

<div class="card">
    <div class="card-header">
        <b>{{ $title }}</b>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm form-group">
                <label for="" class="required"><b>Título</b></label>
                <br>
                <input type="text" class="form-control"  name="nome" value="{{  old('nome', $reserva->nome) }}">
            </div>
            <div class="col-sm form-group">
                <label for="" class="required"><b>Finalidade</b></label>
                <br>
                <select name="finalidade_id" class="form-control form-select" required>
                    @foreach ($finalidades as $finalidade)
                        <option value="{{$finalidade->id}}">{{$finalidade->legenda}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        @include('reserva.partials.datetime-fields')

        <div class="row">
            <div class="col-sm form-group">
                <label for="" class="required"><b>Descrição (informe telefone e e-mail de contato)</b></label>
                <br>
                <textarea name="descricao" class="form-control" rows="3">{{  old('descricao', $reserva->descricao) }}</textarea>
                <br>
            </div>
        </div>
        <button id="b-reservas" type="submit" class="btn btn-success">Enviar</button>
    </div>
</div>
