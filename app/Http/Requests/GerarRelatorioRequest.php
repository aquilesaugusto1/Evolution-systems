<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GerarRelatorioRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
            'colaborador_id' => 'nullable|exists:usuarios,id',
            'contrato_id' => 'nullable|exists:contratos,id',
            'empresa_id' => 'nullable|exists:empresas_parceiras,id',
            'status' => 'nullable|string|in:Pendente,Aprovado,Reprovado',
            'formato' => 'required|in:html,pdf,excel',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'data_inicio.required' => 'O campo data de início é obrigatório.',
            'data_fim.required' => 'O campo data de fim é obrigatório.',
            'data_fim.after_or_equal' => 'A data final deve ser igual ou posterior à data inicial.',
            'formato.required' => 'O formato do relatório deve ser selecionado.',
            'colaborador_id.exists' => 'O colaborador selecionado é inválido.',
        ];
    }
}
