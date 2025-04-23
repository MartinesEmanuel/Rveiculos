<?php
namespace App\Http\Controllers;

use App\Models\Pessoa;
use Illuminate\Http\Request;

class PessoaController extends Controller
{
    // Retorna todas as pessoas cadastradas no banco de dados
    public function index()
    {
        return Pessoa::all();
    }

    // Cria uma nova pessoa no banco de dados
    public function store(Request $request)
    {
        // Valida os dados recebidos na requisição
        $validated = $request->validate([
            'nome' => 'required|string', // Nome é obrigatório e deve ser uma string
            'idade' => 'required|integer', // Idade é obrigatória e deve ser um número inteiro
            'genero' => 'required|string', // Gênero é obrigatório e deve ser uma string
            'telefone' => 'required|string', // Telefone é obrigatório e deve ser uma string
            'email' => 'required|email|unique:pessoas,email', // Email é obrigatório, deve ser válido e único na tabela pessoas
        ]);

        // Cria a pessoa no banco de dados com os dados validados
        $pessoa = Pessoa::create($validated);

        // Retorna a pessoa criada com o código de status 201 (Criado)
        return response()->json($pessoa, 201);
    }

    // Retorna os dados de uma pessoa específica
    public function show(Pessoa $pessoa)
    {
        return $pessoa; // O Laravel automaticamente resolve o modelo com base no ID fornecido na rota
    }

    // Atualiza os dados de uma pessoa existente
    public function update(Request $request, Pessoa $pessoa)
    {
        // Valida os dados recebidos na requisição
        $validated = $request->validate([
            'nome' => 'sometimes|required|string|max:255', // Nome é opcional, mas se fornecido, deve ser uma string com no máximo 255 caracteres
            'email' => 'sometimes|required|email|unique:pessoas,email,' . $pessoa->id, // Email é opcional, mas se fornecido, deve ser válido e único, ignorando o email atual da pessoa
            'cpf' => 'sometimes|required|string|unique:pessoas,cpf,' . $pessoa->id, // CPF é opcional, mas se fornecido, deve ser único, ignorando o CPF atual da pessoa
            'telefone' => 'nullable|string', // Telefone é opcional e pode ser nulo ou uma string
            'data_nascimento' => 'nullable|date', // Data de nascimento é opcional e deve ser uma data válida
            'sexo' => 'nullable|in:M,F,Outro', // Sexo é opcional e deve ser "M", "F" ou "Outro"
            'endereco' => 'nullable|string', // Endereço é opcional e pode ser nulo ou uma string
        ]);

        // Atualiza os dados da pessoa no banco de dados
        $pessoa->update($validated);

        // Retorna os dados atualizados da pessoa
        return $pessoa;
    }

    // Exclui uma pessoa do banco de dados
    public function destroy(Pessoa $pessoa)
    {
        // Remove a pessoa do banco de dados
        $pessoa->delete();

        // Retorna uma resposta sem conteúdo (204 No Content)
        return response()->noContent();
    }
}