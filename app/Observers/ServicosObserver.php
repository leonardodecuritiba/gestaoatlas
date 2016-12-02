<?php
/**
 * Created by PhpStorm.
 * User: Leonardo
 * Date: 01/12/2016
 * Time: 12:02
 */


namespace App\Observers;

use App\Servico;

class ServicosObserver
{
    /**
     * Listen to the User created event.
     *
     * @param  Servico $servico
     * @return void
     */
    public function created(Servico $servico)
    {
        //
    }

    /**
     * Listen to the User deleting event.
     *
     * @param  Servico $servico
     * @return void
     */
    public function deleting(Servico $servico)
    {
        $servico->tabela_preco()->delete();
        $servico->servico_prestados()->delete();
    }
}