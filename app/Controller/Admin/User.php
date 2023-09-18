<?php

namespace App\Controller\Admin;

use \App\Utils\View;
use SessionHandler;
use \App\Model\Entity\User as EntityUser;
use \WilliamCosta\DatabaseManager\Pagination;


class User extends Page{

    private static function getUserItems($request,&$obPagination){
        //usarios
        $itens='';
    
        //QUANTIDADE TOTAL DE REGISTRO
        $quantidadeTotal = EntityUser::getUsers(null,null,null,'COUNT(*) as qtd')->fetchObject()->qtd;
    
        //PAGINA ATUAL
        $queryParams = $request->getQueryParams();
        $paginaAtual = $queryParams['page'] ?? 1;    
    
        //INSTANCIA DE PAGINAÇÃO
        $obPagination = new Pagination($quantidadeTotal, $paginaAtual,5);
        
        //RESULTADOS
        $results = EntityUser::getUsers(null,'id DESC', $obPagination->getLimit());
    
        //RENDERIZA O ITEM
        while($obUser = $results->fetchObject(EntityUser::class)){
         $itens .= View::render('admin/modules/users/item', [
            'id'=> $obUser->id,
            'nome'=> $obUser->nome,
            'email'=> $obUser->email
        ]);
        }
    
        //RETORNA OS DEPOIMENTOS
        return $itens;
        }

    //RENDERIZA A VIEW DE LISTAGEM DE USUARIOS
    public static function getUsers($request){
        
        //CONTEUDO DA HOME
        $content = View::render('admin/modules/users/index', [
            'itens' => self::getUserItems($request,$obPagination),
            'pagination' => parent::getPagination($request,$obPagination),
            'status' => self::getStatus($request)
        ]);

        //RETORNA A PAGINA COMPLETA
        return parent::getPanel('Usuarios > GPSFOODs',$content,'users');
    }

    public static function getNewUser($request){
         //CONTEUDO DO FORM
         $content = View::render('admin/modules/users/form', [
         'title'  => 'Cadastrar Usuário',
         'nome'=> '',
         'email' => '',
         'status' => self::getStatus($request)
        ]);


        //RETORNA A PAGINA COMPLETA
        return parent::getPanel('Cadastrar Usuário > GPSFOODs',$content,'users');
     }
   
     public static function setNewUser($request){
        //POST VARS
        $postVars = $request->getPostVars();
        $nome = $postVars['nome'] ?? '';
        $email = $postVars['email'] ?? '';
        $senha = $postVars['senha'] ?? '';

        
        //VALIDA O EMAIL
        $obUser = EntityUser::getUserByEmail($email);
        if ($obUser instanceof EntityUser){

            //REDIRECIONA O USUARIO
            $request->getRouter()->redirect('/admin/users/new?status=duplicated');
        }
    
        //NOVA INSTANCIA DE USUARIO
        $obUser = new EntityUser;
        $obUser -> nome = $nome;
        $obUser -> email = $email;
        $obUser -> senha = password_hash($senha,PASSWORD_DEFAULT);
        $obUser -> cadastrar();

        //REDIRECIONA O USUARIO
        $request->getRouter()->redirect('/admin/users/'.$obUser->id.'/edit?status=created');
        
    }

    private static function getStatus($request){
        //QUERY PARAMS
        $queryParams = $request->getQueryParams();

        //STATUS
        if(!isset($queryParams['status'])) return '';

        //MENSAGEM DE STATUS
        switch ($queryParams['status']){
            case 'created':
                return Alert::getSuccess('Usuario criado com sucesso!');
                break;
            case 'updated':
                return Alert::getSuccess('Usuario atualizado com sucesso!');
                break;
            case 'deleted':
                return Alert::getSuccess('Usuario excluido com sucesso!');
                break;    
                case 'duplicated':
                    return Alert::getError('Email ja utilizado por outro usuario!');
                    break;    
            }    
        }


    public static function getEditUser($request,$id){
        //OBTEM USUARIO DO BD
        $obUser = EntityUser::getUserById($id);

        //VALIDA A INSTANCIA
        if(!$obUser instanceof EntityUser){
            $request->getRouter()->redirect('/admin/users');
        }
        
        //CONTEUDO DO FORM
        $content = View::render('admin/modules/users/form', [
        'title'  => 'Editar Depoimentos',
        'nome' => $obUser->nome,
        'email' => $obUser->email,
        'status' => self::getStatus($request)
       ]);

    
       //RETORNA A PAGINA COMPLETA
       return parent::getPanel('Editar Usuario > GPSFOODs',$content,'users');
    }
    
    //METODO RESPONSVEL POR GRAVAR A ATUALIZAÇÃO DE UM USUARIO
    public static function setEditUser($request,$id){
        //OBTEM DEPOIMENTO DO BD
        $obUser = EntityUser::getUserById($id);

        //VALIDA A INSTANCIA
        if(!$obUser instanceof EntityUser){
            $request->getRouter()->redirect('/admin/users');
        }
        //POST VARS
        $postVars = $request->getPostVars();
        $nome = $postVars['nome'] ?? '';
        $email = $postVars['email'] ?? '';
        $senha = $postVars['senha'] ?? '';

           //VALIDA O EMAIL
           $obUserEmail = EntityUser::getUserByEmail($email);
           if ($obUserEmail instanceof EntityUser && $obUserEmail->id != $id){
   
               //REDIRECIONA O USUARIO
               $request->getRouter()->redirect('/admin/users/'.$id.'/edit?status=duplicated');
           }

        //ATUALIZA INSTANCIA
        $obUser->nome = $nome;
        $obUser->email = $email;
        $obUser->senha = password_hash($senha,PASSWORD_DEFAULT);
        $obUser->atualizar();

        //REDIRECIONA O USUARIO
        $request->getRouter()->redirect('/admin/users/'.$obUser->id.'/edit?status=updated');
        
    }

    public static function getDeleteUser($request,$id){
        //OBTEM USUARIO DO BD
        $obUser = EntityUser::getUserById($id);

        //VALIDA A INSTANCIA
        if(!$obUser instanceof EntityUser){
            $request->getRouter()->redirect('/admin/users');
        }
        
        //CONTEUDO DO FORM
        $content = View::render('admin/modules/users/delete', [
        'nome' => $obUser->nome,
        'email' => $obUser->email
       ]);

    
       //RETORNA A PAGINA COMPLETA
       return parent::getPanel('Excluir Usuario > GPSFOODs',$content,'users');
    }

      //METODO RESPONSVEL POR EXCLUIR UM USUARIO
      public static function setDeleteUser($request,$id){
        //OBTEM DEPOIMENTO DO BD
        $obUser = EntityUser::getUserById($id);

        //VALIDA A INSTANCIA
        if(!$obUser instanceof EntityUser){
            $request->getRouter()->redirect('/admin/users');
        }
       //EXCLUI O DEPOIMENTO
        $obUser->excluir();

        //REDIRECIONA O USUARIO
        $request->getRouter()->redirect('/admin/users?status=deleted');
        
    }
  
}


