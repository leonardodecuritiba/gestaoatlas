<?php

namespace App\Helpers;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DataHelper
{
    // ******************** FUNCTIONS ******************************
    static public function getReal2Float($value)
    {
        return floatval(str_replace(',','.',str_replace('.','',$value)));
    }
    static public function getFloat2Real($value)
    {
        return number_format($value,2,',','.');
    }

    static public function getFloat2Percent($value)
    {
        return number_format($value, 2, ',', '.');
    }

    static public function getPrettyDateTime($value)
    {
        return ($value!=NULL)?Carbon::createFromFormat('Y-m-d H:i:s', $value)->format('H:i - d/m/Y'):$value;
    }

    static public function getPrettyDate($value)
    {
        return ($value!=NULL)?Carbon::createFromFormat('Y-m-d', $value)->format('d/m/Y'):$value;
    }

    static public function setDate($value)
    {
        return (($value != NULL) && ($value != '')) ? Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') : NULL;
    }

    static public function getOnlyNumbers($value)
    {
        return ($value != NULL) ? preg_replace("/[^0-9]/", "", $value) : $value;
    }

    static public function getOnlyNumbersLetters($value)
    {
        return ($value != NULL) ? preg_replace("/[^a-zA-Z0-9-]/", "", $value) : $value;
    }

    static public function getShortName($value)
    {
        $value = explode(' ', $value);
        return (count($value) > 1) ? ($value[0] . " " . end($value)) : $value[0];
    }

    static public function mask($val, $mask)
    {
        if ($val != NULL || $val != "") {
            $maskared = '';
            $k = 0;
            for ($i = 0; $i <= strlen($mask) - 1; $i++) {
                if ($mask[$i] == '#') {
                    if (isset($val[$k])) $maskared .= $val[$k++];
                } else {
                    if (isset($mask[$i])) $maskared .= $mask[$i];
                }
            }
        } else {
            $maskared = NULL;
        }
        return $maskared;
    }

    static public function storePriceTable($id, $dados, $Tabelas_preco)
    {
        $valor = self::getPercent2Float($dados['valor']);
        $margens = $dados['margens'];
        $margem_minimos = $dados['margem_minimo'];

        foreach ($Tabelas_preco as $tabela_preco) {
            $margem = self::getPercent2Float($margens[$tabela_preco->idtabela_preco]);
            $margem_minimo = self::getPercent2Float($margem_minimos[$tabela_preco->idtabela_preco]);
            $data[] = [
                'idtabela_preco' => $tabela_preco->idtabela_preco,
                key($id) => $id[key($id)],
                'preco' => $valor + ($valor * $margem) / 100,
                'margem' => $margem,
                'preco_minimo' => $valor + ($valor * $margem_minimo) / 100,
                'margem_minimo' => $margem_minimo,
            ];
        }
        return $data;
    }


    // CRIAÇÃO/ATUALIZAÇÃO DAS TABELAS DE PREÇOS

    static public function getPercent2Float($value)
    {
        return floatval(str_replace(',', '.', $value));
    }

    static public function updatePriceTable($dados, $Tabelas_preco)
    {
        $valor = $dados['valor'];
        $margens = $dados['margens'];
        $margem_minimos = $dados['margem_minimo'];

        foreach ($Tabelas_preco as $tabela_preco) {
            $margem = DataHelper::getPercent2Float($margens[$tabela_preco->idtabela_preco]);
            $margem_minimo = DataHelper::getPercent2Float($margem_minimos[$tabela_preco->idtabela_preco]);

            $dataUpd = [
                'preco' => $valor + ($valor * $margem) / 100,
                'margem' => $margem,
                'preco_minimo' => $valor + ($valor * $margem_minimo) / 100,
                'margem_minimo' => $margem_minimo,
            ];
            $tabela_preco->update($dataUpd);
        }
    }
}
