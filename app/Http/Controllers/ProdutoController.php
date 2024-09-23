<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProdutoController extends Controller
{
    public function index()
    {
        return view("teste");
    }

    public function get($idProduto)
    {
        $produto = Produto::with("variacoes")->find($idProduto);

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
        //TODO: criar filtros e paginação
    }

    public function novoProduto()
    {
        return "view blade - novo produto";
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

        return response()->json([
                "success" => true,
                "message" => "Cadastrado com Sucesso."
            ]
        );
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

        return response()->json([
            "success" => true,
            "message" => "Atualizado com Sucesso."
        ]);
    }

    public function destroy(Produto $produto)
    {
        DB::transaction(function () use ($produto) {
            $produto->delete();
            $produto->variacoes()->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'Produto e variações deletados com sucesso!'
        ], 204);
    }

    public function restore(Produto $produto)
    {
        DB::transaction(function () use ($produto) {
            
            $produto->restore();

            $produto->variacoes()->restore();
        });

        return response()->json(['message' => 'Produto e variações restaurados com sucesso!'], 200);
    }

}
