<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\Validation\Validator;

Class PheramorProfileTable extends Table{
	public function initialize(array $config)
	{
                $this->BelongsTo("PheramorUser");
		$this->BelongsTo("PheramorUserProfile");
              
	}
	
}