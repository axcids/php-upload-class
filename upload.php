<?php

class Upload{
  public $file; //for the file coming from the user
  public $fileName; //for the name of file
  public $filePath; //for the database it should start after /htdocs/
  protected $rootDir; //for /htdocs/ and everything before it
  public $uploadDir; //for everything before and after /htdocs/

  public function __construct($uploadDir){ //uploads/test-images
    $this->filePath = $uploadDir;
    $this->rootDir = $_SERVER['DOCUMENT_ROOT'];
    $this->uploadDir = $this->rootDir.'/'.$uploadDir;
    //filePath = uploads/test-images/11515456454.png
    //rootDir = c://xampp/htdocs
    // uploadDir = c://xampp/htdocs/uploads/test-images/5165464SEU.jpg

  }
  public function uploader(){
    global $errors;
    if($this->createUploadDir()){
      $this->fileName = time().$this->file['name'];
      $this->filePath .= '/'.$this->fileName;
      move_uploaded_file($this->file['tmp_name'], $this->uploadDir.'/'.$this->fileName);
    }
  }
  protected function createUploadDir(){
    global $errors;
    if(!is_dir($this->uploadDir)){
      umask(0);
      if(!mkdir($this->uploadDir, 0775)){
        array_push($errors, "Upload directory is not created");
        return false;
      }
    }
    return true;
  }
  public function validate(){
    global $errors;
    if(!empty($this->file['name'])){
      $this->isTypeAllowed();
      $this->isSizeAllowed();
      return true;
    }else{
      array_push($errors, "File is required!");
      return false;
    }
  }
  protected function isTypeAllowed(){
    global $errors;
    $allowed = [
      'jpg' => 'image/jpeg',
      'png' => 'image/png',
      'gif' => 'image/gif'
    ];
    if(!empty($this->file['name'])){
      $fileMimeType = mime_content_type($this->file['tmp_name']);
      if(!in_array($fileMimeType, $allowed)){
        array_push($errors, "File type is not allowed!");
        return false;
      }
    }
      return true;
  }
  protected function isSizeAllowed(){
    global $errors;
    $maxFileSize = 10 * 1024 * 1024; //10 Mega
    $fileSize = $this->file['size'];
    if($fileSize > $maxFileSize){
      array_push($errors, "File size is not allowed!");
      return false;
    }
    return true;
  }
}
