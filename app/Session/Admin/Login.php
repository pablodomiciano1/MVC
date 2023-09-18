<?php 

namespace App\Session\Admin;

class Login{

    //INICIA A SESSAO
    private static function init(){
    //VERIFICA SE A SESSAO NAO ESTA ATIVA
    if(session_status() != PHP_SESSION_ACTIVE){
        session_start();
    }
}

    public static function login($obUser){
    //INICIA A SESSAO   
    self::init();

    //DEFINE A SESSÃO DO USUARIO
    $_SESSION['admin']['usuario'] = [
        'id'    => $obUser->id,
        'nome'  => $obUser->nome,
        'email' => $obUser->email
    ];

    //SUCESSO
    return true;
    
        
    }
    public static function isLogged(){
        self::init();

        return isset($_SESSION['admin']['usuario']['id']);
    }

    public static function logout(){
        //INICIA A SESSÃO
        self::init();

        //DESLOGA O USUARIO
        unset($_SESSION['admin']['usuario']);

        //SUCESSO
        return true;
    }
}