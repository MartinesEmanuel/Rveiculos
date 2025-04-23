<?php
namespace App\Http\Controllers;

use App\Models\Veiculo;
use Illuminate\Http\Request;

class VeiculoController extends Controller
{
    // Listar todos os veículos
    public function index()
    {
        // Retorna todos os veículos com os dados básicos do proprietário (nome e ID)
        return Veiculo::with('pessoa:id,nome')->get();
    }

    // Criar um novo veículo
    public function store(Request $request)
    {
        // Valida os dados recebidos na requisição
        $validated = $request->validate([
            'pessoa_id' => 'required|exists:pessoas,id', // O ID do proprietário é obrigatório e deve existir na tabela pessoas
            'placa' => 'required|string|unique:veiculos,placa', // A placa é obrigatória, deve ser única e uma string
            'marca' => 'required|string', // A marca é obrigatória e deve ser uma string
            'modelo' => 'required|string', // O modelo é obrigatório e deve ser uma string
            'ano_fabricacao' => 'required|integer', // O ano de fabricação é obrigatório e deve ser um número inteiro
            'cor' => 'nullable|string', // A cor é opcional e deve ser uma string
            'proxima_revisao' => 'nullable|date', // A data da próxima revisão é opcional e deve ser uma data válida
        ]);

        // Cria o veículo no banco de dados com os dados validados
        return Veiculo::create($request->all());
    }

    // Mostrar um veículo específico
    public function show(Veiculo $veiculo)
    {
        // Retorna os dados do veículo com as informações do proprietário relacionadas
        return $veiculo->load('pessoa');
    }

    // Atualizar um veículo
    public function update(Request $request, Veiculo $veiculo)
    {
        // Valida os dados recebidos na requisição
        $validated = $request->validate([
            'pessoa_id' => 'sometimes|required|exists:martins.pessoas,id', // O ID do proprietário é opcional, mas se fornecido, deve existir
            'placa' => 'sometimes|required|string|unique:martins.veiculos,placa,' . $veiculo->id, // A placa é opcional, mas se fornecida, deve ser única, ignorando a placa atual do veículo
            'marca' => 'sometimes|required|string|max:255', // A marca é opcional, mas se fornecida, deve ser uma string com no máximo 255 caracteres
            'modelo' => 'sometimes|required|string|max:255', // O modelo é opcional, mas se fornecido, deve ser uma string com no máximo 255 caracteres
            'ano_fabricacao' => 'sometimes|required|integer|min:1900|max:' . date('Y'), // O ano de fabricação é opcional, mas deve estar entre 1900 e o ano atual
            'ano_modelo' => 'sometimes|required|integer|min:1900|max:' . (date('Y') + 1), // O ano do modelo é opcional, mas deve estar entre 1900 e o próximo ano
            'cor' => 'nullable|string|max:50', // A cor é opcional e deve ser uma string com no máximo 50 caracteres
        ]);

        // Atualiza os dados do veículo no banco de dados
        $veiculo->update($validated);

        // Retorna os dados atualizados do veículo
        return $veiculo;
    }

    // Deletar um veículo
    public function destroy(Veiculo $veiculo)
    {
        // Exclui o veículo do banco de dados
        $veiculo->delete();

        // Retorna uma resposta sem conteúdo (204 No Content)
        return response()->noContent();
    }
}