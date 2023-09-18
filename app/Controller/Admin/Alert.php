<?php

namespace App\Controller\Admin;

use \App\Utils\View;

class Alert
{

    //RETORNA MENSAGEM DE ERRO
    public static function getError($message)
    {
        return View::render('admin/alert/status', [
            'tipo' => 'danger',
            'mensagem' => $message
        ]);
    }

    //RETORNA MENSAGEM DE SUCESSO
    public static function getSuccess($message)
    {
        return View::render('admin/alert/status', [
            'tipo' => 'success',
            'mensagem' => $message
        ]);
    }
}
