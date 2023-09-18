<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Organization;
use \App\Controller\Pages\Testimony;
use App\Model\Entity\Testimony as EntityTestimony;

class Home extends Page{

    /**
     * Metodo resonsavel por retornar o conteudo (view) da nossa home
     * @return string
     */
    public static function getHome($request){

        $obOrganization = new Organization;


        //VIEW DA HOME
        $content = View::render('pages/home', [
            'name' => $obOrganization->name,
            'itens' => Testimony::getTestimonyItems($request,$obPagination),
            'pagination' => parent::getPagination($request,$obPagination)
        ]);
        

        //RETORNA A VIEW DA PAGINA
        return parent::getPage('HOME > GPS FOODs', $content);
    }

 }
