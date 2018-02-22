<?php 
  sleep(1.5);
  if (!empty($_POST['name']) and !empty($_POST['age'])) {
    echo 'Вас зовут '.$_POST['name'].', ваш возраст '.$_POST['age'];
  }else{
    echo 'Вы заполнили не все данные';
    echo "sdfdsf";
  }



?>