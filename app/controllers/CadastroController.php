<?php

namespace app\controllers;

use app\models\{
    Users,
    Students,
    Requests,
    Notifications
};

class CadastroController extends AuthController {
    
    
    /* ------------------------- REDENRIZAÇÃO ------------------------------- */

    
    public function view_admin() {
        echo $this->app->render('adm/cadastro.twig', [
            'simple' => [
                'title'  => 'Estácio :: Administrador',
                'data'   => date('Y'),
                'asset'  => url('/public'),
                'login'  => url(),
                'secret' => getenv('API_SECRET')
            ]
        ]);
        
        exit();
    }
    
    public function view_aluno() {
        AuthController::auth();
        
        echo $this->app->render('site/cadastro_aluno.twig', [
            'simple' => [
                'title'             => 'Estácio :: Cadastro de Alunos',
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
    
    public function view_arquivo() {
        AuthController::auth();
        
        echo $this->app->render('site/cadastro_arquivo.twig', [
            'simple' => [
                'title'             => 'Estácio :: Upload de Arquivo(Cadastrar)',
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
    
    public function view_deletar() {
        AuthController::auth();
        
        echo $this->app->render('site/deletar_aluno.twig', [
            'simple' => [
                'title'             => 'Estácio :: Upload de Arquivo(Deletar)',
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
    
    public function deletar_arquivo() {
        
        $file = $_FILES['arquivo_csv'];
        $arq      = fopen($file['tmp_name'], 'r');
        $conteudo = fread($arq, $file['size']);
        $mats     = explode(',', $conteudo);
        
        foreach($mats as $mat):
            $estudante = Students::find(['conditions' => ['atual = ?', $mat]]);
            if($estudante):
                $estudante->delete();
            endif;
        endforeach;
        
    }
    
    public function view_solicitacao() {
        AuthController::auth();
        
        echo $this->app->render('site/cadastro_solicitacao.twig', [
            'simple' => [
                'title'             => 'Estácio :: Cadastro de Solicitações',
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
    
    
    /* -------------------------- SALVAMENTOS ------------------------------- */
 
    
    public function salvar_admin() {
        // Dados do formulário
        $attributes = filter_input_array(INPUT_POST); 
        
        // Validando dados
        $attributes['nome']  = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
        $attributes['email'] = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $attributes['senha'] = password_hash($attributes['senha'], PASSWORD_BCRYPT, ['cost' => 12]);
        
        // Cadastrando usuário/admin
        $users = Users::create($attributes);
        
        // Verifica se houve o cadastro
        if($users):
            echo "<script> alert('Cadastrado com sucesso !!!'); window.location.href = ' " . url() .  " ' </script>";
        else:           
            echo "<script> alert('Erro ao cadastrar-se. Tente mais tarde.'); window.location.href = ' " . url('/cadastro/admin') .  " ' </script>";
        endif;
       
    }
    
    public function salvar_aluno() {
        // Dados do formulário
        $attributes = filter_input_array(INPUT_POST); 
        
        // Validando os dados
        $attributes['atual']   = filter_input(INPUT_POST, 'atual', FILTER_SANITIZE_NUMBER_INT);
        $attributes['nome']    = filter_input(INPUT_POST, 'nome');
        $attributes['caixa']   = filter_input(INPUT_POST, 'caixa', FILTER_SANITIZE_NUMBER_INT);
        
        // Cadastrando alunos
        $alunos = Students::create($attributes);
        
        // Verificando se houve o cadastro
        if($alunos):
            echo "<script> alert('Aluno cadastrado com sucesso!'); window.location.href = ' " . url('/home') .  " ' </script>";
        else:           
            echo "<script> alert('Erro ao cadastrar-se. Tente mais tarde.'); window.location.href = ' " . url('/cadastro/aluno') .  " ' </script>";
        endif;
       
    }
    
    public function salvar_arquivo() {
        // Arquivo do formulário
        $file   = $_FILES['arquivo_csv']; 
        
        // Abre o arquivo e lê seu conteudo
        $object = fopen($file['tmp_name'], 'r');
        
        // Verifica quantidade de dados e retorna em um @array
        while ( ($attributes = fgetcsv($object, 1024, ';')) !== FALSE ):
            if($attributes[0] !== 'Matr. Antiga'):
                // Colunas da tabela com seus respectivos valores
                $columns = [
                    'atual' => $attributes[1],
                    'nome'  => $attributes[2],
                    'caixa' => $attributes[3]
                ];
                
                // Salvando/Criando os dados do arquivo no banco de dados
                Students::create($columns);
                
                // Mensagem de sucesso após o salvamento 
                echo "<script> alert('Arquivo cadastrado com sucesso! '); window.location.href = '" . url('/cadastro/arquivo') .  "' </script>";
            endif;
        endwhile;
        
        // Fecha o arquivo
        fclose($object);
    }
    
    public function salvar_solicitacao() {
        // Dados do formulário
        $attributes = filter_input_array(INPUT_POST); 
        
        // Validando os dados
        $attributes['matricula']        = filter_input(INPUT_POST, 'matricula', FILTER_SANITIZE_NUMBER_INT);
        $attributes['nome']             = filter_input(INPUT_POST, 'nome');
        $attributes['caixas']           = filter_input(INPUT_POST, 'caixas', FILTER_SANITIZE_NUMBER_INT);
        $attributes['data_solicitacao'] = filter_input(INPUT_POST, 'data_solicitacao');
        $attributes['data_devolucao']   = filter_input(INPUT_POST, 'data_devolucao');
        $attributes['solicitante']      = filter_input(INPUT_POST, 'solicitante');
        
        // Cadastrando solicitações
        $solicitacao = Requests::create($attributes);
        
        // Verifica se houve o cadastro
        if($solicitacao):
            $notify = Notifications::create(['idRequests' => $solicitacao->id]);
            if($notify):
                echo "<script> alert('Solicitação cadastrada com sucesso!'); window.location.href = ' " . url('/home') .  " ' </script>";
            endif;
        else:           
            echo "<script> alert('Erro ao cadastrar a solicitação. Tente mais tarde.'); window.location.href = ' " . url('/cadastro/solicitacao') .  " ' </script>";
        endif;
       
    }
}
