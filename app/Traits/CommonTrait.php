<?php

namespace App\Traits;

use App\Helpers\DataHelper;

trait CommonTrait {

	public function getCreatedAtTime() {
		return strtotime( $this->attributes['created_at'] );
	}

	public function getCreatedAtFormatted()
	{
		return DataHelper::getPrettyDateTime( $this->attributes['created_at'] );
	}

	public function getValue2Currency()
	{
		return DataHelper::getFloat2Currency( $this->attributes['value'] );
	}

	public function getConfirmatedAtTime() {
		return strtotime( $this->attributes['confirmated_at'] );
	}

	public function getConfirmatedAtFormatted()
	{
		return DataHelper::getPrettyDateTime( $this->attributes['confirmated_at'] );
	}
}