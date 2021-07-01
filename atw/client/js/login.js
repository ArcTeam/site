$('[name=submit]').on('click', function (e) {
  form = $("#loginForm");
  isvalidate = $(form)[0].checkValidity()
  if (isvalidate) {
    e.preventDefault()
    dati={}
    dati.email=$("[name=email]").val()
    dati.password=$("[name=password]").val()
    postData("login.php", dati, function(data){
      console.log(data);
      if(data.indexOf('Errore!') != -1){
        classe='text-danger';
      } else {
        classe='text-success';
        var sec = 3;
        setInterval(function(){
          sec--;
          if(sec == 0){ window.location.href='index.php'; }
        },1000);
      }
      $(".outMsg").html(data).removeClass('text-danger text-success d-none').addClass(classe + " d-block").fadeIn('fast');
    })
  }
})
