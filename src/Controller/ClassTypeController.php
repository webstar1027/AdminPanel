<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;

class ClassTypeController extends AppController {

    public function initialize() {
        parent::initialize();
        $this->loadComponent("GYMFunction");
    }

    public function classtypeList() {
        $session = $this->request->session()->read("User");
        switch ($session["role_name"]) {
            CASE "administrator" :
                $data = $this->ClassType->find("all")->contain(['GymMember'])->hydrate(false)->toArray();
                break;

            CASE "licensee" :
                $data = $this->ClassType->find("all")->contain(['GymMember'])
                                ->where(["ClassType.created_by" => $session["id"]])
                                ->orWhere(['ClassType.role_name' => 'administrator'])
                                ->hydrate(false)->toArray();
                break;

            CASE "staff_member" :
                $licensee = $this->ClassType->GymMember->find()->where(['id'=>$session['id']])->select(['associated_licensee'])->hydrate(false)->first();
                $data = $this->ClassType->find("all")
                    ->contain(['GymMember'])
                    ->where(["ClassType.created_by" => $licensee['associated_licensee']])
                    ->orWhere(['ClassType.role_name' => 'administrator'])
                    ->hydrate(false)->toArray();
                break;
            
        }

        $this->set("data", $data);
    }

    public function addclassType() {
        $session = $this->request->session()->read("User");
        $this->set("edit", false);
        $this->set("title", __("Add Class Type"));
        //$classes = $this->GymLocation->ClassSchedule->find("list",["keyField"=>"id","valueField"=>"class_name"]);
        //$this->set("classes",$classes);

        if ($this->request->is("post")) {
            
            $row = $this->ClassType->newEntity();

            /* SANITIZATION */
            $this->request->data["description"] = $this->GYMFunction->sanitize_string($this->request->data["description"]);
            /* SANITIZATION */
            $this->request->data["updated_by"] = $session["id"];
            $this->request->data["created_date"] = date("Y-m-d H:i:s");
            $this->request->data["updated_date"] = date("Y-m-d H:i:s");
            $this->request->data["role_name"] = $session["role_name"];
            $this->request->data["created_by"] = $session["id"];

            $row = $this->ClassType->patchEntity($row, $this->request->data);
            if ($this->ClassType->save($row)) {
                $this->Flash->success(__("Success! Class Type Successfully Saved."));
                return $this->redirect(["action" => "classtypeList"]);
            } else {
                $this->Flash->error(__("Error! Class Type Not Saved.Please Try Again."));
            }
        }
    }

    public function editclassType($pid) {

        $session = $this->request->session()->read("User");

        $row1 = $this->ClassType->get($pid);
        //echo $this->GYMFunction->pre($row1);die;
        if(!count($row1)){
            //die('jhbhb');
            $this->Flash->error(__("Error! Requested record not found."));
            return $this->redirect(["action" => "classtypeList"]);
        }
        $row = $row1->toArray();
        if($session['role_id'] != 1 &&  $row['created_by'] != $session['id'] && $row['role_name'] != 'administrator' ){
            $this->Flash->error(__("Error! You don't have permission to edit this class type."));
            return $this->redirect(["action" => "classtypeList"]);
        }

        $this->set("edit", true);
        $this->set("title", __("Edit Class Type"));
        //$row = $this->ClassType->get($pid);
        $this->set("data", $row);

        /** End here * */
        //$classes = $this->GymLocation->ClassSchedule->find("list",["keyField"=>"id","valueField"=>"class_name"]);
        //$this->set("classes",$classes);

        if ($this->request->is("post")) {
            /* SANITIZATION */
            $this->request->data["description"] = $this->GYMFunction->sanitize_string($this->request->data["description"]);
            /* SANITIZATION */
            $this->request->data["updated_by"] = $session["id"];
            $this->request->data["updated_date"] = date("Y-m-d H:i:s");

            $row1 = $this->ClassType->patchEntity($row1, $this->request->data);
            if ($this->ClassType->save($row1)) {
                $this->Flash->success(__("Success! Record Successfully Updated."));
                return $this->redirect(["action" => "classtypeList"]);
            } else {
                $this->Flash->error(__("Error! Record Not Updated.Please Try Again."));
            }
        }
        $this->render("addclassType");
    }

    public function deleteclassType($did=null) {

        $session = $this->request->session()->read("User");
        if(!isset($did) || $did == ''){
            $this->Flash->error(__("Error! Requested record not found."));
            return $this->redirect(["action" => "classtypeList"]);
        }
        $row1 = $this->ClassType->get($did);
        if(!count($row1)){
            $this->Flash->error(__("Error! Requested record not found."));
            return $this->redirect(["action" => "classtypeList"]);
        }

        $row = $row1->toArray();

        if($session['role_id'] != 1 &&  $row['created_by'] != $session['id'] && $row['role_name'] != 'administrator' ){
            $this->Flash->error(__("Error! You don't have permission to delete this class type."));
            return $this->redirect(["action" => "classtypeList"]);
        }

        /** End here * */
        $conn = ConnectionManager::get('default');
        $report_21 = "SELECT count(*) as newcount from `gym_class` where class_type_id=$did";
        $report_21 = $conn->execute($report_21);
        $report_21 = $report_21->fetchAll('assoc');
        //$this->GYMFunction->pre($report_21);
        if ($report_21[0]['newcount'] > 0) {
            $this->Flash->error(__("Sorry! This class type already used in class."));
            return $this->redirect(["action" => "classtypeList"]);
        }

        if ($this->ClassType->delete($row1)) {
            $this->Flash->success(__("Success! Record Deleted Successfully Updated."));
            return $this->redirect(["action" => "classtypeList"]);
        }
    }

    public function isAuthorized($user) {
        return parent::isAuthorizedCustom($user);
    }

}
