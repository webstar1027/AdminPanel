<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\Validation\Validator;

Class PheramorUserTable extends Table{
	public function initialize(array $config)
	{
             
	     $this->BelongsTo("PheramorUserProfile");
             $this->hasMany("PheramorUserProfile",["foreignKey"=>"user_id"]);
             $this->BelongsTo("PheramorRace"); 
             $this->BelongsTo("PheramorBodyType"); 
             $this->BelongsTo("PheramorOrientation"); 
             $this->BelongsTo("PheramorReligion"); 
             $this->BelongsTo("PheramorPayment"); 
             $this->BelongsTo("PheramorProductPayment"); 
             $this->BelongsTo("HoustonZipcode"); 
	}
	
}