<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produto extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "produtos";

    protected $fillable = [
        "nome",
        "preco"
    ];

    public function variacoes()
    {
        return $this->hasMany(Variacao::class);
    }
}
