<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Organization;

class About extends Page{

    /**
     * Metodo resonsavel por retornar o conteudo (view) da nossa pagina de Sobre
     * @return string
     */
    public static function getAbout(){

        $obOrganization = new Organization;


        //VIEW DA HOME
        $content = View::render('pages/about', [
            'name' => $obOrganization->name,
            'description' => $obOrganization->description,
            'site' => $obOrganization->site
        ]);

        //RETORNA A VIEW DA PAGINA
        return parent::getPage('SOBRE > Engish', $content);
    }
}