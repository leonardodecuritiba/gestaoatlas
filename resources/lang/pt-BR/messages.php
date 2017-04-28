<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Messages Language Lines
    |--------------------------------------------------------------------------
    | Model - Procedure Type - Message Type
    | Example 1: Fans (S)tore (S)uccess
    | Example 2: Fans (U)pdate (E)rror
    */
    'store_ok' => 'Cadastro solicitado com sucesso. Verifique seu e-mail para confirmá-lo!',
    'validate_ok' => 'Cadastro realizado com sucesso.',
    'contato_ok' => 'Contato enviado com sucesso.',
    'contato_ok' => 'Contato enviado com sucesso.',
    'username' => [
        'sent' => 'Um lembrete de LOGIN foi enviado para o seu e-mail!',
    ],
    'visualization' => [
        'MCREATE' => 'Cadastro do :name',
        'FCREATE' => 'Cadastro da :name',

        'MSHOW' => 'Visualização do :name',
        'FSHOW' => 'Visualização da :name',

        'MDATA' => 'Dados do :name',
        'FDATA' => 'Dados da :name',
    ],
    'crud' => [
        //STORE
        'MSS' => ':name cadastrado!',
        'MSE' => 'Erro ao cadastrar o :name.',
        'FSS' => ':name cadastrada!',
        'FSE' => 'Erro ao cadastrar a :name.',

        //UPDATE
        'MUS' => ':name atualizado!',
        'MUE' => 'Erro ao atualizar o :name!',
        'FUS' => ':name atualizada!',
        'FUE' => 'Erro ao atualizar a :name!',

        //VALIDATE
        'MVS' => ':name validado!',
        'MVE' => 'Erro ao validar o :name!',
        'FVS' => ':name validada!',
        'FVE' => 'Erro ao validar a :name!',

        //DELETE
        'MDS' => ':name removido!',
        'MDE' => 'Erro ao remover o :name!',
        'FDS' => ':name removida!',
        'FDE' => 'Erro ao remover a :name!',

        //GET
        'MGS' => ':name encontrado!',
        'MGE' => 'Erro. Não foi possível encontrar este :name.',
        'FGS' => ':name encontrada!',
        'FGE' => 'Erro. Não foi possível encontrar esta :name.',

        //RESULTS
        'MRE' => 'Nenhum :name foi encontrado',
        'FRE' => 'Nenhuma :name foi encontrada',
        'MRSU' => 'Foi encontrado 1 :name',
        'FRSU' => 'Foi encontrada 1 :name',
        'MRS' => 'Foram encontrados :value :name',
        'FRS' => 'Foram encontradas :value :name',

        //LOGGED
        'MLS' => ':name logado!',
        'MLE' => 'Login/senha inválidos!',
        'MLVE' => 'Este usuário ainda não foi validado! Por favor, clique no link enviado por email para validar sua conta!',

        //UNLOGGED
        'MULS' => ':name deslogado com sucesso!',
    ]

];
