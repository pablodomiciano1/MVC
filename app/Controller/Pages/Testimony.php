<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Testimony as EntityTestimony;
use \WilliamCosta\DatabaseManager\Pagination;

class Testimony extends Page{

    /** 
     * responsavel por obter a renderizacao dos itens de depoimentos para a pagina
     *
     * @return string
     */
    public static function getTestimonyItems($request,&$obPagination){
    //DEPOIMENTOS
    $itens='';

    //QUANTIDADE TOTAL DE REGISTRO
    $quantidadeTotal = EntityTestimony::getTestimonies(null,null,null,'COUNT(*) as qtd')->fetchObject()->qtd;

    //PAGINA ATUAL
    $queryParams = $request->getQueryParams();
    $paginaAtual = $queryParams['page'] ?? 1;    

    //INSTANCIA DE PAGINAÇÃO
    $obPagination = new Pagination($quantidadeTotal, $paginaAtual,3);
    
    
    //RESULTADOS
    $results = EntityTestimony::getTestimonies(null,'id DESC', $obPagination->getLimit());

    //RENDERIZA O ITEM
    while($obTestimony = $results->fetchObject(EntityTestimony::class)){
     $itens .= View::render('pages/testimony/item', [
        'nome'=> $obTestimony->nome,
        'mensagem'=> $obTestimony->mensagem,
        'data'=> date('d/m/Y H:i:s',strtotime($obTestimony->data))
    ]);
    }

    //RETORNA OS DEPOIMENTOS
    return $itens;

    }
    /**
     * Metodo resonsavel por retornar o conteudo (view) de depoimentos
     * @return string
     */
    public static function getTestimonies($request){

    
        //VIEW DE DEPOIMENTOS
        $content = View::render('pages/testimonies',[
            'itens' => self::getTestimonyItems($request,$obPagination),
            'pagination' => parent::getPagination($request,$obPagination)      
        ]);
        
        //RETORNA A VIEW DA PAGINA
        return parent::getPage('DEPOIMENTOS > GPS FOODs', $content);
    }

    public static function insertTestimony($request){
        $postVars = $request->getPostVars();

        $obTestimony = new EntityTestimony;
        $obTestimony->nome = $postVars['nome'];
        $obTestimony->mensagem = $postVars['mensagem'];
        $obTestimony->cadastrar();

        //RETORNA A PAGINA DE LISTAGEM DE DEPOIMENTOS
        return self::getTestimonies($request);

    }
}