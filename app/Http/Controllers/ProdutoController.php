<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProdutoController extends Controller
{
    public function index()
    {
        return view("produtos.index");
    }

    public function get($idProduto)
    {
        $produto = Produto::with(['variacoes' => function ($query) {
            $query->select('produto_id', 'nome');
        }])->find($idProduto);

        if(!$produto){
            return ["data" => false, "message" => "Não encontrado."];
        }

        return [
            "data" => $produto
        ];

    }

    public function listar(Request $req)
    {
        $produtos = $req->desativados ? Produto::withTrashed()->with("variacoes")->get() : Produto::with("variacoes")->get();

        if(!$produtos->count()){
            return ["data" => false, "message" => "Não há produtos cadastrados."];
        }

        return [
            "data" => $produtos
        ];
    }

    public function store(Request $req)
    {
        $dados = $req->validate([
            'nome' => 'required|string|max:100',
            'preco' => 'required|numeric',
            'variacoes' => 'required|array',
            'variacoes.*' => 'required|string|max:50',
        ]);

        DB::transaction(function () use ($dados) {
            
            $produto = Produto::create($dados);
            
            foreach ($dados["variacoes"] as $variacaoNome) {
                $produto->variacoes()->create(['nome' => $variacaoNome]);
            }

        });

        return redirect()->route('produtos.index')->with('success', 'Produto criado com sucesso!');
    }

    public function update(Produto $produto, Request $req)
    {
        $dados = $req->validate([
            'nome' => 'sometimes|required|string|max:100',
            'preco' => 'sometimes|required|numeric',
            'variacoes' => 'required|array',
            'variacoes.*' => 'required|string|max:50',
        ]);

        DB::transaction(function () use ($dados, $produto) {

            $produto->update([
                'nome' => $dados['nome'] ?? $produto->nome,
                'preco' => $dados['preco'] ?? $produto->preco,
            ]);

            $produto->variacoes()->delete();

            foreach ($dados['variacoes'] as $variacaoNome) {
                $produto->variacoes()->create(['nome' => $variacaoNome]);
            }

        });

        return redirect()->route('produtos.index')->with('success', 'Produto atualizado com sucesso!');
    }

    public function destroy(Produto $produto)
    {
        DB::transaction(function () use ($produto) {
            $produto->delete();
            $produto->variacoes()->delete();
        });

        return response()->json(['success' => true]);
    }

    public function restore(int $idProduto)
    {
        DB::transaction(function () use ($idProduto) {
            $produto = Produto::withTrashed()->findOrFail($idProduto);
            $produto->restore();

            $produto->variacoes()->restore();
        });

        return response()->json(['success' => true]);
    }

}
