<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Variacao extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "variacoes";

    protected $fillable = [
        "produto_id",
        "nome"
    ];

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }
}
