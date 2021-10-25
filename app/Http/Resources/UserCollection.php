<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserCollection extends JsonResource
{
	/**
	 * Transform the resource collection into an array.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	public function toArray($request)
	{
		$data = [
			"id" => $this->id,
			"full_name" => $this->full_name,
			"name" => $this->name,
			"email" => $this->email,
			"last_name" => $this->last_name,
			"profile_image" => $this->profile_image,
			"roles"=>$this->roles
		];

		return $data;
		// return parent::toArray($request);
	}
}
