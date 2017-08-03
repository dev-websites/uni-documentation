<?php

    /**
     * -------------------------------------------------------------------------
     * Web Routes
     * ------------------------------------------------- -----------------------
     *
     * Aqui é onde você pode registrar rotas da web para sua aplicação.
     */

     
     $simple->get('/', 'Index@view');
     
     $simple->get('/notificacoes', 'Notificacao@view');
     $simple->get('/home', 'Home@view');
    
     $simple->get('/cadastro/aluno', 'Cadastro@view_aluno');
     $simple->get('/cadastro/arquivo', 'Cadastro@view_arquivo');
     $simple->get('/deletar/aluno', 'cadastro@view_deletar');
     $simple->get('/cadastro/solicitacao', 'Cadastro@view_solicitacao');
     $simple->get('/cadastro/admin', 'Cadastro@view_admin');
     
     $simple->get('/solicitacao', 'Solicitacao@view');
     $simple->get('/lista/[:str]?/[:filtro]?', 'Lista@view');
     
     $simple->get('/logout', 'Auth@logout');
     
     $simple->post('/cadastro/admin', 'Cadastro@salvar_admin');
     $simple->post('/home', 'Home@enviar');
     $simple->post('/', 'Auth@login');
     $simple->post('/cadastro/aluno', 'Cadastro@salvar_aluno');
     $simple->post('/cadastro/arquivo', 'Cadastro@salvar_arquivo');
     $simple->post('/deletar/aluno', 'Cadastro@deletar_arquivo');
     $simple->post('/cadastro/solicitacao', 'Cadastro@salvar_solicitacao');
     $simple->post('/notificacoes', 'Notificacao@leitura');
     
     $simple->get('/deletar/[i:id]/[:token]', 'Lista@deletar');
     
    /**
     * -------------------------------------------------------------------------
     * Web Route 404
     * -------------------------------------------------------------------------
     * 
     * A página de erro esta sempre sendo solicitada, então para evitar erros,
     * no final de qualquer requisição de páginas você deve usar o exit()
     */
     
     
     $simple->get('*', 'Erro@view');


