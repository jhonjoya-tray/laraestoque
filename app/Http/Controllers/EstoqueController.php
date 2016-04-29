<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Produto;

use App\Http\Requests;

class EstoqueController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getIndex(Produto $produto)
    {

        return view('estoques/index', ['produtos' => $produto->paginate()]);
    }

    public function postEstoque(Produto $produto)
    {

        $produtos = \Request::get('produto');
        $produtosAtualizados = [];


        //Interar os produtos enviados atualizando
        foreach ($produtos as $id => $quantidade) {
            $produto = $produto->find($id);
            if(!$produto){
                continue;
            }

            if($produto->quantidade == intval($quantidade)) {
                continue;
            }

            $produto->quantidade = $quantidade;

            if($produto->save()){
                array_push($produtosAtualizados, ['foiAtualuzado' => true, 'produto' => $produto]);
                continue;
            }
            array_push($produtosAtualizados, ['foiAtualuzado' => false, 'produto' => $produto]);
        }

        return \Redirect::action('EstoqueController@getIndex')->with('logs', $produtosAtualizados);
    }
}
