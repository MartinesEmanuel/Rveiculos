<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Veiculo extends Model
{
    use HasFactory;

    // Define a tabela no esquema 'martins'
    protected $table = 'martins.veiculos';

    // Campos que podem ser preenchidos
    protected $fillable = [
        'pessoa_id',
        'placa',
        'marca',
        'modelo',
        'ano_fabricacao',
        'cor',
        'proxima_revisao', // Adicione este campo se quiser salvar a data da próxima revisão!
    ];

    // Relacionamento: Um veículo pertence a uma pessoa
    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class, 'pessoa_id');
    }

    // Relacionamento: Um veículo tem muitas revisões
    public function revisoes()
    {
        return $this->hasMany(Revisao::class);
    }
}