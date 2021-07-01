const postId = $("[name=postId]").val();
scavoSelect();
userSelect();

$('[name=submit]').on('click', function (e) {
  form = $("#formFotopiano");
  isvalidate = $(form)[0].checkValidity()
  if (isvalidate) {
    e.preventDefault()
    dati={};
    dati.scavo = $("[name=scavo]").val();
    dati.data = $("[name=data]").val();
    dati.autore = $("[name=operatore]").val();
    dati.us = $("[name=us]").val();
    dati.note = $("[name=note]").val();
    dati.trigger="addFotopiano";
    postData("lavoro.php", {dati}, function(data){
      console.log(data);
      if (data.res === true) {
        $(".toast").removeClass('[class^="bg-"]').addClass('bg-success');
        let msg = "il fotopiano inserito è il numero:<br><span class='d-block' style='font-size:40px'>"+data.field.num_fotopiano+"</span><br>appena sei pronto chiudi questo messaggio!<br><i class='fas fa-hand-peace'></i> peace!"
        $(".toast-body").html(msg);
        $(".toast").toast({delay:3000});
        $(".toast").toast('show');
        $('.toast').on('hidden.bs.toast', function () {
          $.redirectPost('workPage.php', {id: dati.scavo});
        })
      }else {
        $(".toast").removeClass('[class^="bg-"]').addClass('bg-danger');
        $("#headerTxt").html('Errore nella query');
        $(".toast>.toast-body").html(data);
        $(".toast").toast({delay:3000});
        $(".toast").toast('show');
      }
    })
  }
})
