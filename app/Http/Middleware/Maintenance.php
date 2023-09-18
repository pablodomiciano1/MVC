<?php

namespace App\Http\Middleware;

Class Maintenance{

    public function handle($request, $next){
        //VERIFICA O ESTADO DE MANUTENCAO DA PAGINA
        if(getenv('MAINTENANCE') == 'true'){
            throw new \Exception("Pagina em manutenção, tente novamente mais tarde", 200);
        }
        //EXECUTA O PROXIMO NIVEL
        return $next($request);

        
    
    }
}