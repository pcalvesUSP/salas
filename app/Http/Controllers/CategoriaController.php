<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoriaRequest;
use App\Models\Categoria;
use App\Models\User;
use Illuminate\Http\Request;
use Uspdev\Replicado\Pessoa;
use App\Utils\ReplicadoUtils;

class CategoriaController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('boss');

        return view('categoria.create', [
            'categoria' => new Categoria(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CategoriaRequest $request)
    {
        $this->authorize('boss');
        $categoria = Categoria::create($request->validated());

        return redirect("/categorias/{$categoria->id}")
            ->with('alert-sucess', 'Categoria criada com sucesso.');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Categoria $categoria)
    {
        $sigla_unidade = ReplicadoUtils::dumpUnidade(config('salas.codUnidade'), ['sglund']);

        return view('categoria.show', [
            'categoria' => $categoria,
            'sigla_unidade' => $sigla_unidade[0]['sglund']
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Categoria $categoria)
    {
        $this->authorize('boss');
        
        return view('categoria.edit', [
            'categoria' => $categoria,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(CategoriaRequest $request, Categoria $categoria)
    {
        $this->authorize('boss');

        $categoria->update($request->validated());

        return redirect("/categorias/{$categoria->id}")
            ->with('alert-sucess', 'Categoria atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Categoria $categoria)
    {
        $this->authorize('boss');

        if($categoria->salas->isNotEmpty()){
            return redirect("/categorias/{$categoria->id}")
                ->with('alert-danger', 'Não é possível deletar essa categoria pois ela contém salas');   
        }

        $categoria->delete();

        return redirect('/')
            ->with('alert-sucess', 'Categoria excluída com sucesso.');
    }

    public function addUser(Request $request, Categoria $categoria)
    {
        $this->authorize('boss');

        $request->validate([
            'codpes' => 'required|integer',
        ],
        [
            'codpes.required' => 'Entre com o número USP.',
            'codpes.integer' => 'O número USP precisa ser inteiro.',
        ]);

        // é um número USP válido?
        $pessoa = Pessoa::dump($request->codpes);

        if (!$pessoa) {
            return redirect("/categorias/{$categoria->id}")
                ->with('alert-danger', 'Número USP inválido');
        }

        if(count(User::where('codpes', $pessoa['codpes'])->get()) == 0)
        {
            $user = User::findOrCreateFromReplicado($pessoa['codpes']);
            if (!($user instanceof \App\Models\User)) {
                return redirect()->back()->withErrors(['codpes' => $user]);
            }
        } else{
            $user = User::firstWhere('codpes', $pessoa['codpes']);
        }

        // não pode existir na tabela categoria_users uma instância
        // com o user_id e a categoria_id solicitados.
        if (!$categoria->users->contains($user)) {
            $categoria->users()->attach($user);
            request()->session()->flash('alert-success', "{$user->name} cadastrado(a) em {$categoria->nome}");
        } else {
            request()->session()->flash('alert-warning', "{$user->name} já está cadastrado(a) em {$categoria->nome}");
        }

        return redirect("/categorias/{$categoria->id}");
    }

    public function alterarVinculos(Request $request, Categoria $categoria){

        $categoria->vinculos = $request->input('vinculo');
        $categoria->save();

        request()->session()->flash('alert-success', "Vínculos cadastrados em {$categoria->nome} alterados.");
        return redirect()->route('categorias.show', ['categoria' => $categoria->id]);
    }

    public function removeUser(Request $request, Categoria $categoria, User $user)
    {
        $this->authorize('boss');

        $categoria->users()->detach($user->id);

        return redirect("/categorias/{$categoria->id}")
            ->with('alert-sucess', "{$user->name} foi excluído(a) de {$categoria->nome}");
    }
}
