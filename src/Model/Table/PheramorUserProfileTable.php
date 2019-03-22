<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\Validation\Validator;

Class PheramorUserProfileTable extends Table{
	public function initialize(array $config)
	{
		$this->BelongsTo("PheramorUserProfile");
                $this->BelongsTo("PheramorUser",["foreignKey"=>"user_id"]);
              
	}
	
}