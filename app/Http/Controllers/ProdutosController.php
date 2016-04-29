<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Produto;

use App\Http\Requests;

class ProdutosController extends Controller
{
	
	public function __construct()
	{
		//ou declarar via routes
		$this->middleware('auth');
	}

	/**
	 * Método lista todos os produtos 
	 * 
	 * @param  Produto $produto 
	 * @return Response
	 */
	public function getIndex(Produto $produto)
	{
		$produtoQueryBuilder = $produto->select('*');

		if(\Request::get('nome')) {
			$produtoQueryBuilder->where(\DB::raw('LOWER(nome)'), 'LIKE', '%'.strtolower(\Request::get('nome')).'%');
		}

		$listaDeProdutos = $produtoQueryBuilder->paginate(5);
		return view('produtos/index')->with('produtos', $listaDeProdutos);
	}

	/**
	 * Método obtém e exibe a view de atualização
	 * 
	 * @param  integer $produtoId
	 * @return Response
	 */
	public function getEditar($produtoId)
	{
		$produto = Produto::where('id', '=', $produtoId)->first();

		if(!$produto)
		{
			//Classe Redirect com método back para redirecionamento usando referrer
			return \Redirect::back();
		}

		return view('produtos/editar', ['produto' => $produto]);
	}

	/**
	 * Método recebe os dados postado utilizado na edição do produto
	 * @return Response
	 */
	public function postEditar()
	{

		$validator = $this->validarDadosProduto();

		if($validator->fails())
		{
			return \Redirect::back()->withErrors($validator);
		}

		$produto = Produto::find(\Request::get('id'));
		$produto->nome = \Request::get('nome');

		if($produto->save()){
			return \Redirect::action('ProdutosController@getIndex')->with('message', 'Produto atualizado com sucesso');
		}

		return \Redirect::back()->withErrors(['message', 'Não foi editar o produto']);
	}

	/**
	 * Método realiza a exclũsão do registro no banco de dados
	 * 
	 * @param  integer $produtoId Id do produto no banco de dados
	 * @return Response
	 */
	public function getDelete($produtoId) {
		$produto = Produto::find($produtoId);

		if(!$produto){
			throw new \Excepiton('Produto não localizado');
		}

		if($produto->delete()){
			\Session::flash('message', 'Produto excluido com sucesso');
			return \Redirect::back();
		}

		return \Redirect::back()->withErrors(['message', 'Não foi possivel excluir o produto']);
	}

	/**
	 * Método exibe o formulário de inserção de produtos
	 * 
	 * @return Response
	 */
	public function getInserir()
	{
		return view('produtos/inserir');
	}	

	/**
	 * Método recebe o post contendo os dados do produtos
	 * 
	 * @return [type] [description]
	 */
	public function postInserir()
	{
		$validator = $this->validarDadosProduto();
		
		if($validator->fails())
		{
			return \Redirect::back()->withErrors($validator);
		}

		if($produto = Produto::create(\Request::all())){

			$this->uploadImagem($produto, \Request::file('imagem'));

			return \Redirect::action('ProdutosController@getIndex')->with('message', 'Produto cadastrado com sucesso');
		}

		return \Redirect::back()->withInput()->withErrors(['message', 'Falha ao salvar produtos']);
	}

	private function uploadImagem($produto, $imagem)
	{
		if($imagem && $imagem->isValid()){
			//public_path é uma função do framework;
			$pathImg  = public_path('img/produtos');
			$extensao = $imagem->getClientOriginalExtension();
			$imagem->move($pathImg, $produto->id.'.'.$extensao);
			$produto->imagem = $extensao;
			$produto->save();
		}
	}

	/**
	 * Método realiza a validação dos dados do produto
	 * 
	 * @return Validator
	 */
	private function validarDadosProduto()
	{

		$messages = ['required'  => 'Informe um valor válido para :attribute'];
		$validacoes = ['nome' => 'required'];

		$validator = \Validator::make(
			\Request::all(['_token']),
			$validacoes,
			$messages
		);

		return $validator;
	}
}