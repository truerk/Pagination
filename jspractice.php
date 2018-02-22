<?php
 echo $_GET['alerta']; 

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

  <div>
      <button id="open2">Открыть 2</button>
    </div>


  <div class="loadfile">
    <div>
      <button id="loadFile">Подгрузка</button>
    </div>  

    <div>
      <p>Подгрузка дока:</p>
      <p id="getFile"></p>
    </div>
  </div>

  <div class="loadfile">
    <div>
      <button id="loadfile1">Отправка get</button>
    </div>  

    <div>
      <p>get:</p>
      <p id="getFiles1"></p>
    </div>
  </div>

  <div class="loadfile">
    <div>
      <input type="text" id="name">
      <input type="text" id="age">
      <button id="loadfile2">Отправка post</button>
    </div>  

    <div>
      <p>post:</p>
      
    </div>
    <p id="getFiles2"></p>
  </div>








  <!-- модальные окна -->

  <div id="modal-1" class="modal-container">
    <div class="modal-content">
      <div class="tab-btn">
        <button id="btn-one" class="btn-tab">1</button><button id="btn-two" class="btn-tab">2</button>
      </div>      
      <div id="content" class="tab-content"></div>
    </div>
  </div>

  <div class="modal-fone"></div>


  <div class="center">
    <button id="open_modal">Модалка</button>
  </div>


  


<script src="js/jquery.js"></script>
<script type="text/javascript"> 

  /*  
  
    jQuery(function() {
      console.log(document === this) // true
      var testVar = 123;
    }) 
  
   */

  /* ---- вкладки --- */

  $('#btn-one').on('click', function(){

    $('#btn-two').removeClass('btn-tab-active');
    $(this).addClass('btn-tab-active');
    var request = new XMLHttpRequest();
    request.open('GET','content1.txt', false);
    request.setRequestHeader("Content-Type", "text/plain;charset=windows-1251");
    request.send();
    console.log(request);
    if (request.status == 200) {
      $('#content').text(request.responseText);
    }else{
      alert('Ошибка: ' + request.status + ', ' + request.statusText);
    }
  });
  $('#btn-two').on('click', function(){

    $('#btn-one').removeClass('btn-tab-active');
    $(this).addClass('btn-tab-active');
    var request = new XMLHttpRequest();
    request.open('GET','content2.txt', false);
    request.setRequestHeader("Content-Type", "text/plain;charset=windows-1251");
    request.send();
    console.log(request);
    if (request.status == 200) {
      $('#content').text(request.responseText);
    }else{
      alert('Ошибка: ' + request.status + ', ' + request.statusText);
    }
  });





  /* ---- модалка --- */
  $('#open_modal').on('click', function(){
    $('#modal-1').css('animation', 'modal-open 0.6s ease-in-out');
    $('.modal-fone').css('display', 'block');      
    $('#modal-1').css('display', 'block'); 
  });

  $('.modal-fone').on('click', function(){
    $('#modal-1').css('animation', 'modal-close 0.6s ease-in-out');
    setTimeout(function(){
      $('.modal-fone').css('display', 'none');
      $('#modal-1').css('display', 'none');
    },600);    
  });












  //[style="display:none"]
  /* ---- querySelector --- */

  /*var elem = document.getElementById('#primer');
  elem.querySelectorAll(css);//вернет все элементы внутри elem по заданным параметрам css
  elem.querySelector(css);//возвращает первый элемент по заданым css внутри elem*/

    /* --- кросс доменный запрос --- */

    //Для разрешение запроса, сервер должен в ответ отправить Access-Control-Allow-Origin: домен

    //var request = ('onload' in new XMLHttpRequest()) ? XMLHttpRequest : XDomainRequest;//если ie8,9 то xdomain
    //request.withCredentials = true;//позволяет отправить куки и http авториз//Access-Control-Allow-Credentials: true
    //request.open('post','google.com', true);

  /* --- синхронные запросы на нативном --- */

    /* --- php методом post асинхроно --- */
    var postBtn = document.getElementById('loadfile2');
    postBtn.addEventListener('click', function(){      
      var request = new XMLHttpRequest();
      request.open('POST', 'info.php', true);
      var params = 'name=' + encodeURIComponent(document.getElementById('name').value) + 
                  '&age=' + encodeURIComponent(document.getElementById('age').value);//указываем данные пост запроса
      request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded; charset=utf-8');//отправляем заголовок, сод кодировку/multipart/form-data /text/plain
      request.send(params);//post параметры в send
      console.log(request);
      request.timeout = 1500;//устанавливает максимальную продолжительность асин запроса
      request.ontimeout = function(){//если время запроса превысило заданное время выполнения запроса(timeout)
        alert('соре слишком долго');
      }
      request.onreadystatechange = function(){//для ответа асинх запроса
        if (request.readyState == 4) {// 0 начальное / 1 вызван open / 2 заголовки / 3 выполнение запроса / 4 запрос завершен
          if (request.status == 200) {
            $('#getFiles2').text(request.responseText);
          }
        }else{
          return false;
        } 
      }

      /*
      request.abort();//прерывает запрос
      request.getResponseHeader(name);//возвращает значение заголовка
      request.getAllResponseheaders();//возврашает все заголовки  */    
    });


    /* --- php методом post --- */
    //в посте параметры указываем в send
    /*var postBtn = document.getElementById('loadfile2');
    postBtn.addEventListener('click', function(){
      var request = new XMLHttpRequest();
      request.open('POST', 'info.php', true);
      var params = 'name=' + encodeURIComponent(document.getElementById('name').value) + 
                  '&age=' + encodeURIComponent(document.getElementById('age').value);//указываем данные пост запроса
      request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');//отправляем заголовок, сод кодировку
      request.send(params);//post параметры в send
      console.log(request);
      //request.onreadystatechange = function(){}

      if (request.status == 200) {
        $('#getFiles2').text(request.responseText);
      }
      alert('dsfs');
    });*/

    /* --- php методом get --- */
    $('#loadfile1').on('click', function(){
      var request = new XMLHttpRequest();
      request.open('GET', 'hello.php?hello=hello', false);
      request.send();
      if (request.status == 200) {
        $('#getFiles1').text(request.responseText);
      }
    });

    /* --- подгрузка доков --- */
    $('#loadFile').on('click', function(){
      var par = document.getElementById('getFile');
      var request = new XMLHttpRequest();//создаем экземляр запроса
      request.open('GET', 'hello.txt', false);// тип запроса / файл / false(синхр) / true(асинхр) 
      request.send();//отправка запроса//получаем ответ в виде status(статус запроса) и responseText(ответ запроса)
      console.log(request);
      //request.abort();//прерывает запрос
      if (request.status == 200) {//обрабатываем стату запроса, если 200 значит успешно
        //$('#getFile').text(request.responseText);
        //$('#getFile').append(request.responseText);
        par.innerHTML = request.responseText;
      }else{
        par.innerHTML = request.statusText;//текст статуса
      }
    });

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

</script>
</body>
</html>