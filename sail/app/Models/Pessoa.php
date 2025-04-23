<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Pessoa extends Model
{
    use HasFactory;

    // Define a tabela no esquema 'martins'
    protected $table = 'martins.pessoas';

    // Campos que podem ser preenchidos
    protected $fillable = ['nome', 'idade', 'genero', 'telefone', 'email'];

    // Validação de email único
    protected $rules = [
        'email' => 'unique:pessoas,email'
    ];

    // Relacionamento: Uma pessoa tem muitos veículos
    public function veiculos()
    {
        return $this->hasMany(Veiculo::class);
    }
}