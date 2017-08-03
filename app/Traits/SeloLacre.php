<?php

namespace App\Traits;

use App\Helpers\DataHelper;

trait SeloLacre
{
    static public function set_used($id)
    {
        $Data = self::find($id);
        return $Data->update(['used' => 1]);
    }

    public function getNomeTecnico()
    {
        return $this->tecnico->getNome();
    }

    public function extorna()
    {
        if ($this->externo == 1) {
            $this->forceDelete();
        } else {
            $this->used = 0;
            $this->save();
        }
        return;
    }

    public function repassaTecnico($idtecnico)
    {
        $this->idtecnico = $idtecnico;
        return $this->save();
    }

    public function getStatusText()
    {
        return ($this->getAttribute('used')) ? 'Usado' : 'Disponível';
    }

    public function getStatusColor()
    {
        return ($this->getAttribute('used')) ? 'danger' : 'success';
    }
}