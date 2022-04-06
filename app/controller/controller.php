<?php
class Todocontroller {

   public function index() {
      $lists = Database::dbconnect();
      return $lists;
   }

   public function create() {
      $title = (filter_input(INPUT_POST, 'title'));
      $content = (filter_input(INPUT_POST, 'content'));

      $todo = new Database;
      $todo->setTitle($title);
      $todo->setContent($content);
      $result = $todo->save();

      header("Location: index.php");
   }

   public function update() {
      $id = (filter_get(INPUT_GET, 'id'));

      $todo = new Database;
      $todo->setContent($id);
      $result = $todo->save();
   }

}