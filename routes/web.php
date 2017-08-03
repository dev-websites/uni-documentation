<?php

    /**
     * -------------------------------------------------------------------------
     * Web Routes
     * ------------------------------------------------- -----------------------
     *
     * Aqui é onde você pode registrar rotas da web para sua aplicação.
     */

     
     $simple->get('/', 'IndexController@view');
     
     $simple->get('/notificacoes', 'NotificacaoController@view');
     $simple->get('/home', 'HomeController@view');
    
     $simple->get('/cadastro/aluno', 'CadastroController@view_aluno');
     $simple->get('/cadastro/arquivo', 'CadastroController@view_arquivo');
     $simple->get('/deletar/aluno', 'cadastroController@view_deletar');
     $simple->get('/cadastro/solicitacao', 'CadastroController@view_solicitacao');
     $simple->get('/cadastro/admin', 'CadastroController@view_admin');
     
     $simple->get('/solicitacao', 'SolicitacaoController@view');
     $simple->get('/lista/[:str]?/[:filtro]?', 'ListaController@view');
     
     $simple->get('/logout', 'AuthController@logout');
     
     $simple->post('/cadastro/admin', 'CadastroController@salvar_admin');
     $simple->post('/home', 'HomeController@enviar');
     $simple->post('/', 'AuthController@login');
     $simple->post('/cadastro/aluno', 'CadastroController@salvar_aluno');
     $simple->post('/cadastro/arquivo', 'CadastroController@salvar_arquivo');
     $simple->post('/deletar/aluno', 'CadastroController@deletar_arquivo');
     $simple->post('/cadastro/solicitacao', 'CadastroController@salvar_solicitacao');
     $simple->post('/notificacoes', 'NotificacaoController@leitura');
     
     $simple->get('/deletar/[i:id]/[:token]', 'ListaController@deletar');
     
    /**
     * -------------------------------------------------------------------------
     * Web Route 404
     * -------------------------------------------------------------------------
     * 
     * A página de erro esta sempre sendo solicitada, então para evitar erros,
     * no final de qualquer requisição de páginas você deve usar o exit()
     */
     
     
     $simple->get('*', 'ErroController@view');


