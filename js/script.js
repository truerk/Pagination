$(function () {
  var $tasksList = $("#tasksList");
  var $taskInput = $("#taskInput");
  var $notification = $("#notification");

  var displayNotification = function(){//если нет эелемнтов показываем надпись
    if (!$tasksList.children().length) {//подсчитывает кол-во элементов
      $notification.fadeIn("fast");
    }else{
      $notification.css("display", "none");
    }
  }

  $("#taskAdd").on("click", function(){//при нажатие
    if (!$taskInput.val()) {      
      return false;
    }
    $tasksList.append("<li>" + $taskInput.val() + "<button class='delete'>&#10006</button></li>");//добавление

    $taskInput.val("");

    displayNotification();//проверка

    $(".delete").on("click", function(){//удаление обьектов
      var $parent = $(this).parent();

      $parent.css("animation", "fadeOut .3s linear");

      setTimeout(function(){
        $parent.remove();
        displayNotification();
      }, 300)
    })
  })
})