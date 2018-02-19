<?php 

  try{
      @ $db = new PDO("mysql:host=localhost;dbname=paginationTask","root","");
      $db->exec("set names utf8");
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);       
  }catch(PDOException $e){
      echo "Ошибка подключения: ".$e->getMessage();
  }

  $query = $db->prepare('select * from task');
  $query->execute();
  $task_count = $query->rowCount();
  $task = $query->fetchALL();  
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title></title>
  <link rel="stylesheet" type="text/css" href="style/style.css">
  <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
</head>
<body>

  <div class="task-main">
    <div class="task-content">
      <div class="task-header">
        <input id="task_name" name="task_name" type="text" placeholder="Наименование">
        <button id="btn_add" class="add" type="submit"><i class="fa fa-plus" aria-hidden="true"></i></button>  
      </div>      

      <div class="task-body">
        <div class="task_null">Задач еще нету</div>

        <?php if ($task_count > 0) { 
          foreach ($task as $tsk) : ?>
            <div class="task-container" id="<?=$tsk['task_id']?>">
              <label><?=$tsk['task_name']?></label>
              <button class="update"><i class="fa fa-pencil" aria-hidden="true"></i></button>
              <button class="delete"><i class="fa fa-minus" aria-hidden="true"></i></button>
            </div>

        <?php endforeach; } ?>

      </div>
    </div>
  </div>


<script src="js/jquery.js"></script>
<script type="text/javascript">
  $(document).ready(function(){

    /* -- проверяем на наличие тасок --- */
    function check_list(){
      var task_count = $('.task-container').length;
      if (task_count > 0) {
        $('.task_null').css('display', 'none');
        
      }else{
        $('.task_null').css('display', 'block');
      }
    }
    check_list();

    /* -- удаление тасок --- */
    function add_delete(){
      $('.delete').on('click', function(){
        var containter = $(this).parent();//attr('id')
        var task_id = this.parentNode.id;
        $.ajax({
          type: 'POST',
          url: 'task_delete.php',
          data: {task_id: task_id},
          success: function(result){
            var json = $.parseJSON(result);
            if (json.result == '1') {
              alert('Что то не получилось');
            }else if(json.result == '2'){
              $('#' + task_id).css("animation", "task_delete 1s ease-in-out");
              setTimeout(function(){
                containter.remove();
                check_list();
              }, 1000);               
            }          
          },
          error: function(){
            alert('Что то не получилось');
          }
        });
      });
    }
    add_delete();

    /* --- добавление --- */
    $('#btn_add').on('click', function(){
      var task_name = $('#task_name').val();
      var task_body = $('.task-body');
      $.ajax({
        type: 'POST',
        url: 'task_add.php',
        data: {task_name: task_name},
        success: function(response){
          var json = $.parseJSON(response);
          if (json.result == '1') {
            alert('Вы не заполнили название');
          }else if(json.result == '2'){
            task_body.append('<div class="task-container" id='+ json.id +'><label>' + task_name + '</label>' + 
            '<button class="update"><i class="fa fa-pencil" aria-hidden="true"></i></button>' +
            '<button class="delete"><i class="fa fa-minus" aria-hidden="true"></i></button></div>');
            check_list();
            $('#' + json.id).css("animation", "task_add 1s ease-in-out");
            add_delete();          
          }
        },
        error: function(){
          alert('Что то не получилось');
        }
      });
    });

  });
</script>
</body>
</html>