INSERT INTO `pjuridicas` (`idpjuridica`, `cnpj`, `ie`, `razao_social`, `nome_fantasia`, `ativ_economica`, `sit_cad_vigente`, `sit_cad_status`, `data_sit_cad`, `reg_apuracao`, `data_credenciamento`, `ind_obrigatoriedade`, `data_ini_obrigatoriedade`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '71322150003428', '582876210111', 'SAVEGNAGO SUPERMERCADOS LTDA', 'SAVEGNAGO LJ25', 'COMÉRCIO VAREJISTA DE MERCADORIAS EM GERAL, COM PREDOMINÂNCIA DE PRODUTOS ALIMENTÍCIOS - SUPERMERCAD', 'HABILITADO', 'ATIVO', '2012-01-19', 'NORMAL - REGIME PERIÓDICO DE APURAÇÃO', '2012-01-20', 'OBRIGATORIEDADE TOTAL', '2010-04-01', '2016-08-25 17:25:29', '2016-08-25 17:25:29', NULL),
(2, '71322150003932', '111111111111', 'SAVEGNAGO SUPERMERCADOS LTDA', 'SAVEGNAGO CPD', 'COMÉRCIO VAREJISTA DE MERCADORIAS EM GERAL, COM PREDOMINÂNCIA DE PRODUTOS ALIMENTÍCIOS - SUPERMERCAD', 'HABILITADO', 'ATIVO', '1996-07-16', 'NORMAL - REGIME PERIÓDICO DE APURAÇÃO', '2009-09-10', 'OBRIGATORIEDADE TOTAL', '2009-09-01', '2016-08-25 17:39:46', '2016-08-25 17:39:46', NULL),
(3, '71322150000402', '664014461114', 'SAVEGNAGO SUPERMERCADOS LTDA', 'SAVEGNAGO LJ02', 'COMÉRCIO VAREJISTA DE MERCADORIAS EM GERAL, COM PREDOMINÂNCIA DE PRODUTOS ALIMENTÍCIOS - SUPERMERCAD', 'HABILITADO', 'ATIVO', '1980-04-01', 'NORMAL - REGIME PERIÓDICO DE APURAÇÃO', '2010-11-23', 'OBRIGATORIEDADE TOTAL', '2010-04-01', '2016-08-25 17:43:18', '2016-08-25 17:43:18', NULL),
(4, '00000000000000', '111111111111', 'TOLEDO TESTE', 'TOLEDO TESTE', 'CTOLEDO TESTE', 'HABILITADO', 'ATIVO', '1980-04-01', 'NORMAL - REGIME PERIÓDICO DE APURAÇÃO', '2010-11-23', 'OBRIGATORIEDADE TOTAL', '2010-04-01', '2016-08-25 17:43:18', '2016-08-25 17:43:18', NULL);


INSERT INTO `contatos` (`idcontato`, `telefone`, `celular`, `skype`, `cep`, `estado`, `cidade`, `bairro`, `logradouro`, `numero`, `complemento`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '1630118448', '16982630403', 'macedo_automacao@hotmail.com', '14020670', 'sp', 'RIBEIRAO PRETO', 'STA CRUZ', 'Rua Triunfo', '400', 'PREDIO', '2016-09-04 11:49:33', '2016-09-04 11:49:33', NULL),
(2, '1639462088', '16991369010', '', '14170150', 'São Paulo', 'SERTAOZINHO', 'SÃO JOÃO', 'AV NOSSA SRa APARECIDA', '2021', '', '2016-09-04 12:32:51', '2016-09-04 12:32:51', NULL),
(3, '1733423356', '', '', '14700500', 'SP', 'BEBEDOURO', '', 'AVENIDA QUITO STAMATO', '555', '', '2016-09-04 12:20:22', '2016-09-04 12:20:22', NULL),
(4, '1143569000', '1143569000', '', '09851900', 'SP', 'SÃO BERNARDO', '', 'RUA MANUEL CREMONESI', '1', '', '2016-09-04 12:46:38', '2016-09-04 12:46:38', NULL),
(5, '0000000000', '1111111111', '', '00000000', '', '', '', '', '', '', '2016-09-04 12:46:38', '2016-09-04 12:46:38', NULL);
--
-- Dumping data for table `USERS`
--
INSERT INTO `users` (`iduser`, `email`, `password`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'comercial@atlastecnologia.com.br', '$2y$10$s0e5B1fHTLmVyIsNSTQXCeMyyVH2EsarnluIVNNm7HcWuAXb8sVjG', NULL, '2016-09-04 11:49:33', '2016-09-04 11:49:33', NULL);

INSERT INTO `colaboradores` (`idcolaborador`, `idcontato`, `iduser`, `nome`, `cpf`, `rg`, `data_nascimento`, `cnh`, `carteira_trabalho`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, 'WILLIAN', '06920572699', '14079508', '1984-05-26', '2d31398c058b8cb47d653b83dcb94022.jpg', '8c243cd2e49b42b074559badba26d5af.jpg', '2016-09-04 11:49:34', '2016-09-04 11:49:34', NULL);

INSERT INTO `clientes` (`idcliente`, `idcontato`, `idcliente_centro_custo`, `idpjuridica`, `idpfisica`, `idsegmento`, `idtabela_preco`, `idregiao`, `idforma_pagamento`, `idcolaborador_criador`, `idcolaborador_validador`, `validated_at`, `centro_custo`, `email_orcamento`, `email_nota`, `foto`, `limite_credito`, `nome_responsavel`,`distancia`,`pedagios`,`outros_custos`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 2, NULL, 1, NULL, 1, 2, 1, 2, 1, 1, '2016-09-04 12:32:51', 0, 'sonia.felicio@saveganago.com.br', 'sonia.felicio@saveganago.com.br', '6fd1b344f6fd9f3fbeeec09f9c047c54.jpg', 25000.00, 'PC \\ PEDRO \\ SONIA','100','100','100', '2016-09-04 12:32:51', '2016-09-04 12:33:21', NULL);

INSERT INTO `clientes` (`idcliente`, `idcontato`, `idcliente_centro_custo`, `idpjuridica`, `idpfisica`, `idsegmento`, `idtabela_preco`, `idregiao`, `idforma_pagamento`, `idcolaborador_criador`, `idcolaborador_validador`, `validated_at`, `centro_custo`, `email_orcamento`, `email_nota`, `foto`, `limite_credito`, `nome_responsavel`,`distancia`,`pedagios`,`outros_custos`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 3, 1, 2, NULL, 1, 2, 1, 2, 1, 1, '2016-09-04 12:20:22', 1, 'sonia.felicio@saveganago.com.br', 'sonia.felicio@saveganago.com.br', '3403c587543b1677bcb9550de9a28db0.jpg', 0.00, 'SAMUEL',100.00,100.00,100.00, '2016-09-04 12:20:22', '2016-09-04 12:34:02', NULL);

INSERT INTO `fornecedores` (`idfornecedor`, `idcontato`, `idpjuridica`, `idpfisica`, `idsegmento_fornecedor`, `email_orcamento`, `grupo`, `nome_responsavel`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 4, 3, NULL, 1, 'ANTONIO.SANTIAGO@TOLEDOBRASIL.COM.BR', 'PEÇAS E EQUIPAMENTOS', 'SANTIAGO', '2016-09-04 12:46:38', '2016-09-04 12:46:38', NULL),
(2, 5, 4, NULL, 1, 'sem email', '', 'sem nome', '2016-09-04 12:46:38', '2016-09-04 12:46:38', NULL);

INSERT INTO `role_user` (`user_id`, `role_id`) VALUES
(1, 1);