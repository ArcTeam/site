classRubricaSelect();
$(".collapseBtn").text(btnListTxt);
$('[name=submit]').on('click', function (e) {
  form = $("#formRubrica");
  isvalidate = $(form)[0].checkValidity()
  if (isvalidate) {
    e.preventDefault()
    dati={};
    dati.classe = $("#classe").val();
    dati.nome = $("#nome").val();
    dati.email = $("#email").val();
    dati.mobile = $("#mobile").val();
    dati.telefono = $("#telefono").val();
    dati.cod_fisc = $("#cod_fisc").val();
    dati.piva = $("#piva").val();
    dati.indirizzo = $("#indirizzo").val();
    dati.web = $("#web").val();
    dati.note = $("#note").val();
    dati.trigger="addRubrica";
    console.log(dati);
    postData("utenti.php", {dati}, function(data){
      if (data === true) {
        $(".toast").removeClass('[class^="bg-"]').addClass('bg-success');
        $(".toast>.toast-body").text('contatto inserito correttamente');
        $(".toast").toast({delay:3000});
        $(".toast").toast('show');
        setTimeout(function(){
          $.redirectPost('rubrica.php');
        },3000);
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
