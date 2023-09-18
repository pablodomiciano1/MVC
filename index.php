<?php


require __DIR__.'/includes/app.php';


use \App\Http\Router;


$obRouter = new Router(URL);

//INCLUI AS ROTAS DA PAGINA
include __DIR__.'/routes/pages.php';
//INCLUI AS ROTAS DA PAGINA
include __DIR__.'/routes/admin.php';


//IMPRIME O RESPONSE DA ROTA
$obRouter->run()->sendResponse();


