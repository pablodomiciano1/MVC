<?php

namespace App\Controller\Admin;

use \App\Utils\View;
use SessionHandler;

class Home extends Page
{
    //RENDERIZA A VIEW DE HOME DO PAINEL
    public static function getHome($request){
        //CONTEUDO DA HOME
        $content = View::render('admin/modules/home/index', []);

        return parent::getPanel('Home > GPSFOODs',$content,'home');
    }

   
}
