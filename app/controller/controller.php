<?php

// controllerフォルダ todocontroller
require_once('config.php');
require_once('error.php');

class Utils {
   public static function h($str) {
      return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
   }
}

class Token {
   public static function create() {
      if (!isset($_SESSION['token'])) {
         $_SESSION['token'] = bin2hex(random_bytes(32));
      }
   }
}

class Todocontroller {

   public function todos() {
      $todolists = Database::todogetAll();
      return $todolists;
   }

   public function words() {
      $wordlists = Database::wordgetAll();
      return $wordlists;
   }

   public function bodies() {
      $bodylists = Database::bodiesgetAll();
      return $bodylists;
   }

   public function goals() {
      $goallists = Database::goalget();
      return $goallists;
   }

   public function files() {
      $filelists = Database::fileAllget();
      return $filelists;
   }

   public function create() {

   // $title = $_POST['title'];
   // $content = $_POST['content'];
         
      $data = array(
         "title" => $_POST['title'],
         "content" => $_POST['content'],
     );

      $validation = new TodoValidation;
      $validation->setData($data);

      if($validation->tokencheck() === false) {
         $token_errors = $validation->getTokenErrorMessages();
         $_SESSION['token_errors'] = $token_errors;
         header("Location: ./create.php");
         return;
      } else if($validation->todocheck() === false) {
         $title_errors = $validation->getTitleErrorMessages();
         $content_errors = $validation->getCotentErrorMessages();
         $_SESSION['title_errors'] = $title_errors;
         $_SESSION['content_errors'] = $content_errors;

         $params = sprintf("?id=%s&title=%s&content=%s", $_POST['id'], $_POST['title'], $_POST['content']);
         header(sprintf("Location: ./create.php%s", $params));
         return;
      }

      $validation_data = $validation->getData();

      $todo = new Database;
      $todo->setTitle($validation_data['title']);
      $todo->setContent($validation_data['content']);
      $result = $todo->save();

      header("Location: ./index.php");
   }

   public function pictures() {

      $filedata = array(
         "file" => $_FILES['img'],
         "filename" => basename($file['name']),
         "tmp_path" => $file['tmp_name'],
         "fil_err" => $file['error'],
         "filesize" => $file['size'],
         "comment" => $_POST['comment'],
         "filetype" => pathinfo($save_path,PATHINFO_EXTENSION),
         "$arrImagetype" => array('jpg','jpeg','png','git','pdf'),
      );
      // "tmp_path" => $file['tmp_name'],

      $file = $_FILES['img'];
      $filename = basename($file['name']);
      $tmp_path = $file['tmp_name'];
      $fil_err = $file['error'];
      $filesize = $file['size'];
      $upload_dir = './images/';
      $save_filename = date('YmdHis') . $filename;
      $save_path = $upload_dir . $save_filename;
      $filetype = pathinfo($save_path,PATHINFO_EXTENSION);
      $arrImagetype = array('jpg','jpeg','png','git','pdf');
      $comment = filter_input(INPUT_POST, 'comment',FILTER_SANITIZE_SPECIAL_CHARS);

      // if($validation->filecheck() === false) {
      //    $filetype_errors = $validation->getFileTypeErrorMessages();
      //    $_SESSION['filetype_errors'] = $filetype_errors;
      //    header("Location: ./file.php");
      //    return;
      // }

      
      // $allow_ext = array('jpg','jpeg','png','git','pdf');
      // $file_ext = pathinfo($filename, PATHINFO_EXTENSION);
      // if(!in_array(strtolower($file_ext), $allow_ext)) {
      //    $validation = new TodoValidation;
      //    $validation->tokencheck();
      //    $filemodel_errors = $validation->getFileModelErrorMessages();
      //    $_SESSION['filemodel_errors'] = $filemodel_errors;
      //    header("Location: ./file.php");
      //    return;
      //    echo "ファイルの末を変更してください";
      // }
      

      // 拡張 書き換え　8/2開始
      // $filetype = pathinfo($save_path,PATHINFO_EXTENSION);
      // $arrImagetype = array('jpg','jpeg','png','git','pdf');
      if(in_array(strtolower($filetype), $arrImagetype)) {
         
         // ファイルがあるかどうか？ 
         if(move_uploaded_file($tmp_path, $save_path)) {
            $validation = new TodoValidation;
            $validation->setFileData($filedata);
            if($validation->tokencheck() === false) {
               $token_errors = $validation->getTokenErrorMessages();
               $_SESSION['token_errors'] = $token_errors;
               header("Location: ./file.php");
               return;
            } 
            if($validation->filecheck() === false) {
               $comment_errors = $validation->getCommentErrorMessages();
               $filesize_errors = $validation->getFileSizeErrorMessages();
               $_SESSION['comment_errors'] = $comment_errors;
               $_SESSION['filesize_errors'] = $filesize_errors;

            // $file_errors = $validation->getFileErrorMessages();
            // $_SESSION['file_errors'] = $file_errors;

               header("Location: ./file.php");
               return;
            } 
      
            $validation_filedata = $validation->getFileData();

            $img = new Database;
            $imgresult = $img->filesave($filename,$save_path,$comment);
            header("Location: ./file_upload.php");
         } 
      } 
      else if(!in_array(strtolower($filetype), $arrImagetype))
      {
         $validation = new TodoValidation;
         $validation->filecheck();
         $filemodel_errors = $validation->getFileModelErrorMessages();
         $_SESSION['filemodel_errors'] = $filemodel_errors;
         header("Location: ./file.php");
         return;
         echo "ファイルの末を変更してください";
      }

   }

   public function postcreate() {

      $content = (filter_input(INPUT_POST, 'postcontent'));

      $validation = new TodoValidation;
      $validation->setContent($content);

      if($validation->tokencheck() === false) {
         $token_errors = $validation->getTokenErrorMessages();
         $_SESSION['token_errors'] = $token_errors;
         header("Location: ./post.php");
         return;
      } else if($validation->postcheck() === false) {
         $post_errors = $validation->getPostErrorMessages();
         $_SESSION['post_errors'] = $post_errors;
         header("Location: ./post.php");
         return;
      }
      
      $validation_content = $validation->getContent();

      $word = new Database;
      $word->setcontent($validation_content);
      $postresult = $word->postsave();

      header("Location: ./index.php");
   }

   public function dietcreate() {

      $weightdata = array(
         "body" => $_POST['body'],
         "weight" => $_POST['weight'],
         "today" => $_POST['today'],
      );

      $validation = new TodoValidation;
      $validation->setWeightData($weightdata);

      if($validation->tokencheck() === false) {
         $token_errors = $validation->getTokenErrorMessages();
         $_SESSION['token_errors'] = $token_errors;
         header("Location: ./weight.php");
         return;
      }
      
      if($validation->weightheck() === false) {
   
         $weight_errors = $validation->getWeightErrorMessages();
         $body_errors = $validation->getBodyErrorMessages();
         $today_errors = $validation->getTodayErrorMessages();
         $_SESSION['weight_errors'] = $weight_errors;
         $_SESSION['body_errors'] = $body_errors;
         $_SESSION['today_errors'] = $today_errors;

         $weightparams = sprintf("?body=%s&weight=%s&today=%s", $_POST['body'], $_POST['weight'], $_POST['today']);
         header(sprintf("Location: ./weight.php%s", $weightparams));
         return;
      } 

      $validation_weightdata = $validation->getWeightData();

      $physical = new Database;
      $physical->setbody($validation_weightdata['body']);
      $physical->setweight($validation_weightdata['weight']);
      $physical->settoday($validation_weightdata['today']);
      $weightresult = $physical->weightsave();

      header("Location: ./index.php");
   }

   public function edit() {

      $params = array();
      if($_SERVER['REQUEST_METHOD'] === 'GET') {
         if(isset($_GET['id'])) {
            $id = $_GET['id'];
         }
         if(isset($_GET['title'])) {
            $params['title'] = $_GET['title'];
         }
         if(isset($_GET['content'])) {
            $params['content'] = $_GET['content'];
         }
      }

      $todo = Database::findId($id);
      
      $data = array(
         "todo" => $todo,
         "params" => $params,
      );
      
      return $data;
   }  
   
   public function update() {

      $data = array(
         "id" => $_POST['id'],
         "title" => $_POST['title'],
         "content" => $_POST['content'],
      );

      $validation = new TodoValidation;
      $validation->setData($data);

      if($validation->tokencheck() === false) {
         $token_errors = $validation->getTokenErrorMessages();
         $_SESSION['token_errors'] = $token_errors;
         header("Location: ./edit.php");
         return;
      }

      if($validation->todocheck() === false) {
         $title_errors = $validation->getTitleErrorMessages();
         $content_errors = $validation->getCotentErrorMessages();
         $_SESSION['title_errors'] = $title_errors;
         $_SESSION['content_errors'] = $content_errors;

         $params = sprintf("?id=%s&title=%s&content=%s",$_POST['id'], $_POST['title'], $_POST['content']);
         header(sprintf("Location: ./edit.php%s", $params));
         return;
      }
   
      $validation_data = $validation->getData();

      $todo = new Database;
      $todo->setId($validation_data['id']);
      $todo->setTitle($validation_data['title']);
      $todo->setcontent($validation_data['content']);
      $result = $todo->update();

      header("Location: ./index.php");
   }

   public function delete() {

      $id = $_POST['id'];
      if($_SERVER['REQUEST_METHOD'] === 'GET') {
         if(isset($_GET['id'])) {
            $id = $_GET['id'];
         }
      }

      $todo = new Database;
      $todo->setId($id);
      $result = $todo->delete();

      return $result;
   }

   public function postdelete() {

      $word = new Database;
      $postresult = $word->postdelete();
      return $postresult;
   }

   public function filedelete() {

      $id = $_POST['id'];
      if($_SERVER['REQUEST_METHOD'] === 'GET') {
         if(isset($_GET['id'])) {
            $id = $_GET['id'];
         }
      }

      $fileimg = new Database;
      $fileimg->setId($id);
      $fileresult = $fileimg->filedelete();

      return $fileresult;
   }

}