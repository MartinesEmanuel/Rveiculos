<?php
namespace App\Http\Controllers;

use App\Models\Revisao;
use Illuminate\Http\Request;

class RevisaoController extends Controller
{
    // Listar todas as revisões
    public function index()
    {
        // Retorna todas as revisões com os dados do veículo relacionado
        return Revisao::with('veiculo')->get();
    }

    // Criar uma nova revisão
    public function store(Request $request)
    {
        // Valida os dados recebidos na requisição
        $validated = $request->validate([
            'veiculo_id' => 'required|exists:veiculos,id', // O ID do veículo é obrigatório e deve existir na tabela veiculos
            'data_prevista' => 'required|date', // A data prevista é obrigatória e deve ser uma data válida
            'status' => 'nullable|string', // O status é opcional e deve ser uma string
            'servicos' => 'nullable|array', // Os serviços são opcionais e devem ser um array
            'observacoes' => 'nullable|string', // As observações são opcionais e devem ser uma string
        ]);

        // Cria a revisão no banco de dados com os dados validados
        return Revisao::create($validated);
    }

    // Mostrar uma revisão específica
    public function show(Revisao $revisao)
    {
        // Retorna os dados da revisão com os dados do veículo relacionado
        return $revisao->load('veiculo');
    }

    // Atualizar uma revisão
    public function update(Request $request, $id)
    {
        // Busca a revisão pelo ID, retornando erro 404 se não encontrada
        $revisao = Revisao::findOrFail($id);

        // Preenche os dados da revisão com os dados recebidos na requisição
        $revisao->fill($request->all());

        // Salva as alterações no banco de dados
        $revisao->save();

        // Retorna a revisão atualizada
        return response()->json($revisao);
    }

    // Deletar uma revisão
    public function destroy($id)
    {
        // Busca a revisão pelo ID
        $revisao = Revisao::find($id);

        // Verifica se a revisão foi encontrada
        if (!$revisao) {
            return response()->json(['message' => 'Revisão não encontrada.'], 404);
        }

        // Exclui a revisão do banco de dados
        $revisao->delete();

        // Retorna uma mensagem de sucesso
        return response()->json(['message' => 'Revisão excluída com sucesso.']);
    }
}
