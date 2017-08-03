<?php

namespace app\controllers;

use app\models\{
    Users,
    Notifications
};

class HomeController extends AuthController {
    
    
    /**
     * -------------------------------------------------------------------------
     * Renderiza/Mostra a página de home da aplicação
     * -------------------------------------------------------------------------
     */
    
    public function view() {
        AuthController::auth();
        
        echo $this->service->render('site/home.twig', [
            'simple' => [
                'title'             => 'Estácio :: Home',
                'data'              => date('Y'),
                'asset'             => url('/public'),
                'notificacoes'      => url('/notificacoes'),
                'home'              => url('/home'),
                'novo-aluno'        => url('/cadastro/aluno'),
                'upload'            => url('/cadastro/arquivo'), 
                'deletar-aluno'     => url('/deletar/aluno'), 
                'nova-solicitacao'  => url('/cadastro/solicitacao'),
                'lista-solicitacao' => url('/solicitacao'),
                'lista-aluno'       => url('/lista'),
                'deslogar'          => url('/logout')
            ],
            'user'  => Users::find('nome', ['conditions' => ['id = ?', $_SESSION['user']]]),
            'count' => Notifications::count(['conditions' => ['status = ?', 0] ]) 
        ]);
        
        exit();
    }
    
    
    public function enviar() {
        // Dados do formulário
        $send   = filter_input(INPUT_POST, 'pesquisa', FILTER_SANITIZE_STRING);
        $filtro = filter_input(INPUT_POST, 'filtro', FILTER_SANITIZE_STRING);
        
        // Substituir espaço vazio por '-'
        $str = str_replace(' ', '-', $send);
        
        // Verifica se há filtragem na pesquisa
        if($str && $filtro):
            $this->response->redirect(url("/lista/{$str}/{$filtro}"))->send();
        else:
            $this->response->redirect(url("/lista/{$str}"))->send();
        endif;
        
    }
    
}
