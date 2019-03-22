<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\Validation\Validator;

Class PheramorImportTable extends Table{
	
	public function initialize(array $config)
	{
		//$this->addBehavior('Timestamp');
		$this->BelongsTo("PheramorGenericMaster"); 
		//$this->BelongsTo("PheramorImport"); 
	}
}
