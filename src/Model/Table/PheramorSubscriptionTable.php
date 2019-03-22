<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

Class PheramorSubscriptionTable extends Table {

    public function initialize(array $config) {
        $this->addBehavior('Timestamp');
        $this->belongsTo("PheramorSubscriptionCategory", ["foreignKey" => "subscription_cat_id"]);
      //  $this->belongsTo("MembershipPayment");
       }

    public function validationDefault(Validator $validator) {
        return $validator
                ->notEmpty('membership_label', 'Membership name is required.')
                ->notEmpty('membership_length', 'Membership validity is required.');
    }

}
