<?php

namespace App\Controller;

use Cake\Event\Event;
use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\Validation\Validation;

/*
 * Chamged by Ashok
 */

class UserController extends AppController {

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        // Allow users to register and logout.
        // You should not add the "login" action to allow list. Doing so would
        // cause problems with normal functioning of AuthComponent.
        $this->Auth->allow(['user', 'index']);
    }

    public function index() {
        
        $data = $_POST;
        
        echo "imran khan";die("SDFSDFSD");
        //return $this->redirect(["action" => "login"]);
    }

  
}
