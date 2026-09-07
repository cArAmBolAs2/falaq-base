<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Pergunta;
use App\Http\Requests\StorePerguntaRequest;

class EventoController extends Controller
{
    public function index()
    {
        $eventos = Evento::withCount('perguntas')->latest()->paginate(12);

        return view('eventos.index', compact('eventos'));
    }

    public function show($id)
    {
        $evento = Evento::findOrFail($id);

        $perguntas = $evento->perguntas()
            ->latest()
            ->paginate(10);

        return view('eventos.show', compact('evento', 'perguntas'));
    }

    public function storePergunta(StorePerguntaRequest $request, $id)
    {
        $dados = $request->validated();

        Pergunta::create([
            'evento_id' => $dados['evento_id'],
            'texto'     => $dados['texto'],
            'status'    => 'pendente',
        ]);

        return redirect()->route('eventos.show', $dados['evento_id'])
            ->with('sucesso', 'Sua pergunta foi enviada com sucesso!');
    }
}
