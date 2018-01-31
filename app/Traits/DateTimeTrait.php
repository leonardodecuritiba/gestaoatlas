<?php

namespace App\Traits;

use App\Helpers\DataHelper;

trait DateTimeTrait {

	public function getCreatedAtTime()
	{
		return strtotime( $this->attributes['created_at'] );
	}

	public function getCreatedAtFormatted()
	{
		return DataHelper::getPrettyDateTime( $this->attributes['created_at'] );
	}


	public function getConfirmatedAtTime()
	{
		return strtotime( $this->attributes['confirmated_at'] );
	}

	public function getConfirmatedAtFormatted()
	{
		return DataHelper::getPrettyDateTime( $this->attributes['confirmated_at'] );
	}


	public function getClosedAtTime()
	{
		return strtotime( $this->attributes['closed_at'] );
	}

	public function getClosedAtFormatted()
	{
		return DataHelper::getPrettyDateTime( $this->attributes['closed_at'] );
	}

}