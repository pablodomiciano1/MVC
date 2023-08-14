<?php 

namespace App\Http\Middleware;

class Queue{

    //FILA DE MIDDLEWARE A SER EXECUTADOS
    private $middlewares = [];

    //A FUNÇÃO DE EXECUÇÃO DO CONTROLADOR
    private $controller;

    private $controllerArgs = [];

    public function __construct($middlewares,$controller,$controllerArgs){
        $this->$middlewares = $middlewares;
        $this->$controller = $controller;
        $this->$controllerArgs = $controllerArgs;

    }

    /**
     * Metodo responsável por executar o proximo nivel da fila de middlewares
     *
     * @param Request $request
     * @return Response
     */
    public function next($request){
        
    }
}