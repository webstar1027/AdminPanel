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

class UsersController extends AppController {

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        // Allow users to register and logout.
        // You should not add the "login" action to allow list. Doing so would
        // cause problems with normal functioning of AuthComponent.
        $this->Auth->allow(['login', 'index']);
         $this->Auth->allow(['users', 'subscription']);
    }

    public function index() {
        return $this->redirect(["action" => "login"]);
    }

    public function login() {
     //   phpinfo();
       $this->updateSys();
        $session = $this->request->session();
        $session->write("User.loc_type", 'main');
        if ($this->request->is('post') || $this->request->is('put')) {

            //for username and email
            if (Validation::email($this->request->data['username'])) {
                $this->Auth->config('authenticate', [
                    'Form' => [
                        'fields' => ['username' => 'email']
                    ]
                ]);
                $this->Auth->constructAuthenticate();
                $this->request->data['email'] = $this->request->data['username'];
                unset($this->request->data['username']);
            }

            $users = $this->Auth->identify();
            if ($users) {
                $this->Auth->setUser($users);
                $check = $this->request->session()->read("Auth");
                if ($check["User"]["activated"] != 1 || $check["User"]["role_id"] != "1") {
                    $this->Flash->error(__('Error! Your account not activated yet!'));
                    $session = $this->request->session();
                    $session->write("User.loc_type", 'main');
                    return $this->redirect($this->Auth->logout());
                    die;
                }

                $this->loadComponent("PHMFunction");
               
                $logo = $this->PHMFunction->getSettings("company_logo");
                $logo = (!empty($logo)) ? $logo : $this->request->webroot."upload/logo.png";
                $name = $this->PHMFunction->getSettings("name");
                $left_header = $this->PHMFunction->getSettings("left_header");
                $footer = $this->PHMFunction->getSettings("footer");
                $is_rtl = ($this->PHMFunction->getSettings("enable_rtl") == 1) ? true : false;
                $datepicker_lang = $this->PHMFunction->getSettings("datepicker_lang");
                $version = $this->PHMFunction->getSettings("system_version");

                $session = $this->request->session();
                $fname = $session->read('Auth.User.first_name');
                $lname = $session->read('Auth.User.last_name');
                $created_role = $session->read('Auth.User.created_role');
                $created_by = $session->read('Auth.User.created_by');
                $uid = $session->read('Auth.User.id');
               

                $join_date = $session->read('Auth.User.created_date');
                $profile_img = $session->read('Auth.User.image');
                $agree = $session->read('Auth.User.agree');
                $lice_agree_type = $session->read('Auth.User.licensee_type');
                //$location_id = $session->read('Auth.User.location_id'); 
                //Location ID set
                $conn = ConnectionManager::get('default');
                $location_id = 0;

                $display_name=$this->PHMFunction->get_user_name($uid);
                $profile_img=$this->PHMFunction->get_user_picture($uid);
                
                $role_name = $session->read('Auth.User.role_name');
                $role_id = $session->read('Auth.User.role_id');
              //  $session->write("User.display_name", $fname . " " . $lname);
                $session->write("User.display_name", $display_name);
                $session->write("User.id", $uid);
                $session->write("User.original_id", $uid);
               

                $session->write("User.role_name", $role_name);
                $session->write("User.created_by", $created_by);
                $session->write("User.created_role", $created_role);
                $session->write("User.location_id", $location_id);
                $session->write("User.role_id", $role_id);
                $session->write("User.join_date", $join_date);
                $session->write("User.profile_img", $profile_img);
                $session->write("User.logo", $logo);
                $session->write("User.name", $name);
                $session->write("User.left_header", $left_header);
                $session->write("User.footer", $footer);
                $session->write("User.is_rtl", $is_rtl);
                $session->write("User.dtp_lang", $datepicker_lang);
                $session->write("User.version", $version);
                $session->write("User.agree", $agree);
                $session->write("User.lice_agree_type", $lice_agree_type);
                $session->write("User.loc_type", 'main');

                return $this->redirect($this->Auth->redirectUrl());
            } else {
                $this->Flash->error(__('Invalid email or password, try again'));
            }
        }
        if ($this->Auth->user()) {
            return $this->redirect($this->Auth->redirectUrl());
        }
        $this->viewBuilder()->layout('login');
    }

    public function logout() {
        $session = $this->request->session();
        $session->delete('User');
        $session->destroy();
        return $this->redirect($this->Auth->logout());
    }
    
    
    public function subscription()
    {
        die("Hello");
    }

    public function updateSys() {
        // $this->autoRender = false;
        $conn = ConnectionManager::get('default');
        $sql = "SELECT * from pheramor_general_setting";
        $settings = $conn->execute($sql)->fetchAll("assoc");
        if (!empty($settings)) {
            if (isset($settings[0]["system_version"])) {
                $version = $settings[0]["system_version"];
                switch ($version) {
                    CASE "2": /* If old version is 2 */

                        $sql = "UPDATE `pheramor_general_setting` SET system_version = '3' ";
                        $conn->execute($sql);

                        break;
                    CASE "3": /* If old version is 2 */

                        $sql = "UPDATE `pheramor_general_setting` SET system_version = '4' ";
                        $conn->execute($sql);

                        break;
                }
            } else {
                /* 1st Update */
                $sql = "ALTER TABLE `pheramor_general_setting` ADD `enable_rtl` INT(11) NULL DEFAULT '0'";
                $conn->execute($sql);
                $sql = "ALTER TABLE `pheramor_general_setting` CHANGE `enable_rtl` `enable_rtl` INT(11) NULL DEFAULT '0'";
                $conn->execute($sql);
                $sql = "ALTER TABLE `pheramor_general_setting` ADD `datepicker_lang` TEXT NULL DEFAULT NULL";
                $conn->execute($sql);
                $sql = "ALTER TABLE `pheramor_general_setting` ADD `system_version` TEXT NULL DEFAULT NULL";
                $conn->execute($sql);
                $sql = "ALTER TABLE `pheramor_general_setting` ADD `sys_language` VARCHAR(20) NOT NULL DEFAULT 'en'";
                $conn->execute($sql);
                $sql = "UPDATE `pheramor_general_setting` SET system_version = '2' ";
                $conn->execute($sql);

                /*$path = $this->request->base;
               $sql = "INSERT INTO `gym_accessright` (`controller`, `action`, `menu`, `menu_icon`, `menu_title`, `member`, `staff_member`, `accountant`, `page_link`) VALUES ('Reports', '', 'report', 'report.png', 'Report', '0', '1', '1', '" . $path . "/reports/membership-report')";
                $conn->execute($sql);

                $sql = "SHOW COLUMNS FROM `membership` LIKE 'membership_class' ";
                $columns = $conn->execute($sql)->fetch();
                if ($columns == false) {
                    $sql = "ALTER TABLE `membership` ADD `membership_class` varchar(255) NULL";
                    $conn->execute($sql);
                }*/
            }
        }
    }

}
