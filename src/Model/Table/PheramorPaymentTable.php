<?php
namespace App\Model\Table;
use Cake\ORM\Table;


class PheramorPaymentTable extends Table
{
	public function initialize(array $config)
	{
		$this->addBehavior("Timestamp");
		$this->primaryKey('id');
		$this->belongsTo("PheramorUser");
		$this->belongsTo("PheramorSubscription",["foreignKey"=>"subscription_id"]);
		$this->belongsTo("PheramorPayment",["foreignKey"=>"subscription_id"]);
		$this->belongsTo("PheramorRefundPayment");
		$this->belongsTo("PheramorSubscriptionCategory");
		$this->belongsTo("PheramorUser",["foreignKey"=>"user_id"]);
                $this->belongsTo("PheramorRefundPayment",["foreignKey"=>"user_id"]);
                $this->belongsTo("PheramorPaymentHistory",["foreignKey"=>"payment_id"]);
                
               // $this->belongsTo("MembershipPaymentHistory",["foreignKey"=>"mp_id"]);
	}
}