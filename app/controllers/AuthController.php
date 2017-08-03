<?php

namespace app\controllers;

use app\models\{
    Users
};

class AuthController extends \Src\Http\Controller {
    
    
    public function login() {
        // Dados do formulário
        $login = filter_input_array(INPUT_POST);
        
        // Senha com Hash no banco
        $hash = Users::find('senha', ['conditions' => ['email = ?', $login['email']] ]);
        
        if($hash):
            // Verificar senha do usuário com hash do banco
            if(password_verify($login['senha'], $hash->senha)):
                // Pesquisar pelo usuário no banco
                $user = Users::find_by_email_and_senha($login['email'], $hash->senha);

                // Iniciar sessão após o login
                session_start();
                
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVXWYZabcdefghijklmnopqrstuvxwyz0123456789';
                $token = hash('sha512', str_shuffle($chars));
                
                // Sessão usuário é criada e recebe a identificação do usuário
                $_SESSION['user']  = $user->id;
                $_SESSION['token'] = $token;

                // Escreve e fecha a sessão
                session_write_close();

                $this->response->redirect(url('/home'))->send();
            else:
                echo "<script> alert('Senha incorreta. Por favor, tente novamente.'); window.location.href = ' " . url() .  " ' </script>";
            endif;
        else:
            echo "<script> alert('Usuário requistado não foi encontrado ou não existe.'); window.location.href = ' " . url() .  " ' </script>";
        endif;
    }
 
    
    public function logout() {
        // Inicia sessão 
        session_start();
        
        // Destrói qualquer sessão 
        session_destroy();
        
        // Redirecionando o usuário para tela de login
        $this->response->redirect(url())->send();
    }

   
    /* ---------------------- FUNÇÃO PROTEGIDA ------------------------------ */
    
    
    protected static function auth() {
        // Iniciando sessão | ler e fecha
        session_start(['read_and_close' => TRUE]);
        
        // Verificando se a sessão do usuário existe
        if(!isset($_SESSION['user']) || empty($_SESSION['user']) || ($_SESSION['user'] == 0)):
            echo "<script> alert('Você não tem permissão para acessar essa página. Por favor, efetue seu login.'); window.location.href = ' " . url() .  " ' </script>";
        endif;
    }
    
}

