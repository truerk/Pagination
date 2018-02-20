<?php 
  try{
      @ $db = new PDO("mysql:host=localhost;dbname=paginationTask","root","");
      $db->exec("set names utf8");
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);       
  }catch(PDOException $e){
      echo "Ошибка подключения: ".$e->getMessage();
  }

  if (empty($_POST['task_id']) or empty($_POST['task_name'])) {
    echo json_encode(array('result' => '1'));
    exit();
  }

  $task_id = htmlspecialchars(trim($_POST['task_id']));
  $task_name = htmlspecialchars(trim($_POST['task_name']));

  if (empty($task_id) or empty($task_name)) {
    echo json_encode(array('result' => '1'));
    exit();
  }

  $query = $db->prepare('update task set task_name=:task_name where task_id=:task_id');
  $query->execute(['task_id'=>$task_id, 'task_name'=>$task_name]);

  if ($query) {
    echo json_encode(array('result' => '2'));
    exit();
  }else{
    echo json_encode(array('result' => '3'));
    exit();
  }


?>