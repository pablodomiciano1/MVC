<?php 

namespace App\Controller\Admin;

use \App\Utils\View;

class Page{
    
    private static $modules = [
    'home' => [
        'label' => 'Home',
        'link'=> URL.'/admin'
    ],
    'testimonies' => [
        'label' => 'Depoimentos',
        'link'=> URL.'/admin/testimonies'
    ],
    'users' => [
        'label' => 'Usuários',
        'link'=> URL.'/admin/users'
    ]
    ];
       

    //METODO RESPONSAVEL POR RETORNAR O CONTEUDO (VIEW) DA ESTRUTURA GENERICA DE PAGINA DO PAINEL
    public static function getPage($title,$content){
        return View::render('admin/page',[
            'title'   => $title,
            'content' => $content
        ]);
    }

    private static function getMenu($currentModule){
        //LINKS DO MENU
        $links = '';

        //ITERA OS MODULOS
        foreach(self::$modules as $hash=>$module){
            $links .= View::render('admin/menu/link', [
                'label' => $module['label'],
                'link' => $module['link'],
                'current' => $hash == $currentModule ? 'text-danger' : ''
             ]);
        }
        //RETORNA A RENDERIZACAO DO MENU
        return View::render('admin/menu/box',[
            'links' => $links
        ]);
    }

    //METODO RESPONSAVEL POR RENDERIZAR A VIEW DO PAINEL COM CONTEUDOS DINAMICOS
    public static function getPanel($title,$content,$currentModule){
        //RENDERIZA A VIEW DO PAINEL
        $contentPanel = View::render('admin/panel',[
            'menu' => self::getMenu($currentModule),
            'content' => $content

        ]);
        //RETORNA A PAGINA RENDERIZADA
        return self::getPage($title,$contentPanel);
    }

      /**
     * Metodo responsavel por renderizar o layout de paginação
     *
     * @param Request $request
     * @param Pagination $obPagination
     * @return string
     */
    public static function getPagination($request, $obPagination){
        //PAGINAS
        $pages = $obPagination->getPages();

        //VERIFICA A QUANTIDADE DE PAGINAS
        if (count($pages)<= 1)return '';

        //LINKS
        $links = '';

        //URL ATUAL (SEM GETS)
        $url = $request->getRouter()->getCurrentUrl();

        //GET
        $queryParams = $request->getQueryParams();

        //RENDERIZA OS LINKS
        foreach($pages as $page){
            //ALTERA PAGINA
            $queryParams['page'] = $page['page'];

            //LINK 
            $link = $url.'?'.http_build_query($queryParams);
            
            //VIEW 
            $links .= View::render('admin/pagination/link', [
                'page' => $page['page'],
                'link' => $link, 
                'active' => $page['current'] ? 'active' : ''
            ]);
            
        } 


            return View::render('admin/pagination/box', [
                'links' => $links
            ]);
    }


}