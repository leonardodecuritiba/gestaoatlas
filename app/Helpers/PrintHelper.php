<?php

namespace App\Helpers;

use App\AparelhoManutencao;
use App\Equipamento;
use App\Instrumento;
use App\OrdemServico;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class PrintHelper
{
    private $linha_xls;
    private $data;
    private $insumos;
    private $outros_custos;
    private $OrdemServico;

    // ******************** FUNCTIONS ******************************
    public function printOS($idordem_servico)
    {

//        incluir no cabeçalho ou rodapé seguinte observaçao: .
        $this->OrdemServico = OrdemServico::find($idordem_servico);
        $Cliente = $this->OrdemServico->cliente;
        $filename = 'OrdemServico_' . $this->OrdemServico->idordem_servico . '_' . Carbon::now()->format('H-i_d-m-Y');

        $atlas = array(
            'endereco' => 'Rua Triunfo, 400',
            'bairro' => 'Santa Cruz',
            'cidade' => 'Ribeirão Preto',
            'cep' => '14020-670',
            'cnpj' => '10.555.180/0001-21',
            'razao_social' => 'MACEDO AUTOMAÇAO COMERCIAL LTDA',
            'ie' => '797.146.934.117',
            'n_autorizacao' => '10002180',
            'fone' => '(16)3011-8448',
            'email' => 'os@atlastecnologia.com.br');
        $empresa = array(
            'nome' => 'ORDEM DE SERVIÇO - #' . $this->OrdemServico->idordem_servico,
            'descricao' => 'Manutenção e venda de equipamentos de automação comercial',
            'dados' => $atlas,
            'logo' => public_path('uploads/institucional/logo_atlas.png'),
        );

        $aviso_txt = [
            [
                'ASSINATURA:',
                'O CLIENTE CONFIRMA A EXECUÇÃO DOS SERVIÇOS E TROCA DE PEÇAS ACIMA CITADOS, E TAMBÉM APROVA OS PREÇOS COBRADOS.'],
            [
                'EQUIPAMENTOS DEIXADOS POR CLIENTES NA EMPRESA:',
                'O CLIENTE AUTORIZA PRÉVIA E EXPRESSAMANTE UMA VEZ QUE ORÇAMENTOS NÃO FOREM APROVADOS QUE INSTRUMENTOS OU EQUIPAMANTOS NÃO ',
                'RETIRADOS DAS DEPENDÊCIAS DA EMPRESA NO PRAZO DE 90 DIAS DA ASSINATURA DESSA ORDEM SERVIÇO SEJAM DESCARTADOS PARA O LIXO OU SUCATA.'
            ]
        ];

        if ($this->OrdemServico->status() == 0) {
            $aviso_txt[] = ['Ordem serviço não finalizada'];
        }

        if ($Cliente->is_pjuridica()) {
            //empresa
            $Pessoa_juridica = $Cliente->pessoa_juridica;
            $Contato = $Cliente->contato;

            $dados_cliente = [
                array(
                    'Cliente / Razão Social:', $Pessoa_juridica->razao_social,
                    'Fantasia:', $Pessoa_juridica->nome_fantasia,
                    'HR / DATA I', $this->OrdemServico->created_at
                ),
                array(
                    'CNPJ:', $Pessoa_juridica->cnpj,
                    'I.E:', $Pessoa_juridica->ie,
                    'DATA / HR F', $this->OrdemServico->fechamento
                ),
                array(
                    'Endereço:', $Contato->getRua(),
                    'CEP: ' . $Contato->cep, 'UF: ' . $Contato->estado,
                    'Cidade: ' . $Contato->cidade
                ),
                array(
                    'Telefone:', $Contato->telefone,
                    'Contato:', $Cliente->nome_responsavel
                ),
                array(
                    'Email:', $Cliente->email_nota,
                    'Nº Chamado Sist. Cliente:', $this->OrdemServico->numero_chamado
                ),
            ];
        } else {
            $PessoaFisica = $Cliente->pessoa_fisica;
            $Contato = $Cliente->contato;

            $dados_cliente = [
                array(
                    'Cliente / Razão Social:', $Cliente->nome_responsavel,
                    'Fantasia:', '-',
                    'HR / DATA I', $this->OrdemServico->created_at
                ),
                array(
                    'CPF:', $PessoaFisica->cpf,
                    'I.E:', '-',
                    'DATA / HR F', $this->OrdemServico->fechamento
                ),
                array(
                    'Endereço:', $Contato->getRua(),
                    'CEP: ' . $Contato->cep, 'UF: ' . $Contato->estado,
                    'Cidade: ' . $Contato->cidade
                ),
                array(
                    'Telefone:', $Contato->telefone,
                    'Contato:', $Cliente->nome_responsavel
                ),
                array(
                    'Email:', $Cliente->email_nota,
                    'Nº Chamado Sist. Cliente:', $this->OrdemServico->numero_chamado
                ),
            ];
        }
        $default_size = '13';
        $font = [
            'nome' => array(
                'family' => 'Bookman Old Style',
                'size' => '16',
            ),
            'descricao' => array(
                'size' => $default_size,
                'bold' => true
            ),
            'endereco' => array(
                'size' => $default_size,
            ),
            'quebra' => array(
                'size' => $default_size,
                'bold' => true
            ),
            'negrito' => array(
                'size' => $default_size,
                'bold' => true
            ),
            'normal' => array(
                'size' => $default_size,
            )
        ];


        $this->data = [
            'empresa' => $empresa,
            'dados_cliente' => $dados_cliente,
            'aviso_txt' => $aviso_txt,
            'fonts' => $font
        ];

        Excel::create($filename, function ($excel) {

//            dd($data['empresa']['cabecalho']);
            $excel->sheet('Sheetname', function ($sheet) {
                $sheet->setPageMargin(0.25);

                $cabecalho = $this->data['empresa']['dados'];

                $sheet->mergeCells('A1:C1');
                $sheet->mergeCells('A2:C2');

                $sheet->cell('A1', function ($cell) {
                    // manipulate the cell
                    $cell->setValue(strtoupper($this->data['empresa']['nome']));
                    $cell->setFont($this->data['fonts']['nome']);
                    $cell->setFontFamily('Bookman Old Style');
                });
                $sheet->cell('A2', function ($cell) {
                    // manipulate the cell
                    $cell->setValue($this->data['empresa']['descricao']);
                    $cell->setFont($this->data['fonts']['descricao']);
                });

                $sheet->rows(array(
                    array($cabecalho['razao_social'] . ' / CNPJ: ' . $cabecalho['cnpj']),
                    array('I.E: ' . $cabecalho['ie']),
                    array('N° de Autorização: ' . $cabecalho['n_autorizacao']),
                    array($cabecalho['endereco'] . ' - ' . $cabecalho['bairro'] . ' - CEP: ' . $cabecalho['bairro']),
                    array('Fone: ' . $cabecalho['fone']),
                    array('E-mail: ' . $cabecalho['email']),
                ));
                $sheet->mergeCells('A3:C3');
                $sheet->mergeCells('A4:C4');
                $sheet->mergeCells('A5:C5');
                $sheet->mergeCells('A6:C6');
                $sheet->mergeCells('A7:C7');
                $sheet->mergeCells('A8:C8');
                $sheet->cells('A3:B8', function ($cells) {
                    // manipulate the range of cells
                    $cells->setFont($this->data['fonts']['endereco']);
                });

                $sheet->mergeCells('D1:G8');
                $objDrawing = new \PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($this->data['empresa']['logo']); //your image path
                $objDrawing->setCoordinates('D1');
                $objDrawing->setWorksheet($sheet);


                //QUEBRA -------------------------------------------------------------------------------
                //********************************************************************************************//
                //********************************************************************************************//
                $this->linha_xls = 9;
                $info = ['Ordem Serviço n° ' . $this->OrdemServico->idordem_servico];
                $sheet->mergeCells('A' . $this->linha_xls . ':G' . $this->linha_xls);
                $sheet->row($this->linha_xls, function ($row) {
                    // call cell manipulation methods
                    $row->setBackground('#d9d9d9');
                    $row->setAlignment('center');
                    $row->setFont($this->data['fonts']['quebra']);
                });
                $sheet->row($this->linha_xls, $info);
                $this->linha_xls++;
                //\QUEBRA ------------------------------------------

                //CABEÇALHO DADOS CLIENTE
                $sheet->rows($this->data['dados_cliente']);
                $this->linha_xls += 6;

                //********************************************************************************************//
                //INSTRUMENTOS ------------------------------------------
                $AparelhosManutencao = $this->OrdemServico->aparelho_manutencaos;
                $this->insumos = NULL;
                foreach ($AparelhosManutencao as $Aparelho) {
                    if ($Aparelho->idinstrumento == NULL) {
                        $sheet = $this->setEquipamento($Aparelho, $sheet);
                    } else {
                        $sheet = $this->setInstrumento($Aparelho, $sheet);
                    }

                    //************************** SERVIÇOS ********************************************************//
                    $sheet = $this->setServico($sheet, $Aparelho);
                    //************************** PEÇAS ***********************************************************//
                    $sheet = $this->setPeca($sheet, $Aparelho);
                    //************************** KITS ************************************************************//
                    $sheet = $this->setKit($sheet, $Aparelho);
                }

                //********************************************************************************************//
                //************************** FECHAMENTO ******************************************************//
                $Fechamento = json_decode($this->OrdemServico->getValores());
                $this->insumos['total_servicos'] = $Fechamento->valor_total_servicos;
                $this->insumos['total_pecas'] = $Fechamento->valor_total_pecas;
                $this->insumos['total_kits'] = $Fechamento->valor_total_kits;
                if (isset($Fechamento->valor_desconto)) {
                    $this->insumos['descontos'] = $Fechamento->valor_desconto;
                }
                if (isset($Fechamento->valor_acrescimo)) {
                    $this->insumos['acrescimos'] = $Fechamento->valor_acrescimo;
                }
                $this->insumos['acrescimos'] = $Fechamento->valor_total_kits;
                $sheet = $this->setFechamento($sheet);

                //********************************************************************************************//
                //************************* FECHAMENTO FINAL *********************************************//
                $sheet->row($this->linha_xls, function ($row) {
                    $row->setFontWeight(true);
                });
                $sheet->row($this->linha_xls, ['TOTAL  DA ORDEM SERVIÇO', '', '', '', $Fechamento->valor_final]);
                $this->linha_xls += 2;


                $sheet = self::setCabecalhoCinza($sheet, [
                    'line' => $this->linha_xls,
                    'info' => ['Termos'],
                ]);
                $sheet->mergeCells('A' . $this->linha_xls . ':G' . $this->linha_xls);
                $sheet->row($this->linha_xls, [$this->data['aviso_txt'][0][0]]);
                $this->linha_xls++;
                $sheet->mergeCells('A' . $this->linha_xls . ':G' . $this->linha_xls);
                $sheet->row($this->linha_xls, [$this->data['aviso_txt'][0][1]]);
                $this->linha_xls++;

                $sheet->mergeCells('A' . $this->linha_xls . ':G' . ($this->linha_xls));
                $sheet->row($this->linha_xls, [$this->data['aviso_txt'][1][0]]);
                $this->linha_xls++;
                $sheet->mergeCells('A' . $this->linha_xls . ':G' . ($this->linha_xls));
                $sheet->row($this->linha_xls, [$this->data['aviso_txt'][1][1]]);
                $this->linha_xls++;
                $sheet->mergeCells('A' . $this->linha_xls . ':G' . ($this->linha_xls));
                $sheet->row($this->linha_xls, [$this->data['aviso_txt'][1][2]]);
                $this->linha_xls += 2;


                $sheet = self::setCabecalhoCinza($sheet, [
                    'line' => $this->linha_xls,
                    'info' => ['TÉCNICO'],
                ]);
                $sheet->row($this->linha_xls, [
                    'Nome:', $this->OrdemServico->colaborador->nome,
                    'CPF:', $this->OrdemServico->colaborador->cpf]);
                $this->linha_xls += 2;
                $sheet->mergeCells('B' . $this->linha_xls . ':G' . ($this->linha_xls));
                $sheet->row($this->linha_xls, ['ASSINATURA :', '________________________________________________________________________________']);
                $this->linha_xls += 2;

                $sheet = self::setCabecalhoCinza($sheet, [
                    'line' => $this->linha_xls,
                    'info' => ['RESPONSÁVEL DO ESTABELECIMENTO'],
                ]);
                $sheet->row($this->linha_xls, [
                    'Nome:', $this->OrdemServico->responsavel,
                    'CPF:', $this->OrdemServico->responsavel_cpf]);
                $this->linha_xls += 2;
                $sheet->mergeCells('B' . $this->linha_xls . ':G' . ($this->linha_xls));
                $sheet->row($this->linha_xls, ['ASSINATURA :', '________________________________________________________________________________']);
                $this->linha_xls += 2;

                if (isset($this->data['aviso_txt'][2])) {
                    $this->linha_xls++;
                    $sheet->mergeCells('A' . $this->linha_xls . ':G' . ($this->linha_xls));
                    $sheet->row($this->linha_xls, function ($row) {
                        // call cell manipulation methods
                        $row->setBackground('#d9d9d9');
                        $row->setAlignment('center');
                        $row->setFont($this->data['fonts']['quebra']);
                    });
                    $sheet->row($this->linha_xls, $this->data['aviso_txt'][2]);

                }
            });

        })->export('xls');
        return 'imprimir';
    }

    private function setEquipamento(AparelhoManutencao $aparelhoManutencao, $sheet)
    {
        $Equipamento = $aparelhoManutencao->equipamento;
        $dados_equipamento = [
            array(
                'Marca: ' . $Equipamento->marca->descricao,
                'Modelo: ' . $Equipamento->modelo,
                'N° de Série: ' . $Equipamento->numero_serie,
            )
        ];

        $defeitos_solucao = [
            array(
                'Defeito:', $aparelhoManutencao->defeito,
                '',
                'Solução:', $aparelhoManutencao->solucao
            )
        ];
        //LINHA ------------------------------------------
        $sheet = self::setCabecalhoCinza($sheet, [
            'line' => $this->linha_xls,
            'info' => ['Equipamento (#' . $Equipamento->idequipamento . ') - ' . $Equipamento->descricao]
        ]);
        //CABEÇALHO ------------------------------------------
        $sheet->rows($dados_equipamento);
        $sheet->rows($defeitos_solucao);

        $this->linha_xls += 3;
        $sheet->mergeCells('B' . $this->linha_xls . ':C' . $this->linha_xls);
        $sheet->mergeCells('E' . $this->linha_xls . ':G' . $this->linha_xls);
        $this->linha_xls += 1;
        return $sheet;
    }

    private function setCabecalhoCinza($sheet, $dados)
    {
        $this->linha_xls += 1;
        $linha = $dados['line'];
        //LINHA ------------------------------------------
        $sheet->mergeCells('A' . $linha . ':G' . $linha);
        $sheet->row($linha, function ($row) {
            $row->setBackground('#d9d9d9');
            $row->setAlignment('center');
            $row->setFont($this->data['fonts']['quebra']);
        });
        $sheet->row($linha, $dados['info']);
        return $sheet;
    }

    private function setInstrumento(AparelhoManutencao $aparelhoManutencao, $sheet)
    {
        $Instrumento = $aparelhoManutencao->instrumento;
        $dados_instrumento = [
            array(
                'Marca: ' . $Instrumento->marca->descricao,
                'Modelo: ' . $Instrumento->modelo,
                'N° de Série: ' . $Instrumento->numero_serie,
                'Patrimônio: ' . $Instrumento->patrimonio,
                'Ano: ' . $Instrumento->ano,
                'Inventário: ' . $Instrumento->inventario
            ),
            array(
                'Portaria: ' . $Instrumento->portaria,
                'Capacidade: ' . $Instrumento->capacidade,
                'Divisão: ' . $Instrumento->divisao,
                'Setor: ' . $Instrumento->setor,
                'IP: ' . $Instrumento->ip,
                'Endereço: ' . $Instrumento->endereco
            )
        ];
        if ($Instrumento->has_selo_instrumentos()) {
            $selo = $Instrumento->selo_afixado()->numeracao;
        }
        if ($Instrumento->has_lacres_instrumentos()) {
            $lacre = $Instrumento->lacres_afixados_valores();
        }

        $selo_lacre = [
            array(
//                            'Selo retirado: '.$Instrumento->selo_afixado()->numeracao,
                'Selo Afixado: ', isset($selo) ? $selo : '-',
//                            'Lacres Retirados: '.$Instrumento->selo_afixado()->numeracao,
                'Lacres Afixados: ', isset($lacre) ? $lacre : '-'
            )
        ];
        $defeitos_solucao = [
            array(
                'Defeito:', $aparelhoManutencao->defeito,
                '',
                'Solução:', $aparelhoManutencao->solucao
            )
        ];
        //LINHA ------------------------------------------
        $sheet = self::setCabecalhoCinza($sheet, [
            'line' => $this->linha_xls,
            'info' => ['Instrumento (#' . $Instrumento->idinstrumento . ') - ' . $Instrumento->descricao]
        ]);
        //CABEÇALHO ------------------------------------------
        $this->linha_xls++;
        $sheet->rows($dados_instrumento);
        $sheet->rows($selo_lacre);
        $sheet->rows($defeitos_solucao);

        $this->linha_xls += 4;
        $sheet->mergeCells('B' . $this->linha_xls . ':C' . $this->linha_xls);
        $sheet->mergeCells('E' . $this->linha_xls . ':G' . $this->linha_xls);
        $this->linha_xls += 1;
        return $sheet;
    }

    private function setServico($sheet, $aparelho)
    {
        if ($aparelho->has_servico_prestados()) {
            foreach ($aparelho->servico_prestados as $Servico_prestados) {
                $Servico = $Servico_prestados->servico;
//                            $tabela_preco   = $Peca->tabela_cliente($OrdemServico->cliente->idtabela_preco);
                $servicos[] = [
                    $Servico->idservico,
                    $Servico->descricao,
                    'R$ ' . $Servico_prestados->valor,
                    $Servico_prestados->quantidade,
                    $Servico_prestados->valor_total_real()
                ];
            }
            $cabecalho = [
                'line' => $this->linha_xls,
                'info' => ['Serviços'],
                'cabecalho' => ['Codigo', 'Serviço', 'V. un', 'Qtde', 'V. Total'],
                'values' => $servicos,
            ];
            $sheet = self::setData($sheet, $cabecalho);
            $this->linha_xls += count($servicos) + 2;
            $this->insumos['servicos'][] = $servicos;
            $this->linha_xls += 1;
        }
        return $sheet;
    }

    private function setData($sheet, $dados)
    {
        //LINHA ------------------------------------------
        $sheet = self::setCabecalhoCinza($sheet, $dados);
        //CABEÇALHO ------------------------------------------
        $linha = $dados['line'] + 1;
        $sheet->row($linha, function ($row) {
            $row->setFontWeight(true);
        });
        $sheet->row($linha, $dados['cabecalho']);

        // DADOS ------------------------------------------
        $sheet->rows($dados['values']);
        return $sheet;
    }

    private function setPeca($sheet, $aparelho)
    {
        if ($aparelho->has_pecas_utilizadas()) {
            foreach ($aparelho->pecas_utilizadas as $Peca_utilizada) {
                $Peca = $Peca_utilizada->peca;
//                            $tabela_preco   = $Peca->tabela_cliente($OrdemServico->cliente->idtabela_preco);
                $pecas[] = [
                    $Peca->idpeca,
                    $Peca->descricao,
                    'R$ ' . $Peca_utilizada->valor,
                    $Peca_utilizada->quantidade,
                    $Peca_utilizada->valor_total_real(),
                    '-',
                    '-'
                ];
            }
            $cabecalho = [
                'line' => $this->linha_xls,
                'info' => ['Peças'],
                'cabecalho' => ['Codigo', 'Peça', 'V. un', 'Qtde', 'V. Total', 'Garantia', 'Garantia Negada'],
                'values' => $pecas,
            ];
            $sheet = self::setData($sheet, $cabecalho);
            $this->linha_xls += count($pecas) + 2;
            $this->insumos['pecas'][] = $pecas;
            $this->linha_xls += 1;
        }
        return $sheet;
    }

    private function setKit($sheet, $aparelho)
    {
        if ($aparelho->has_kits_utilizados()) {
            foreach ($aparelho->kits_utilizados as $Kit_utilizado) {
                $Kit = $Kit_utilizado->kit;
//                            $tabela_preco   = $Peca->tabela_cliente($OrdemServico->cliente->idtabela_preco);
                $kits[] = [
                    $Kit->idkit,
                    $Kit->descricao,
                    'R$ ' . $Kit_utilizado->valor,
                    $Kit_utilizado->quantidade,
                    $Kit_utilizado->valor_total_real(),
                    '-',
                    '-'
                ];
            }
            $cabecalho = [
                'line' => $this->linha_xls,
                'info' => ['Kits'],
                'cabecalho' => ['Codigo', 'Peça', 'V. un', 'Qtde', 'V. Total', 'Garantia', 'Garantia Negada'],
                'values' => $kits,
            ];
            $sheet = self::setData($sheet, $cabecalho);
            $this->linha_xls += count($kits) + 2;
            $this->insumos['kits'][] = $kits;
            $this->linha_xls += 1;
        }
        return $sheet;
    }

    private function setFechamento($sheet)
    {
        $sheet = self::setCabecalhoCinza($sheet, [
            'line' => $this->linha_xls,
            'info' => ['Fechamento de Valores'],
        ]);

        //************************* SERVIÇOS *******************************************************//
        if (isset($this->insumos['servicos'])) {
            foreach ($this->insumos['servicos'] as $insumo) {
                foreach ($insumo as $servico) {
                    $servicos[] = $servico;
                }
            }
            $this->linha_xls += 2;
            $cabecalho = [
                'line' => $this->linha_xls,
                'info' => ['Serviços'],
                'cabecalho' => ['Codigo', 'Kit', 'V. un', 'Qtde', 'V. Total'],
                'values' => $servicos,
            ];
            $sheet = self::setData($sheet, $cabecalho);
            $this->linha_xls += count($servicos) + 2;

            //total
            $sheet->cell('E' . $this->linha_xls, function ($cell) {
                $cell->setFontWeight(true);
                $cell->setValue($this->insumos['total_servicos']);
            });
        }
        //************************* PEÇAS ***********************************************************//
        if (isset($this->insumos['pecas'])) {
            foreach ($this->insumos['pecas'] as $insumo) {
                foreach ($insumo as $peca) {
                    $pecas[] = $peca;
                }
            }
            $this->linha_xls += 2;
            $cabecalho = [
                'line' => $this->linha_xls,
                'info' => ['Peças'],
                'cabecalho' => ['Codigo', 'Peça', 'V. un', 'Qtde', 'V. Total', 'Garantia', 'Garantia Negada'],
                'values' => $pecas,
            ];
            $sheet = self::setData($sheet, $cabecalho);
            $this->linha_xls += count($pecas) + 2;

            //total
            $sheet->cell('E' . $this->linha_xls, function ($cell) {
                $cell->setFontWeight(true);
                $cell->setValue($this->insumos['total_pecas']);
            });
        }
        //************************* KITS ***********************************************************//
        if (isset($this->insumos['kits'])) {
            foreach ($this->insumos['kits'] as $insumo) {
                foreach ($insumo as $kit) {
                    $kits[] = $kit;
                }
            }
            $this->linha_xls += 2;
            $cabecalho = [
                'line' => $this->linha_xls,
                'info' => ['Kits'],
                'cabecalho' => ['Codigo', 'Peça', 'V. un', 'Qtde', 'V. Total', 'Garantia', 'Garantia Negada'],
                'values' => $kits,
            ];
            $sheet = self::setData($sheet, $cabecalho);
            $this->linha_xls += count($this->insumos['kits']) + 2;

            //total
            $sheet->cell('E' . $this->linha_xls, function ($cell) {
                $cell->setFontWeight(true);
                $cell->setValue($this->insumos['total_kits']);
            });
        }
        //************************* OUTROS *********************************************************//
        $dados_fechamento = [
            array('Deslocamento', '', '', '', 'R$ ' . $this->OrdemServico->custos_deslocamento),
            array('Pedagios', '', '', '', 'R$ ' . $this->OrdemServico->pedagios),
            array('Outros Custos', '', '', '', 'R$ ' . $this->OrdemServico->outros_custos),
        ];
        if (isset($this->insumos['descontos'])) {
            $dados_fechamento[] = array('Descontos', '', '', '', $this->insumos['descontos']);
        }
        if (isset($this->insumos['acrescimos'])) {
            $dados_fechamento[] = array('Acréscimos', '', '', '', $this->insumos['acrescimos']);
        }

        $this->linha_xls += 2;
        $cabecalho = [
            'line' => $this->linha_xls,
            'info' => ['Outros'],
            'cabecalho' => ['Descrição', '', '', '', 'V. Total'],
            'values' => $dados_fechamento,
        ];
        $sheet = self::setData($sheet, $cabecalho);
        $this->linha_xls += count($dados_fechamento) + 2;
        return $sheet;
    }
}
