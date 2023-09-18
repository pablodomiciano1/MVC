<?php 

namespace App\Http\Middleware;
use App\Http\Request;
class Queue{

    private static $map = [];

    //MAPEAMENTO DE MIDDLEWARES
    private static $default = [];

    //FILA DE MIDDLEWARE A SER EXECUTADOS
    private $middlewares = [];

    //A FUNÇÃO DE EXECUÇÃO DO CONTROLADOR
    private $controller;

    private $controllerArgs = [];

    public function __construct($middlewares,$controller,$controllerArgs){
        $this->middlewares    = array_merge(self::$default,$middlewares);
        $this->controller     = $controller;
        $this->controllerArgs = $controllerArgs;

    }
    public static function setMap($map){
        self::$map = $map;

    }
    public static function setDefault($default){
        self::$default = $default;   


    }
    /**
     * Metodo responsável por executar o proximo nivel da fila de middlewares
     *
     * @param Request $request
     * @return Response
     */
    public function next($request){
        //VERIFICA FILA VAZIA
        if (empty($this->middlewares)) return call_user_func_array($this->controller,$this->controllerArgs);

        //MIDDLEWARE
        $middleware = array_shift($this->middlewares);

        //VERIFICA O MAPEAMENTO
        if(!isset(self::$map[$middleware])){
            throw new \Exception("Problemas ao processar o middleware de requisição", 500);
        }

        //NEXT
        $queue = $this;
        $next = function($request) use($queue){
            return $queue->next($request);
        };
        
        return (new self::$map[$middleware])-> handle($request,$next);
    }
}