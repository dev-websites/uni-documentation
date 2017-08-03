<?php

namespace app\controllers;


class IndexController extends \Src\Http\Controller {
    
    
    /**
     * -------------------------------------------------------------------------
     * Renderiza/Mostra a página inicial, o login
     * -------------------------------------------------------------------------
     */
    
    public function view() {
        echo $this->app->render('adm/login.twig', [
            'simple' => [
                'title'    => 'Estácio :: Login',
                'data'     => date('Y'),
                'asset'    => url('/public'),
                'cadastro' => url('/cadastro/admin')
            ]
        ]);
        
        exit();
    }
    
}
