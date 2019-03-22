<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
class PheramorImportController extends AppController {
 
    public function initialize() {
        parent::initialize();
        /* $this->loadComponent('Csrf'); */
        $this->loadComponent("PHMFunction");
        $session = $this->request->session()->read("User");
        $this->set("session", $session);
    }
    
     /**
     * Upload User Generic Data
     * @Method importGenericData
     * @Date 23 Nov 2017
     * @Author RNF Technologies  
     */
     public function importGenericData(){
          // echo "<pre>"; print_r($_SERVER);  die;
           $session = $this->request->session()->read("User");
           $category = $this->PheramorImport->newEntity();
           $category_data = $this->PheramorImport->find("all")->where(['module_name'=>'UGD'])->order(['id'=>'DESC'])->toArray();
           $this->set("category_data", $category_data);
           $this->set("category", null);
           if ($this->request->is("post")) {
            $this->request->data["created_date"] = date("Y-m-d H:i:s");
            $this->request->data["author_id"] = $session['id'];
            $this->request->data["module_name"] = 'UGD';
            $file = $this->PHMFunction->uploadImportImage($this->request->data['user_genetic_data']);
            if(empty($file)){
                 $this->Flash->error(__("Error! Please upload valid csv file"));
                 return $this->redirect(["action" => "importGenericData"]);
            }
            $this->request->data['file_name'] = $file;
            $upload_file_name = $_FILES['user_genetic_data']['name']; 
            $this->request->data['upload_file_name']=$upload_file_name;      
            $category = $this->PheramorImport->patchEntity($category, $this->request->data());
            
           
            

            if ($this->PheramorImport->save($category)) {
                $conn = ConnectionManager::get('default');
                 $csv_file = $_SERVER['DOCUMENT_ROOT'] . $file; 
                  $fileupload = fopen($csv_file, "r");
                 $firstline = true;
                 while (($emapData = fgetcsv($fileupload, 10000, ",")) !== FALSE)
                 {
                   // echo "<pre>"; print_r($emapData); die;
                   if (!$firstline) {
                       ///
                      if(!empty($emapData[0])){
                       $emapData[0]= trim($emapData[0]);
                       $emapData[1]= trim($emapData[1]);
                       $emapData[2]= trim($emapData[2]);
                       $emapData[3]= trim($emapData[3]);
                       $emapData[4]= trim($emapData[4]);
                       $emapData[5]= trim($emapData[5]);
                       $emapData[6]= trim($emapData[6]);
                       $emapData[7]= trim($emapData[7]);
                       $emapData[8]= trim($emapData[8]);
                       $emapData[9]= trim($emapData[9]);
                       $emapData[10]= trim($emapData[10]);
                       $emapData[11]= trim($emapData[11]);
                       $emapData[12]= trim($emapData[12]);
                      // $emapData[13]= trim($emapData[13]);
                       
                       
                     $selectgdata= $conn->execute("select * from pheramor_genetic_information where pheramor_kit_ID='" . $emapData[0] . "' ");
                     if ($selectgdata->count() > 0){
                          $update = $conn->execute("update pheramor_genetic_information set 
                       pheramor_kit_ID='".$emapData[0] ."',
                       HLA_A_1 ='" . $emapData[1] . "' ,
                       HLA_A_2 ='" . $emapData[2] . "' ,
                       HLA_B_1 ='" . $emapData[3] . "' ,
                       HLA_B_2 ='" . $emapData[4] . "' ,
                       HLA_C_1 ='" . $emapData[5] . "' ,
                       HLA_C_2 ='" . $emapData[6] . "' ,
                       HLA_DPB1_1 ='" . $emapData[7] . "' ,
                       HLA_DPB1_2 ='" . $emapData[8] . "' ,
                       HLA_DQB1_1 ='" . $emapData[9] . "' ,
                       HLA_DQB1_2 ='" . $emapData[10] . "' ,
                       HLA_DRB_1 ='" . $emapData[11] . "' ,
                       HLA_DRB_2 ='" . $emapData[12] . "' ,
                       updated_date='" . date('Y-m-d H:i:s') . "'  where pheramor_kit_ID='" . $emapData[0] . "' ");
                     } else{   
                     $insert = $conn->execute("insert into pheramor_genetic_information set 
                       pheramor_kit_ID='".$emapData[0] ."',
                       HLA_A_1 ='" . $emapData[1] . "' ,
                       HLA_A_2 ='" . $emapData[2] . "' ,
                       HLA_B_1 ='" . $emapData[3] . "' ,
                       HLA_B_2 ='" . $emapData[4] . "' ,
                       HLA_C_1 ='" . $emapData[5] . "' ,
                       HLA_C_2 ='" . $emapData[6] . "' ,
                       HLA_DPB1_1 ='" . $emapData[7] . "' ,
                       HLA_DPB1_2 ='" . $emapData[8] . "' ,
                       HLA_DQB1_1 ='" . $emapData[9] . "' ,
                       HLA_DQB1_2 ='" . $emapData[10] . "' ,
                       HLA_DRB_1 ='" . $emapData[11] . "' ,
                       HLA_DRB_2 ='" . $emapData[12] . "' ,
                       created_date='" . date('Y-m-d H:i:s') . "' ,
                       updated_date='" . date('Y-m-d H:i:s') . "'");
                     }
                     
                       }else{
                          $this->Flash->error(__("Error! Please upload csv file with correct data"));
                          return $this->redirect(["action" => "importGenericData"]);
                       }
                   }
                        $firstline = false;
                     }
                      $this->Flash->success(__("Success! Record Saved Successfully"));
                      return $this->redirect(["action" => "importGenericData"]);
                 } else {
               
                $this->Flash->error('Error');
               
                return;
               
            }
        }
     }
     
      /**
     * Add Mechine Genetic Data
     * @Method addMechineGeneticData
     * @Date 29 Nov 2017
     * @Author RNF Technologies  
     */
     
     public function addMachineGeneticData(){
           $session = $this->request->session()->read("User");
           $category = $this->PheramorImport->PheramorGenericMaster->newEntity();
           $this->set("category", null);
            if ($this->request->is("post")) {
            $this->request->data["created_date"] = date("Y-m-d H:i:s");
            $this->request->data["updated_date"] = date("Y-m-d H:i:s");
            $conn = ConnectionManager::get('default');
            $table_name=$this->request->data["generic_type"];
            $insert = $conn->execute("insert into $table_name set gene1='" . $this->request->data["gene1"] . "',
                       gene2='".$this->request->data["gene2"] ."',
                       dissimilarity ='" . $this->request->data["dissimilarity"] . "' ,
                       created_date ='" . date("Y-m-d H:i:s") . "' ,
                       updated_date ='" . date("Y-m-d H:i:s") . "' ");
             if ($insert) {
                   $this->Flash->success(__("Success! Record Saved Successfully"));
                   return $this->redirect(["action" => "addMachineGeneticData"]);
                 } else {
                  $this->Flash->error('Error');
                   return;
              }
           /* $data = array(
                        "generic_type" => $this->request->data["generic_type"], 
                        "gene1" => $this->request->data["gene1"],
                        "gene2" => $this->request->data["gene2"],
                        "dissimilarity" => $this->request->data["dissimilarity"]
                    );                                                                    
            $data_string = json_encode($data);
            //print_r($data_string);
            $ch = curl_init('http://staging.pheramorapp.com:5000/geneticdata/save');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string))
            );

            $result = curl_exec($ch);
            $data=json_decode($result);
            //print_r($data);  die;
            if ($data->status==1) {
                   $this->Flash->success(__("Success! Record Saved Successfully"));
                   return $this->redirect(["action" => "addMachineGeneticData"]);
                 } else {
                  $this->Flash->error('Error');
                   return;
               
            }
            */
            /*$category = $this->PheramorImport->PheramorGenericMaster->patchEntity($category, $this->request->data());
            if ($this->PheramorImport->PheramorGenericMaster->save($category)) {
                   $this->Flash->success(__("Success! Record Saved Successfully"));
                   return $this->redirect(["action" => "addMachineGeneticData"]);
                 } else {
                  $this->Flash->error('Error');
                   return;
               
            }*/
        }
     }

     /**
     * Manage User Genetic Data
     * @Method manageGenericData
     * @Date 07 March 2018
     * @Author RNF Technologies  
     */
     
     public function manageGenericData() {
        
    }

    public function isAuthorized($user) {
        return parent::isAuthorizedCustom($user);
    }

}

