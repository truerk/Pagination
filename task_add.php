<?php 
  try{
      @ $db = new PDO("mysql:host=localhost;dbname=paginationTask","root","");
      $db->exec("set names utf8");
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);       
  }catch(PDOException $e){
      echo "Ошибка подключения: ".$e->getMessage();
  }

  if (empty($_POST['task_name'])) {    
    echo json_encode(array('result' => '1'));
    exit();
  }

  $task_name = htmlspecialchars(trim($_POST['task_name']));

  $query = $db->prepare('insert into task set task_name=:task_name');
  $query->execute(['task_name'=>$task_name]);
  $last_id = $db->lastInsertId();
  if ($query) {
    echo json_encode(array('result' => '2','id' => $last_id));
    exit();
  }







?>