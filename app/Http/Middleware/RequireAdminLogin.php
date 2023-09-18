<?php

namespace App\Http\Middleware;

use \App\Session\Admin\Login as SessionAdminLogin;

class RequireAdminLogin
{


    public function handle($request, $next)
    {
        //VERIFICA SE O USUARIO ESTA LOGADO
        if (!SessionAdminLogin::isLogged()) {
            $request->getRouter()->redirect('/admin/login');
        }
        //CONTINUA A EXECUÇÃO
        return $next($request);
    }
}
