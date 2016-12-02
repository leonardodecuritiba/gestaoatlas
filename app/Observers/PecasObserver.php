<?php
/**
 * Created by PhpStorm.
 * User: Leonardo
 * Date: 01/12/2016
 * Time: 12:02
 */


namespace App\Observers;

use App\Peca;

class PecasObserver
{
    /**
     * Listen to the User created event.
     *
     * @param  Peca $peca
     * @return void
     */
    public function created(Peca $peca)
    {
        //
    }

    /**
     * Listen to the User deleting event.
     *
     * @param  Peca $peca
     * @return void
     */
    public function deleting(Peca $peca)
    {
        $peca->tabela_preco()->delete();
        $peca->pecas_utilizadas()->delete();
        $peca->peca_kits()->delete();
    }
}