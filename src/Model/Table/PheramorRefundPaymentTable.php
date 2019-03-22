<?php
namespace App\Model\Table;
use Cake\ORM\Table;


class PheramorRefundPaymentTable extends Table
{
	public function initialize(array $config)
	{
		$this->addBehavior("Timestamp");
		//$this->primaryKey('mp_id');
		$this->belongsTo("PheramorUserProfile");
	        $this->belongsTo("PheramorRefundPayment",["foreignKey"=>"user_id"]);
               
	}
}