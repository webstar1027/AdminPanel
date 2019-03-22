<?php
/**
 * Amazon S3 services Comonent.
 */
  
//require 'aws/aws-autoloader.php';

namespace App\Controller\Component;
use Cake\Controller\Component;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\Core\Exception\Exception;
use Aws\Common\Exception\RuntimeException;
use Aws\Common\Exception\MultipartUploadException;
use Aws\S3\Model\MultipartUpload\UploadBuilder;
use Aws\S3\S3Client;

class AwsComponent  extends Component {

/**
 * @var : name of bucket in which we are going to operate
 */ 	
	public $bucket = 'pheramorapp';

/**
 * @var : Amazon S3Client object
 */ 	
	private $s3 = null;
	
	
	public function __construct(){
		require_once(ROOT . DS . 'vendor' . DS . 'aws' . DS . 'aws-autoloader.php');
		$this->s3 = S3Client::factory(array(
			'key' => 'AKIAI3MIM3Y6ESA7HVUQ',
			'secret' => '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',
			//'region' => 'eu-west-1',
		));
		
	}
	
	
/**
 * @desc : to upload file on bucket with specified path
 * @param : keyname > path of file which need to be uploaded
 * @return : uploaded file object 
 * @created on : 14.03.2014
 */	

	public function upload($keyname=null){
		try {
			$uploader = UploadBuilder::newInstance()
						->setClient($this->s3)
						->setSource($keyname)
						->setBucket($this->bucket)
						->setKey($keyname)
						->build();
						
			return  $uploader->upload();
			 
		} catch (MultipartUploadException $e) {
			if(Configure::read('debug')) echo 'S3 Exception :'.$e->getMessage() ;
			$uploader->abort();
		} catch (Exception $e) {
			if(Configure::read('debug')) echo 'Exception :'.$e->getMessage() ;
		}
		
		return false; 	
	}
        
   /**
 * @desc : to upload file on bucket with specified path
 * @param : keyname > path of file which need to be uploaded
 * @return : uploaded file object 
 * @created on : 14.03.2014
 */	
    
	public function uploadfile($temp, $fileName) {
        if (!empty($fileName)) {
            try {
            $data = $this->s3->putObject([
                'Bucket' => $this->bucket,
                'Key' => $fileName,
                'SourceFile' => $temp,
                'ContentType' => 'image/jpeg',
                'ACL' => 'public-read',
                'StorageClass' => 'REDUCED_REDUNDANCY'
            ]);
            }catch (S3Exception $e) {
                echo $e->getMessage() . "\n";
            }
        } else {
            $data = '';
        }
        return $data;
    }
    public function movefile($fileName,$image_file_name) {
        if (!empty($fileName)) {
            try {
            $data = $this->s3->putObject([
                'Bucket' => $this->bucket,
                'Key' => $image_file_name,
                'Body'   => fopen($fileName, 'r'),
                'ContentType' => 'image/jpeg',
                'ACL' => 'public-read'
            ]);
            }catch (S3Exception $e) {
                echo $e->getMessage() . "\n";
            }
        } else {
            $data = '';
        }
        return $data;
    }

    /**
 * @desc : to delete multiple objects from bucket
 * @param : array(
				array('Key' => $keyname1),
				array('Key' => $keyname2),
				array('Key' => $keyname3),
			)
 * @return : boolean
 * @created on : 14.03.2014   
 */
	public function delete($objects=array()){
		try{
			return $this->s3->deleteObjects(array(
				'Bucket' => $this->bucket,
				'Objects' => $objects
			));
		} catch (RuntimeException $e) {
			if(Configure::read('debug')) echo 'RuntimeException Exception :'.$e->getMessage() ;
		} catch (Exception $e) {
			if(Configure::read('debug')) echo 'Exception :'.$e->getMessage() ;			
		}
		return false ;
	}
	
	
 /**
 * @desc : to empty specified folder
 * @param : folder to which you want to empty
 * @return : deleted file count
 * @created on :14.03.2014
 */    
   public function emptyFolder($folder=null,$regexp='/\.[0-9a-z]+$/'){
		try{
			return $this->s3->deleteMatchingObjects($this->bucket, $folder, $regexp);
			
		} catch (RuntimeException $e) {
			if(Configure::read('debug')) echo 'RuntimeException Exception :'.$e->getMessage() ;	
		} catch (Exception $e) {
			if(Configure::read('debug')) echo 'Exception :'.$e->getMessage() ;			
		}
		return false ;
	}
			
}
