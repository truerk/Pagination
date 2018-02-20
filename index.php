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
        <input class="add-input" id="task_name" name="task_name" type="text" placeholder="Наименование">
        <button id="btn_add" class="add" type="submit"><i class="fa fa-plus" aria-hidden="true"></i></button>  
      </div>      

      <div class="task-body">
        <div class="task_null">Задач еще нету</div>

        <?php /*if ($task_count > 0) {*/ 
          foreach ($task as $tsk) : ?>
            <div class="task-container" id="<?=$tsk['task_id']?>">
              <!--<label class="task-label"><?=$tsk['task_name']?></label>-->
              <input type="text" class="task-label" value="<?=$tsk['task_name']?>" readonly>
              <button class="update"><i class="fa fa-pencil" aria-hidden="true"></i></button>
              <button class="delete"><i class="fa fa-minus" aria-hidden="true"></i></button>
            </div>

        <?php endforeach; /*}*/ ?>
            <div class="task-container" id="<?=$tsk['task_id']?>">
              <!--<label class="task-label"><?=$tsk['task_name']?></label>-->
              <textarea class="task-label" readonly>11111111111111111111 222222222222222222 333333333333333333333 444444444444444444 55555555555555555</textarea>
              <!--<input type="text" class="task-label" value="ккккккккккккккккккккккккккккккккккккккккккккккккккккккккккккккккккккккккккккккккккк" readonly>-->
              <button class="update"><i class="fa fa-pencil" aria-hidden="true"></i></button>
              <button class="delete"><i class="fa fa-minus" aria-hidden="true"></i></button>
            </div>
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

    /* ---- редактирование ---- */
    function add_update(){
      $('.update').on('click', function(){

        var container_id = this.parentNode.id;
        var task_value_old = $('#' + container_id + ' input.task-label').val();   
        $('#' + container_id + ' input.task-label').attr('readonly', false);
        $('#' + container_id + ' input.task-label').focus();

        $('#' + container_id + ' input.task-label').blur(function(){

          /* -- фича от повторного блюра -- */
          if ($('#' + container_id + ' input.task-label').attr('readonly')) {
            //alert('Повторный блюр');
            return false;
          }
          var task_value_new = $('#' + container_id + ' input.task-label').val();
          /* --- фича от одного и того же названия -- */
          if (task_value_old == task_value_new) {
            //alert('одно и то же');
            $('#' + container_id + ' input.task-label').attr('readonly', true);
            return false;
          }

          if (task_value_new == '') {
            $('#' + container_id + ' input.task-label').val(task_value_old);
            $('#' + container_id + ' input.task-label').attr('readonly', true);
            return false;
          }

          /* ------ тупил здесь тк не посылал имя и проверял на пустоту вечно айдишник а не имя, завтра доделать!!!!!!!!!!!!!!!!!!!!! --*/
          $.ajax({
            type: 'POST',
            url: 'task_update.php',
            data: {task_id: container_id, task_name:task_value_new},
            success: function(result){
              var json = $.parseJSON(result);
              //alert(json.result);
              $('#' + container_id + ' input.task-label').val(json.result);
              $('#' + container_id + ' input.task-label').attr('readonly', true);
            },
            error: function(){
              alert('Что то не получилось');
              $('#' + container_id + ' input.task-label').val(task_value_old);
              $('#' + container_id + ' input.task-label').attr('readonly', true);
            }
          });
          
          //$('#' + container_id + ' input.task-label').attr('readonly', true);


        });
        //$("p").removeClass("myClass noClass").addClass("yourClass");
        //var task_value_old = $('#' + container_id + ' input.task-label').val();
        //$('#' + container_id + ' input.task-label').remove();
        //$('#' + container_id).prepend('<input type="text" class="task-update" value='+task_value_old+'>'); 
      });
    }
    add_update();
    

    /* -- удаление тасок --- */
    function add_delete(){
      $('.delete').on('click', function(){
        var containter = $(this).parent();//attr('id')//получаем род контейнер
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
            task_body.append('<div class="task-container" id='+ json.id +'><input type="text" class="task-label" value="' + task_name + '" readonly>' + 
            '<button class="update"><i class="fa fa-pencil" aria-hidden="true"></i></button>' +
            '<button class="delete"><i class="fa fa-minus" aria-hidden="true"></i></button></div>');
            check_list();
            $('#task_name').val('');
            $('#' + json.id).css("animation", "task_add 1s ease-in-out");
            add_delete();

            /* --- добавляем редактирование ---- */
            $('#'+ json.id +' .update').on('click', function(){
              var container_id = json.id;
              $('#' + container_id + ' input.task-label').attr('readonly', false);
              $('#' + container_id + ' input.task-label').focus();
              $('#' + container_id + ' input.task-label').blur(function(){


                //var a = confirm("проверочка");
                //alert(a);
                $('#' + container_id + ' input.task-label').attr('readonly', true);

              });
            });
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