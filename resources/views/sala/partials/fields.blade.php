<table class="table table-borderless">
    <div class="table-responsive">
        <tr>
            <th>Categoria</th>
            <th>Capacidade</th>
            <th>Recursos</th>
        </tr>
        <tr>
            <td> {{ $sala->categoria->nome }} </td>
            <td>{{  $sala->capacidade ?? ''  }}</td>
            <td>
              @foreach($sala->recursos as $recurso)
                <p>{{ $recurso->nome }}</p>
              @endforeach
            </td>
        </tr>
    </div>
</table>
</br>
@canany(['admin','boss'])
    <form action="/salas/{{  $sala->id  }}" method="POST">
        <a class="btn btn-success" href="/salas/{{  $sala->id  }}/edit" role="button" data-bs-toggle="tooltip" title="Editar">
            <i class="fa fa-pen"></i>
        </a>
        @csrf
        @method('delete')
        <button class="btn btn-danger" type="submit" name="tipo" value="one" data-bs-toggle="tooltip" title="Excluir" onclick="return confirm('Tem certeza?');">
            <i class="fa fa-trash" ></i>
        </button>
    </form>
@endcan
