<?php

namespace app\controllers;

use app\models\{
    Users,
    Students,
    Notifications
};

class ListaController extends AuthController {
    

    public function view() {
        AuthController::auth();
        
        $str    = $this->request->str;
        $filtro = $this->request->filtro;
        
        $rpc = str_replace('-', ' ', $str);
        
        $alunos = explode(',' , $rpc);
        
        if($filtro != NULL):
            if($filtro == 'matricula'):
                $conditions = ['conditions' => "atual LIKE '%" . trim($alunos[0]) . "%' OR nome LIKE '%" . trim($alunos[0]) . "%'"];
            elseif($filtro == 'caixa'):
                $conditions = ['conditions' => "nome LIKE '%" . trim($alunos[0]) . "%' OR caixa = '" . trim($alunos[0]) . "'"];
            endif;
        else:
            $conditions = ['conditions' => "nome LIKE '%" . trim($alunos[0]) . "%'"];
        endif;
                
        $count = 0;
        foreach($alunos as $aluno):
            if($count > 0):
                $conditions = $this->pesquisar_param($filtro, $conditions, $aluno);
            else:
                $count++;
            endif;
        endforeach;
        $conditions['limit'] = 1000;
        
        if(isset($conditions)):
            $this->listagem_com_condicional($conditions);
        else:
            $this->listagem_padrao();
        endif;
        
        exit();
    }
    
    public function deletar() {
        // Inicia a sessão
        session_start();
        
        $id    = $this->request->id; // Captura o ID do aluno(a) selecionado
        $token = $this->request->token; // Captura o token
        
        // Realiza uma pesquisa pelo aluno(a)
        $aluno = Students::find($id);
        
        // Verifica se o aluno existe e o token
        if($aluno && $token == $_SESSION['token']):
            // Deleta o aluno(a)
            Students::delete_all(['conditions' => ['id = ?', $id]]);
        
            // Mensagem de sucesso ao excluir o(a) aluno(a)
            echo "<script> alert('Aluno excluido com sucesso! '); window.location.href = '". url('/lista') ."' </script>";
        else:
            // Mensagem de falha, caso haja algum erro
            echo "<script> alert('Falha ao excluir o aluno. ') window.location.href = '". url('/lista') ."' </script>";
        endif;
        
        // TOKEN
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVXWYZabcdefghijklmnopqrstuvxwyz0123456789';
        $_SESSION['token'] = hash('sha512', str_shuffle($chars));
        
        // Fechando sessão
        session_write_close();
    }
    
    
    /* ----------------------- FUNÇÕES PRIVADAS ----------------------------- */
    
    
    private function listagem_padrao() {
        echo $this->app->render('site/lista.twig', [
            'simple' => [
                'title'             => 'Estácio :: Lista de Alunos',
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
                'deslogar'          => url('/logout'),
                'deletar'           => url('/deletar')
            ],
            'user'   => Users::find('nome', ['conditions' => ['id = ?', $_SESSION['user']]]),
            'alunos' => Students::find('all', ['limit' => 1000]),
            'count'  => Notifications::count(['conditions' => ['status = ?', 0] ]), 
            'token'  => $_SESSION['token']
        ]);
    }
    
    private function listagem_com_condicional(array $conditions) {
        session_start(['read_and_close' => TRUE]);
        
        echo $this->app->render('site/lista.twig', [
            'simple' => [
                'title'             => 'Estácio :: Lista de Alunos',
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
                'deslogar'          => url('/logout'),
                'deletar'           => url('/deletar')
            ],
            'user'   => Users::find('nome', ['conditions' => ['id = ?', $_SESSION['user']]]),
            'alunos' => Students::find('all', $conditions),
            'count'  => Notifications::count(['conditions' => ['status = ?', 0] ]) ,
            'token'  => $_SESSION['token']
        ]);
    }
    
    private function pesquisar_param($filtro, array $conditions, $aluno) {
        // Verifica se há algum filtro
        if($filtro !== NULL):
            
            // Verifica se o filtro é do tipo 'matrícula'
            if($filtro == 'matricula'):
                $conditions['conditions'] .= "OR atual LIKE '%" . trim($aluno) . "%' OR nome LIKE '%" . trim($aluno) . "%'";
            // Verifica se o filtro é do tipo 'caixa'
            elseif($filtro == 'caixa'):
                $conditions['conditions'] .= "OR nome LIKE '%" . trim($aluno) . "%' OR caixa = '" . trim($aluno) . "'";
            endif;
        else:
            // Caso contrário não há filtragem
            $conditions['conditions'] .= "OR nome LIKE '%" . trim($aluno) . "%'";
        endif;
        
        return $conditions;
    }
}

