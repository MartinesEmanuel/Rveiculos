<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Revisao extends Model
{
    use HasFactory;

    // Define a tabela no esquema 'martins'
    protected $table = 'martins.revisoes';

    // Campos que podem ser preenchidos
    protected $fillable = [
        'veiculo_id',
        'data_prevista',
        'status',
        'servicos',
        'observacoes'
    ];

    protected $casts = [
        'servicos' => 'array',
    ];

    // Relacionamento: Uma revisão pertence a um veículo
    public function veiculo()
    {
        return $this->belongsTo(Veiculo::class);
    }
}