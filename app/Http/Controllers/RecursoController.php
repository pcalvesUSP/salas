<?php

namespace App\Http\Controllers;

use App\Http\Requests\RecursoRequest;
use App\Models\Recurso;

class RecursoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('boss');
        $recursos = Recurso::all();

        return view('recurso.index', [
            'recursos' => $recursos,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(RecursoRequest $request)
    {
        $this->authorize('boss');
        $recurso = Recurso::create($request->validated());

        return redirect('/recursos')
            ->with('alert-sucess', 'Recurso criado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Recurso $recurso)
    {
        $this->authorize('boss');
        $recurso->delete();

        return redirect('/recursos')
            ->with('alert-sucess', 'Recurso excluído com sucesso.');
    }
}
