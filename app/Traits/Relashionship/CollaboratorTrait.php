<?php

namespace App\Traits\Relashionship;

use App\Colaborador;

trait CollaboratorTrait {


	public function getCollaboratorName()
	{
		return $this->collaborator->getName();
	}

	public function getCollaboratorShortName()
	{
		return $this->collaborator->getShortName();
	}

	public function getCollaboratorNameDocument()
	{
		return $this->collaborator->getNameDocument();
	}

	public function collaborator()
	{
		return $this->belongsTo(Colaborador::class);
	}


}