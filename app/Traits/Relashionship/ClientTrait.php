<?php

namespace App\Traits\Relashionship;

use App\Cliente;

trait ClientTrait {


	public function getClientName()
	{
		return $this->client->getName();
	}

	public function getClientShortName()
	{
		return $this->client->getShortName();
	}

	public function client()
	{
		return $this->belongsTo(Cliente::class);
	}


}