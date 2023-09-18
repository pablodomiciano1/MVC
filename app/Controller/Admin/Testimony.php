<?php

namespace App\Controller\Admin;

use \App\Utils\View;
use SessionHandler;
use \App\Model\Entity\Testimony as EntityTestimony;
use \WilliamCosta\DatabaseManager\Pagination;


class Testimony extends Page{

    private static function getTestimonyItems($request,&$obPagination){
        //DEPOIMENTOS
        $itens='';
    
        //QUANTIDADE TOTAL DE REGISTRO
        $quantidadeTotal = EntityTestimony::getTestimonies(null,null,null,'COUNT(*) as qtd')->fetchObject()->qtd;
    
        //PAGINA ATUAL
        $queryParams = $request->getQueryParams();
        $paginaAtual = $queryParams['page'] ?? 1;    
    
        //INSTANCIA DE PAGINAÇÃO
        $obPagination = new Pagination($quantidadeTotal, $paginaAtual,5);
        
        
        //RESULTADOS
        $results = EntityTestimony::getTestimonies(null,'id DESC', $obPagination->getLimit());
    
        //RENDERIZA O ITEM
        while($obTestimony = $results->fetchObject(EntityTestimony::class)){
         $itens .= View::render('admin/modules/testimonies/item', [
            'id'=> $obTestimony->id,
            'nome'=> $obTestimony->nome,
            'mensagem'=> $obTestimony->mensagem,
            'data'=> date('d/m/Y H:i:s',strtotime($obTestimony->data))
        ]);
        }
    
        //RETORNA OS DEPOIMENTOS
        return $itens;
    
        }

    //RENDERIZA A VIEW DE LISTAGEM DE DEPOIMENTOS
    public static function getTestimonies($request){
        //CONTEUDO DA HOME
        $content = View::render('admin/modules/testimonies/index', [
            'itens' => self::getTestimonyItems($request,$obPagination),
            'pagination' => parent::getPagination($request,$obPagination),
            'status' => self::getStatus($request)
        ]);

        return parent::getPanel('Depoimentos > GPSFOODs',$content,'testimonies');
    }

    public static function getNewTestimony($request){
         //CONTEUDO DO FORM
         $content = View::render('admin/modules/testimonies/form', [
         'title'  => 'Cadastrar Depoimentos',
         'nome'=> '',
         'mensagem' => '',
         'status' => ''
        ]);


        //RETORNA A PAGINA COMPLETA
        return parent::getPanel('Cadastrar Depoimento > GPSFOODs',$content,'testimonies');
     }
   
     public static function setNewTestimony($request){
        //POST VARS
        $postVars = $request->getPostVars();

        //NOVA INSTANCIA DE DEPOIMENTO
        $obTestimony = new EntityTestimony;
        $obTestimony -> nome = $postVars['nome'] ?? '';
        $obTestimony -> mensagem = $postVars['mensagem'] ?? '';
        $obTestimony -> cadastrar();

        //REDIRECIONA O USUARIO
        $request->getRouter()->redirect('/admin/testimonies/'.$obTestimony->id.'/edit?status=created');
        
    }

    private static function getStatus($request){
        //QUERY PARAMS
        $queryParams = $request->getQueryParams();

        //STATUS
        if(!isset($queryParams['status'])) return '';

        //MENSAGEM DE STATUS
        switch ($queryParams['status']){
            case 'created':
                return Alert::getSuccess('Depoimento criado com sucesso!');
                break;
            case 'updated':
                return Alert::getSuccess('Depoimento atualizado com sucesso!');
                break;
            case 'deleted':
                return Alert::getSuccess('Depoimento excluido com sucesso!');
                break;    
        }
 }

    public static function getEditTestimony($request,$id){
        //OBTEM DEPOIMENTO DO BD
        $obTestimony = EntityTestimony::getTestimonyById($id);

        //VALIDA A INSTANCIA
        if(!$obTestimony instanceof EntityTestimony){
            $request->getRouter()->redirect('/admin/testimonies');
        }
        
        //CONTEUDO DO FORM
        $content = View::render('admin/modules/testimonies/form', [
        'title'  => 'Editar Depoimentos',
        'nome' => $obTestimony->nome,
        'mensagem' => $obTestimony->mensagem,
        'status' => self::getStatus($request)
       ]);

    
       //RETORNA A PAGINA COMPLETA
       return parent::getPanel('Editar Depoimento > GPSFOODs',$content,'testimonies');
    }
    
    //METODO RESPONSVEL POR GRAVAR A ATUALIZAÇÃO DE UM DEPOIMENTO
    public static function setEditTestimony($request,$id){
        //OBTEM DEPOIMENTO DO BD
        $obTestimony = EntityTestimony::getTestimonyById($id);

        //VALIDA A INSTANCIA
        if(!$obTestimony instanceof EntityTestimony){
            $request->getRouter()->redirect('/admin/testimonies');
        }
        //POST VARS
        $postVars = $request->getPostVars();

        //ATUALIZA INSTANCIA
        $obTestimony->nome = $postVars['nome'] ?? $obTestimony->nome;
        $obTestimony->mensagem = $postVars['mensagem'] ?? $obTestimony->mensagem;
        $obTestimony->atualizar();

        //REDIRECIONA O USUARIO
        $request->getRouter()->redirect('/admin/testimonies/'.$obTestimony->id.'/edit?status=updated');
        
    }

    public static function getDeleteTestimony($request,$id){
        //OBTEM DEPOIMENTO DO BD
        $obTestimony = EntityTestimony::getTestimonyById($id);

        //VALIDA A INSTANCIA
        if(!$obTestimony instanceof EntityTestimony){
            $request->getRouter()->redirect('/admin/testimonies');
        }
        
        //CONTEUDO DO FORM
        $content = View::render('admin/modules/testimonies/delete', [
        'nome' => $obTestimony->nome,
        'mensagem' => $obTestimony->mensagem
       ]);

    
       //RETORNA A PAGINA COMPLETA
       return parent::getPanel('Excluir Depoimento > GPSFOODs',$content,'testimonies');
    }

      //METODO RESPONSVEL POR EXCLUIR UM DEPOIMENTO
      public static function setDeleteTestimony($request,$id){
        //OBTEM DEPOIMENTO DO BD
        $obTestimony = EntityTestimony::getTestimonyById($id);

        //VALIDA A INSTANCIA
        if(!$obTestimony instanceof EntityTestimony){
            $request->getRouter()->redirect('/admin/testimonies');
        }
       //EXCLUI O DEPOIMENTO
        $obTestimony->excluir();

        //REDIRECIONA O USUARIO
        $request->getRouter()->redirect('/admin/testimonies?status=deleted');
        
    }
  
}


