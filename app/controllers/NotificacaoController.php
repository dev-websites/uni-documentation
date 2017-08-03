<?php

namespace app\controllers;

use app\models\{
    Users,
    Requests,
    Notifications
};

class NotificacaoController extends AuthController {
    
    
    public function view() {
        AuthController::auth();
        
        echo $this->app->render('site/notificacoes.twig', [
            'simple' => [
                'title'             => 'Estácio :: Notificações',
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
            'user'          => Users::find('nome', ['conditions' => ['id = ?', $_SESSION['user']]]),
            'notifications' => $this->message(),
            'count'         => Notifications::count(['conditions' => ['status = ?', 0] ]) 
        ]);
        
        exit();
    }
    
    public function leitura() {
        // Dados de verificação do formulário
        $notification = filter_input_array(INPUT_POST);
        
        // Verifica se mais de uma notificação foi selecionada
        foreach($notification as $key => $value):
            if($value == 'on'):
                $notify = Notifications::find($key);
                $notify->status = 1;
                $notify->save();
            endif;
        endforeach; 
        
        // Redireciona para a página atual
        $this->response->redirect(url('/notificacoes'))->send();
    }
    
    
    /* ----------------------- FUNÇÕES PRIVADAS ----------------------------- */
    
    
    private function message(){
        // Pesquisa todas as notificações com estado '0' ou não lido
        $notifications = Notifications::find('all', ['conditions' => ['status = ?', 0]]);
        
        $count = 0;
        foreach($notifications as $notification):
            $request   = Requests::find(['select' => 'nome, caixas, data_devolucao as dev, solicitante', 'conditions' => ['id = ?', $notification->idrequests]]);
            
            $data_dev  = date('d/m/Y', strtotime($request->dev)); // Data Devolução
            $hoje      = date('d/m/Y');                           // Hoje
            
            $dias_restantes = $data_dev - $hoje; // Diferença de dias
                        
            $notifications[$count]->msn = "Restam {$dias_restantes} dias para a devolução da documentação do aluno(a) {$request->nome}, que foi solicitado(a) por {$request->solicitante}.";
            $count++;
        endforeach;
        
        return $notifications;
    }
    
}