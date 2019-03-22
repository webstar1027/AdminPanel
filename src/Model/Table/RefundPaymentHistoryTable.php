<?php
namespace App\Model\Table;
use Cake\ORM\Table;


class RefundPaymentHistoryTable extends Table
{
	public function initialize(array $config)
	{
		$this->addBehavior("Timestamp");
		//$this->primaryKey('mp_id');
		$this->belongsTo("GymMember");
	        $this->belongsTo("RefundPaymentHistory",["foreignKey"=>"member_id"]);
               
	}
}