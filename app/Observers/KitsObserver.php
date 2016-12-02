<?php
/**
 * Created by PhpStorm.
 * User: Leonardo
 * Date: 01/12/2016
 * Time: 12:02
 */


namespace App\Observers;

use App\Kit;

class KitsObserver
{
    /**
     * Listen to the User created event.
     *
     * @param  Kit $kit
     * @return void
     */
    public function created(Kit $kit)
    {
        //
    }

    /**
     * Listen to the User deleting event.
     *
     * @param  Kit $kit
     * @return void
     */
    public function deleting(Kit $kit)
    {
        $kit->pecas_kit()->delete();
        $kit->tabela_preco()->delete();
        $kit->kits_utilizados()->delete();
    }
}