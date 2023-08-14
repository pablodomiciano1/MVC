<?php

namespace App\Http;

use \Closure;
use \Exception;
use \ReflectionFunction;
use \App\Http\Middleware\Queue as MiddlewareQueue; 


class Router {

    /**
     * URL completa do projeto (raiz)
     * @var string
     */
    private $url = '';

    /**
     * Prefixo de todas as rotas
     * @var string
     */
    private $prefix ='';

    /**
     * Indice de rotas
     * @var array
     */
    private $routes = [];

    /**
     * Instancia de Request
     * @var Request
     */
    private $request;

    /**
     * Metodo resonsavel por iniciar a classe
     * @param string $url
     */
    public function __construct($url){
        $this->request = new Request($this);
        $this->url = $url;
        $this->setPrefix();
    }

    /**
     * Metodo resposanvel por definir o prefixo das rotas
     */
    private function setPrefix(){
        //INFORMAÇOES DA URL ATUAL
        $parseUrl = parse_url($this->url);

        //DEFINE O PREFIXO
        $this->prefix = $parseUrl['path'] ?? '';
        }

    /**
     * Metodo responsavel por adicionar uma rota na classe
     * @param string $method
     * @param string $route
     * @param array $params
     */
    private function addRoute($method, $route, $params=[]){
        //VALIDAÇAO DOS PARAMETROS
        foreach ($params as $key => $value) {
            if ($value instanceof Closure) {
                $params['controller'] = $value;
                unset($params[$key]);
                continue;
            }
        }

        //MIDDLEWARES DA ROTA
        $params['middlewares'] = $params['middlewares'] ?? [];

        

        //VARIAVEIS DA ROTA
        $params['variables'] = [];

        //PADRAO DE VALIDAÇAO DAS VARIAVEIS DAS ROTAS
        $patternVariabel = '/{(.*?)}/';
        if (preg_match_all($patternVariabel,$route,$matches)) {
            $route = preg_replace($patternVariabel, '(.*?)',$route);
            $params['variables'] = $matches[1];
        }

        //PADRAO DE VALIDAÇAO DA URL
        $patternRoute = '/^'.str_replace('/','\/',$route).'$/';

        //ADICIONA A ROTA DENTRO DA CLASSE
        $this->routes[$patternRoute][$method] = $params;
    }

    /**
     * Metodo responsavel por definir uma rota de GET
     * @param string $route
     * @param array $params
     */
    public function get($route, $params = []){
        return $this->addRoute('GET', $route, $params);
    }

    /**
     * Metodo responsavel por definir uma rota de POST
     * @param string $route
     * @param array $params
     */
    public function post($route, $params = []){
        return $this->addRoute('POST', $route, $params);
    }

    /**
     * Metodo responsavel por definir uma rota de PUT
     * @param string $route
     * @param array $params
     */
    public function put($route, $params = []){
        return $this->addRoute('PUT', $route, $params);
    }

    /**
     * Metodo responsavel por definir uma rota de DELETE
     * @param string $route
     * @param array $params
     */
    public function delete($route, $params = []){
        return $this->addRoute('DELETE', $route, $params);
    }

    /**
     * Metodo responsavel por retrnar a URI desconsiderando o prefixo
     * @return string
     */
    private function getUri(){
        $uri = $this->request->getUri();

        //FATIA A URI COM O PREFIXO
        $xUri = strlen($this->prefix) ? explode($this->prefix,$uri) : [$uri];
        
        //RETORNA A URI SEM PREFIXO
        return end($xUri);
    }

    /**
     * Metodo responsavel por retornar os dados da rota atual
     * @return array
     */
    private function getRoute(){
        //URI
        $uri = $this->getUri();

        //METHOD
        $httpMethod = $this->request->getHttpMethod();

        //VALIDA AS ROTAS
        foreach ($this->routes as $patternRoute=>$methods) {
            //VERIFICA SE A URI BATE O PADRAO
            if(preg_match($patternRoute, $uri,$matches)){
                //VERFICA O METODO
                if (isset($methods[$httpMethod])) {
                    //REMOVE A PRIMEIRA POSIÇAO
                    unset($matches[0]);  
                    
                    //VARIAVEIS PROCESADAS
                    $keys = $methods[$httpMethod]['variables']; 
                    $methods[$httpMethod]['variables'] = array_combine($keys,$matches);
                    $methods[$httpMethod]['variables']['request'] = $this->request;

                    //RETORNO DOS PARAMETROS DA ROTA
                    return $methods[$httpMethod];    
                }
                //METODO NAO PERMITIDO/DEFINIDO
                throw new Exception("Metodo não é permitido", 405);
                
            }
        
        }

        //URL NAO ENCONTRADA
        throw new Exception("URL não encontrada", 404);
    }

    

    /**
     * Metodo responsavel por executar a rota atual
     * @return Response
     */
    public function run(){
        try {
            //OBTEM A ROTA ATUAL
            $route = $this->getRoute();

            //VERIFICA O CONTRROLADOR
            if (!isset($route['controller'])) {
                throw new Exception("A URL não pode ser processada", 500);   
            }
            //ARGUMENTOS DA FUNÇAO
            $args = [];

            //REFLECTION
            $reflection = new ReflectionFunction($route['controller']);
            foreach ($reflection->getParameters() as $parameter) {
                $name = $parameter->getName();
                $args[$name] = $route['variables'][$name] ?? '';
            }
            
            
            //RETORNA EXECUÇÃO DA FILA DE MIDDLEWARES
            return (new MiddlewareQueue($route['middlewares'],$route['controller'],$args))->next($this->request);
        } catch (Exception $e) {
            return new Response($e->getCode(),$e->getMessage());
        }

    }

    public function getCurrentUrl(){
        return $this->url.$this->getUri();
    }
}

