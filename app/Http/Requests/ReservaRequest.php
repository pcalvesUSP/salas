<?php

namespace App\Http\Requests;

use App\Models\Sala;
use App\Rules\verifyRoomAvailability;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReservaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        /*
         * A validação da disponibilidade será customizada
         */
        if ($this->method() == 'PATCH' || $this->method() == 'PUT') {
            $id = $this->reserva->id;
        } else {
            $id = 0;
        }

        $rules = [
            'nome' => 'required',
            'horario_inicio' => 'required|date_format:G:i|',
            'horario_fim' => 'required|date_format:G:i|after:horario_inicio|',
            'finalidade_id' => 'required|integer',
            'sala_id' => ['required', Rule::in(Sala::pluck('id')->toArray())],
            'descricao' => 'nullable',
            'repeat_until' => ['required_with:repeat_days', 'nullable', 'date_format:d/m/Y'],
            'repeat_days.*' => 'integer|between:0,7',
            'data' => ['required', 'date_format:d/m/Y', new verifyRoomAvailability($this, $id)],
        ];

        if(!Gate::allows('responsavel', Sala::find($this->sala_id))){
            array_push($rules['data'], 'after_or_equal:today');
            //$rules['horario_inicio'] .= 'after:'. date('d/m/Y G:i');
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'nome.required' => 'O título não pode ficar em branco.',
            'data.required' => 'A data não pode ficar em branco.',
            'horario_inicio.required' => 'O horário de início não pode ficar em branco.',
            'horario_fim.required' => 'O horário de fim não pode ficar em branco.',
            'horario_inicio.date_format' => 'Digite o horário no formato 0:00. Exemplo: 9:00',
            'horario_fim.date_format' => 'Digite o horário no formato 0:00. Exemplo: 9:00',
            'sala_id.required' => 'Selecione uma sala.',
            'repeat_until.required_with' => 'Selecione uma data para o fim da repetição.',
            'data.after_or_equal' => 'Não é possível fazer reservas em dias passados.',
            'horario_inicio.after' => 'Não é possível fazer reservas em um horário passado.',
        ];
    }
}
