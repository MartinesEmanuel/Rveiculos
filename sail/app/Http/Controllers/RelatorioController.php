<?php
// filepath: /home/martins/Documentos/Rveiculos/sail/app/Http/Controllers/RelatorioController.php

namespace App\Http\Controllers;

use App\Models\Veiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RelatorioController extends Controller
{
    // Retorna todos os veículos com informações relacionadas à pessoa proprietária
    public function veiculos()
    {
        $query = Veiculo::with('pessoa'); // Inclui os dados da pessoa relacionada ao veículo
        $dados = $query->get(); // Executa a consulta e obtém os dados
        $sql = $query->toSql(); // Obtém a consulta SQL gerada (para depuração)

        // Retorna os dados e a consulta SQL em formato JSON
        return response()->json([
            'dados' => $dados,
            'sql' => $sql,
        ]);
    }

    // Retorna a quantidade de veículos agrupados por gênero do proprietário
    public function veiculosPorGenero()
    {
        $result = DB::table('pessoas')
            ->join('veiculos', 'pessoas.id', '=', 'veiculos.pessoa_id') // Faz um join entre pessoas e veículos
            ->select('pessoas.genero', DB::raw('COUNT(veiculos.id) as total')) // Seleciona o gênero e a contagem de veículos
            ->groupBy('pessoas.genero') // Agrupa os resultados por gênero
            ->orderBy('total', 'desc') // Ordena pela quantidade de veículos em ordem decrescente
            ->get();

        // Retorna o resultado em formato JSON
        return response()->json($result);
    }

    // Retorna a quantidade de veículos agrupados por marca
    public function marcasPorVeiculos()
    {
        $result = DB::table('veiculos')
            ->select('marca', DB::raw('COUNT(id) as total')) // Seleciona a marca e a contagem de veículos
            ->groupBy('marca') // Agrupa os resultados por marca
            ->orderBy('total', 'desc') // Ordena pela quantidade de veículos em ordem decrescente
            ->get();

        // Retorna o resultado em formato JSON
        return response()->json($result);
    }

    // Calcula a média de tempo entre revisões para cada pessoa
    public function mediaTempoRevisoes()
    {
        $result = DB::table('revisoes')
            ->select(
                'pessoa_id', // Seleciona o ID da pessoa
                DB::raw('AVG(DATEDIFF(data_realizada, LAG(data_realizada) OVER (PARTITION BY pessoa_id ORDER BY data_realizada))) as media_dias') // Calcula a média de dias entre revisões consecutivas
            )
            ->groupBy('pessoa_id') // Agrupa os resultados por pessoa
            ->get();

        // Retorna o resultado em formato JSON
        return response()->json($result);
    }
}