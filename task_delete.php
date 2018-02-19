<?php 
  try{
      @ $db = new PDO("mysql:host=localhost;dbname=paginationTask","root","");
      $db->exec("set names utf8");
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);       
  }catch(PDOException $e){
      echo "Ошибка подключения: ".$e->getMessage();
  }

  if (empty($_POST['task_id'])) {    
    echo json_encode(array('result' => '1'));
    exit();
  }

  $task_id = htmlspecialchars(trim($_POST['task_id']));

  $query = $db->prepare('delete from task where task_id=:task_id');
  $query->execute(['task_id'=>$task_id]);
  
  if ($query) {
    echo json_encode(array('result' => '2'));
    exit();
  }







?>