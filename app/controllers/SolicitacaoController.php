<?php

namespace app\controllers;

use app\models\{
    Users,
    Requests,
    Notifications
};

class SolicitacaoController extends AuthController {
    
    public function view() {
        AuthController::auth();
        
        echo $this->app->render('site/solicitacao.twig', [
            'simple' => [
                'title'             => 'Estácio :: Solicitações',
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
            'user'       => Users::find('nome', ['conditions' => ['id = ?', $_SESSION['user']]]),
            'documentos' => Requests::all(),
            'count'      => Notifications::count(['conditions' => ['status = ?', 0] ]) 
        ]);
        
        exit();
    }
    
}
