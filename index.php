<?php 

  try{
      @ $db = new PDO("mysql:host=localhost;dbname=paginationTask","root","");
      $db->exec("set names utf8");
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );  //для limit     
  }catch(PDOException $e){
      echo "Ошибка подключения: ".$e->getMessage();
  }

  $page_size = 6;  

  if (isset($_GET['page'])) {    
    if (!preg_match('/^\+?\d+$/', $_GET['page']) or $_GET['page'] == '0') {
      $page_nom_query = 0;
      $page_nom = 1;
    }else{
      $page_nom_query = $page_size * (htmlspecialchars(trim($_GET['page'])) - 1);
      $page_nom = $_GET['page'];      
    }    
  }else{
    $page_nom_query = 0;
    $page_nom = 1;
  }

  $query1 = $db->prepare('select * from task');
  $query1->execute();
  $task_count = $query1->rowCount();

  $page_count = ceil($task_count/$page_size);

  if (htmlspecialchars(trim($_GET['page'])) > $page_count) {
    $page_nom_query = 0;
    $page_nom = 1;
  }
  
  $query = $db->prepare('select * from task order by task_id asc limit :page_nom_query, :page_size');
  $query->execute(['page_nom_query'=>$page_nom_query, 'page_size'=>$page_size]);  
  $task = $query->fetchALL();

  ?>

  <div class="task-page-content" style="display: none;">
<?php
  for ($i=1; $i < $page_count+1; $i++) {     
    if ($i == $page_nom) {
      if ($i == 1) {
        if ($page_count == 1) {
          if ($page_count > 1) {
            $next = $i + 1; ?>
    <?php }else{ ?>
            <span><i class="fa fa-angle-double-left" aria-hidden="true"></i></span>
            <span><i class="fa fa-angle-left" aria-hidden="true"></i></span>
            <span><?=$i?></span>
            <span><i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
            <span><i class="fa fa-angle-right" aria-hidden="true"></i></span>
    <?php } 
        }else{
          $next = $i + 1; ?>
          <span><i class="fa fa-angle-double-left" aria-hidden="true"></i></span>
          <span><i class="fa fa-angle-left" aria-hidden="true"></i></span>
          <span><?=$i?></span>
          <a href='<?php echo $_SERVER['PHP_SELF']?>?page=<?=$next?>' disabled><?=$next?></a>
          <a href='<?php echo $_SERVER['PHP_SELF']?>?page=<?=$page_count?>' disabled><i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
  <?php } ?>
<?php }else if ($page_count > 1) {
        if ($i == $page_count) {
          $back = $i - 1; ?>
          <a href='<?php echo $_SERVER['PHP_SELF']?>?page=<?=1?>' disabled><i class="fa fa-angle-double-left" aria-hidden="true"></i></a>
          <a href='<?php echo $_SERVER['PHP_SELF']?>?page=<?=$back?>' disabled><?=$back?></a>
          <span><?=$i?></span>
          <span><i class="fa fa-angle-right" aria-hidden="true"></i></span>
          <span><i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
  <?php }else{
          $back = $i - 1;
          $back2 = $i - 2;
          $next = $i + 1;
          $next2 = $i + 2; ?>
          <a href='<?php echo $_SERVER['PHP_SELF']?>?page=<?=1?>' disabled><i class="fa fa-angle-double-left" aria-hidden="true"></i></a>
          <a href='<?php echo $_SERVER['PHP_SELF']?>?page=<?=$back?>' disabled><?=$back?></a>
          <span><?=$i?></span>
          <a href='<?php echo $_SERVER['PHP_SELF']?>?page=<?=$next?>' disabled><?=$next?></a>
          <a href='<?php echo $_SERVER['PHP_SELF']?>?page=<?=$page_count?>' disabled><i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
  <?php }
      }
    }
  }

?>
  </div>

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
        <input class="add-input" id="task_name1" name="task_name" type="text" placeholder="Наименование">
        <button id="btn_add1" class="add" type="submit"><i class="fa fa-plus" aria-hidden="true"></i></button>

      </div>      


      <div class="task-header-container"> 
        <div class="add-content">
            <div class="task-header"> 
              <input class="add-input" id="task_name" name="task_name" type="text" placeholder="Наименование">
              <button id="btn_add" class="add" type="submit"><i class="fa fa-plus" aria-hidden="true"></i></button>
            </div>             
        </div>
        <div class="add-button">  
            <button id="open-add">Тыкаешь и всплывает добавление</button>
        </div>      
      </div>


        <br><br>
      <button id="open1">Добавление через модалку</button> 

      <div class="task-body">
        <div class="task_null">На этой страничке кончились задачки:)</div>

        <?php /*if ($task_count > 0) {*/ 
          foreach ($task as $tsk) : ?>
            <div class="task-container" id="<?=$tsk['task_id']?>">
              <!--<label class="task-label"><?=$tsk['task_name']?></label>-->
              <!--<input type="text" class="task-label" value="<?=$tsk['task_name']?>" readonly>-->
              <textarea class="task-label" readonly><?=$tsk['task_name']?></textarea>
              <button class="update"><i class="fa fa-pencil" aria-hidden="true"></i></button>
              <button class="delete"><i class="fa fa-minus" aria-hidden="true"></i></button>
            </div>

        <?php endforeach; /*}*/ ?>         
      </div>
    </div>
    <div class="task-page-container">
      <div class="task-page-content">
        <?php
          for ($i=1; $i < $page_count+1; $i++) {     
            if ($i == $page_nom) {
              if ($i == 1) {
                if ($page_count == 1) {
                  if ($page_count > 1) {
                    $next = $i + 1; ?>
            <?php }else{ ?>
                    <span><i class="fa fa-angle-double-left" aria-hidden="true"></i></span>
                    <span><i class="fa fa-angle-left" aria-hidden="true"></i></span>
                    <span><?=$i?></span>
                    <span><i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
                    <span><i class="fa fa-angle-right" aria-hidden="true"></i></span>
            <?php } 
                }else{
                  $next = $i + 1; ?>
                  <span><i class="fa fa-angle-double-left" aria-hidden="true"></i></span>
                  <span><i class="fa fa-angle-left" aria-hidden="true"></i></span>
                  <span><?=$i?></span>
                  <a href='<?php echo $_SERVER['PHP_SELF']?>?page=<?=$next?>'><?=$next?></a>
                  <a href='<?php echo $_SERVER['PHP_SELF']?>?page=<?=$page_count?>'><i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
          <?php } ?>
        <?php }else if ($page_count > 1) {
                if ($i == $page_count) {
                  $back = $i - 1; ?>
                  <a href='<?php echo $_SERVER['PHP_SELF']?>?page=<?=1?>'><i class="fa fa-angle-double-left" aria-hidden="true"></i></a>
                  <a href='<?php echo $_SERVER['PHP_SELF']?>?page=<?=$back?>'><?=$back?></a>
                  <span><?=$i?></span>
                  <span><i class="fa fa-angle-right" aria-hidden="true"></i></span>
                  <span><i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
          <?php }else{
                  $back = $i - 1;
                  $back2 = $i - 2;
                  $next = $i + 1;
                  $next2 = $i + 2; ?>
                  <a href='<?php echo $_SERVER['PHP_SELF']?>?page=<?=1?>'><i class="fa fa-angle-double-left" aria-hidden="true"></i></a>
                  <a href='<?php echo $_SERVER['PHP_SELF']?>?page=<?=$back?>'><?=$back?></a>
                  <span><?=$i?></span>
                  <a href='<?php echo $_SERVER['PHP_SELF']?>?page=<?=$next?>'><?=$next?></a>
                  <a href='<?php echo $_SERVER['PHP_SELF']?>?page=<?=$page_count?>'><i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
          <?php }
              }
            }
          }
        ?>
      </div>
    </div>
    <div class="task-page-container">
      <div class="task-page-content">
        <?php for ($i=1; $i < $page_count+1; $i++) {     
          if ($i == $page_nom) {
            if ($i == 1) {
              $next = $i + 1; ?>
              <span><i class="fa fa-angle-double-left" aria-hidden="true"></i></span>
              <span><i class="fa fa-angle-left" aria-hidden="true"></i></span>
              <span><?=$i?></span>
              <a href='<?php echo $_SERVER['PHP_SELF']?>?page=<?=$next?>'><i class="fa fa-angle-right" aria-hidden="true"></i></a>
              <a href='<?php echo $_SERVER['PHP_SELF']?>?page=<?=$page_count?>'><i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
           <?php }else if ($page_count > 1) {
              if ($i == $page_count) {
                $back = $i - 1; ?>
                <a href='<?php echo $_SERVER['PHP_SELF']?>?page=<?=1?>'><i class="fa fa-angle-double-left" aria-hidden="true"></i></a>
                <a href='<?php echo $_SERVER['PHP_SELF']?>?page=<?=$back?>'><i class="fa fa-angle-left" aria-hidden="true"></i></a>
                <span><?=$i?></span>
                <span><i class="fa fa-angle-right" aria-hidden="true"></i></span>
                <span><i class="fa fa-angle-double-right" aria-hidden="true"></i></span>
             <?php }else{ 
                $back = $i - 1;
                $next = $i + 1; ?>
                <a href='<?php echo $_SERVER['PHP_SELF']?>?page=<?=1?>'><i class="fa fa-angle-double-left" aria-hidden="true"></i></a>
                <a href='<?php echo $_SERVER['PHP_SELF']?>?page=<?=$back?>'><i class="fa fa-angle-left" aria-hidden="true"></i></a>
                <span><?=$i?></span>
                <a href='<?php echo $_SERVER['PHP_SELF']?>?page=<?=$next?>'><i class="fa fa-angle-right" aria-hidden="true"></i></a>
                <a href='<?php echo $_SERVER['PHP_SELF']?>?page=<?=$page_count?>'><i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
             <?php }
            }
          }
        } ?>
      </div>
    </div>
    <div class="task-page-container">      
      <div class="task-page-content">
        <? for ($i=1; $i < $page_count+1; $i++) {
            if ($i  == $page_nom) { 
              echo "<span>$i</span>";
            }else{ 
              $a = $i; ?>
              <a href='<?php echo $_SERVER['PHP_SELF']?>?page=<?=$a?>' disabled><?=$i?></a>
           <? } } ?>
      </div>          
    </div>
  </div>

    
  <div id="dialog1" class="dialog-container">  
    <div class="dialog">
      <div class="task-header">
        <input class="add-input" id="task_name1" name="task_name" type="text" placeholder="Наименование">
        <button id="btn_add1" class="add" type="submit"><i class="fa fa-plus" aria-hidden="true"></i></button>  
      </div>
      <p>первое</p>
      <button id="close1">Закрыть</button>
    </div>
  </div>

  <div id="dialog2" class="dialog-container">  
    <div class="dialog" id="dialog228">
      <p>второе</p>
      <form id="formac">
        <input type="text" name="alerta" id="alertaa">
        <button id="ajax2" type="submit">Сообщение</button>
      </form>
      <input type="text" class="error">
      <button id="close2">Закрыть</button>
    </div>
  </div>

  <div class="dialog-fon">
  </div>

  <?php echo $_GET['alerta']; ?>


  <div>
      <button id="open2">Открыть 2</button>
    </div>
  
  
    
  <a href="jspractice.php">practice</a>

<script src="js/jquery.js"></script>
<script type="text/javascript">
  $(document).ready(function(){


    /* --- удаляение гет в url --- */
    var newURL = location.href.split("?")[0];
    window.history.pushState('object', document.title, newURL);


    /* --- тест формы в модалке -- */
  $('#ajax2').on('click', function(){
    var parent = $(this).parent();//для удаления
    var parpar = this.parentNode.parentNode.id;//род контейнер
    var form_id = this.parentNode.id;//для получения значений в форме
    var alerta = $('#' + form_id + ' input').val();    
    //parent.remove(); 
    
    if (alerta == '') {
      $('#' + parpar + ' .error').val('пусто');
      return false;
    }else{
      //history.pushState(null, null, "?alert123=" + alerta);
      return true;
    } 
  });


  /* ---- всплывающее добавление ---- */
    $('#open-add').on('click', function(){
      $(this).css('display', 'none');
      $('.add-content').css('animation', 'add-content-open 1s ease-in-out');
      $('.add-content').css('display', 'block');
    });

    $('#btn_add').on('click', function(){
      
      $('.add-content').css('animation', 'add-content-close 1s ease-in-out');
      setTimeout(function(){
          $('.add-content').css('display', 'none');
          $('#open-add').css('display', 'block');
      }, 1000);
      
    });


  /* ------- модальное окно ---------- */

    /*setTimeout(function(){
        $('.dialog-fon').css('display','none');  
      },1000);*/

    /* ----- первое ----- */
    $('#open1').on('click', function(){
      $('.dialog-fon').css('display','block');
      $('#dialog1').css("animation", "modal_open 0.5s ease-in-out");
      $('#dialog1').css('display','block');
    });

    $('.dialog-fon').on('click', function(){       
      $('#dialog1').css("animation", "modal_close 0.5s ease-in-out");
      setTimeout(function(){
        $('#dialog1').css('display','none');
        $('.dialog-fon').css('display','none'); 
      }, 500); 
    });

    $('#close1').on('click', function(){
      $('#dialog1').css("animation", "modal_close 0.5s ease-in-out");
      setTimeout(function(){
        $('#dialog1').css('display','none');
        $('.dialog-fon').css('display','none'); 
      }, 500);
    });


    /* ----- Второе ----- */
   $('#open2').on('click', function(){
      $('.dialog-fon').css('display','block');
      $('#dialog2').css("animation", "modal_open 0.5s ease-in-out");
      $('#dialog2').css('display','block');
    });

    $('.dialog-fon').on('click', function(){       
      $('#dialog2').css("animation", "modal_close 0.5s ease-in-out");
      setTimeout(function(){
        $('#dialog2').css('display','none');
        $('.dialog-fon').css('display','none'); 
      }, 500); 
    });

    $('#close2').on('click', function(){
      $('#dialog2').css("animation", "modal_close 0.5s ease-in-out");
      setTimeout(function(){
        $('#dialog2').css('display','none');
        $('.dialog-fon').css('display','none'); 
      }, 500);
    });



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
        var task_value_old = $('#' + container_id + ' textarea').text();

        $('#' + container_id + ' textarea').removeClass('task-label').addClass('task-update');
        $('#' + container_id + ' textarea').attr('readonly', false);
        $('#' + container_id + ' textarea').focus();

        $('#' + container_id + ' textarea').blur(function(){

          if ($('#' + container_id + ' textarea').attr('readonly')) {   
            return false;
          }

          var task_value_new = $('#' + container_id + ' textarea').val();

          if (task_value_old == task_value_new) {                        
            $('#' + container_id + ' textarea').attr('readonly', true);
            $('#' + container_id + ' textarea').removeClass('task-update').addClass('task-label');
            //alert('одинаковые');
            return false;
          }else if (task_value_new == '') {
            $('#' + container_id + ' textarea').val(task_value_old);
            $('#' + container_id + ' textarea').attr('readonly', true);
            $('#' + container_id + ' textarea').removeClass('task-update').addClass('task-label');
            //alert('пустое');
            return false;
          }else{
            $.ajax({
              type: 'POST',
              url: 'task_update.php',
              data: {task_id: container_id, task_name:task_value_new},
              success: function(result){
                var json = $.parseJSON(result);

                if (json.result !='2') {
                  //alert(json.result);
                  $('#' + container_id + ' textarea').val($.trim(task_value_old));
                  $('#' + container_id + ' textarea').removeClass('task-update').addClass('task-label');
                  $('#' + container_id + ' textarea').attr('readonly', true);
                  return false;
                }
                //alert('если нет ошибок ' + json.result);

                $('#' + container_id + ' textarea').val($.trim(task_value_new));
                $('#' + container_id + ' textarea').text($.trim(task_value_new));
                task_value_old = task_value_new;
                $('#' + container_id + ' textarea').attr('readonly', true);
                $('#' + container_id + ' textarea').removeClass('task-update').addClass('task-label');
              },
              error: function(){
                //alert('Что то не получилось');
                $('#' + container_id + ' textarea').val($.trim(task_value_old));
                $('#' + container_id + ' textarea').attr('readonly', true);
                $('#' + container_id + ' textarea').removeClass('task-update').addClass('task-label');
              }
            }); 
          }
        });
      });
    }
    add_update();
    

    /* -- удаление тасок --- */
    function add_delete(){
      $('.delete').on('click', function(){
        var containter = $(this).parent();//attr('id')//получаем род контейнер 
        alert($(this).attr)
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
            //alert('Вы не заполнили название');
            return false;
          }else if(json.result == '2'){
            task_body.append('<div class="task-container" id='+ json.id +'><textarea class="task-label" readonly>' + task_name + '</textarea>' + 
            '<button class="update"><i class="fa fa-pencil" aria-hidden="true"></i></button>' +
            '<button class="delete"><i class="fa fa-minus" aria-hidden="true"></i></button></div>');
            check_list();
            $('#task_name').val('');
            $('#' + json.id).css("animation", "task_add 1s ease-in-out");
            add_delete();

            /* --- добавляем редактирование ---- */
            $('#'+ json.id +' .update').on('click', function(){
              var container_id = json.id;
              var task_value_old = $('#' + json.id + ' textarea').text();

              $('#' + container_id + ' textarea').removeClass('task-label').addClass('task-update');
              $('#' + container_id + ' textarea').attr('readonly', false);
              $('#' + container_id + ' textarea').focus();

              $('#' + container_id + ' textarea').blur(function(){

                if ($('#' + container_id + ' textarea').attr('readonly')) {  
                  return false;
                }

                var task_value_new = $('#' + container_id + ' textarea').val();

                if (task_value_old == task_value_new) {                        
                  $('#' + container_id + ' textarea').attr('readonly', true);
                  $('#' + container_id + ' textarea').removeClass('task-update').addClass('task-label');
                  //alert('одинаковые ' + json.id);
                  return false;
                }else if (task_value_new == '') {
                  $('#' + container_id + ' textarea').val(task_value_old);
                  $('#' + container_id + ' textarea').attr('readonly', true);
                  $('#' + container_id + ' textarea').removeClass('task-update').addClass('task-label');
                  //alert('пустое ' + json.id);
                  return false;
                }else{
                  $.ajax({
                    type: 'POST',
                    url: 'task_update.php',
                    data: {task_id: container_id, task_name:task_value_new},
                    success: function(result){
                      var json = $.parseJSON(result);

                      if (json.result !='2') {
                        //alert(json.result);
                        $('#' + container_id + ' textarea').val($.trim(task_value_old));
                        $('#' + container_id + ' textarea').removeClass('task-update').addClass('task-label');
                        $('#' + container_id + ' textarea').attr('readonly', true);
                        return false;
                      }
                      //alert('если нет ошибок ' + json.result);

                      $('#' + container_id + ' textarea').val($.trim(task_value_new));
                      $('#' + container_id + ' textarea').text($.trim(task_value_new));                      
                      task_value_old = task_value_new;
                      $('#' + container_id + ' textarea').attr('readonly', true);
                      $('#' + container_id + ' textarea').removeClass('task-update').addClass('task-label');
                    },
                    error: function(){
                      //alert('Что то не получилось');
                      $('#' + container_id + ' textarea').val($.trim(task_value_old));
                      $('#' + container_id + ' textarea').attr('readonly', true);
                      $('#' + container_id + ' textarea').removeClass('task-update').addClass('task-label');
                    }
                  });
                }

              });

            });
          }
        },
        error: function(){
          alert('Что то не получилось');
        }
      });
    });

    /* ----- комментари для js ------------

    var newURL = location.href.split("?")[0];
    window.history.pushState('object', document.title, newURL);

    var parent = $(this).parent();//для удаления
    var parpar = this.parentNode.parentNode.id;//род контейнер
    var form_id = this.parentNode.id;//для получения значений в форме
    //history.pushState(null, null, "?alert123=" + alerta);

    task_body.append('<div class="task-container" id='+ json.id +'><input type="text" class="task-label" value="' + task_name + '" readonly><textarea class="task-label" readonly>' + task_name + '</textarea>' + 
    '<button class="update"><i class="fa fa-pencil" aria-hidden="true"></i></button>' +
    '<button class="delete"><i class="fa fa-minus" aria-hidden="true"></i></button></div>');

    var containter = $(this).parent();//attr('id')//получаем род контейнер

    //$("p").removeClass("myClass noClass").addClass("yourClass");
    //var task_value_old = $('#' + container_id + ' input.task-label').val();
    //$('#' + container_id + ' input.task-label').remove();
    //$('#' + container_id).prepend('<input type="text" class="task-update" value='+task_value_old+'>');

    var container_id = this.parentNode.id;
    var task_value_old = $('#' + container_id + ' textarea').text(); 

    */

    /*$('#' + container_id + ' textarea').text(task_value_new);
    $('#' + container_id + ' textarea').val(task_value_new);
    $('#' + container_id + ' textarea').attr('readonly', true);
    $('#' + container_id + ' textarea').removeClass('task-update').addClass('task-label');
    alert('Старое: ' + task_value_old + '; Новое: ' + task_value_new);
    task_value_old = task_value_new;
    $('#' + container_id + ' input.task-label').blur(function(){});*/

  });
</script>
</body>
</html>

