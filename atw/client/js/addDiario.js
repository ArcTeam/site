const scavo = $("[name=scavo]").val();
const operatore = $("[name=operatore]").val();
const diario = $("[name=diario]").val();
$(".backBtn").on('click', function() { $.redirectPost("workPage.php", {id:$("[name=scavo]").val()});});
if (diario) {
  $("#dataDiv").hide();
  postData("lavoro.php", {dati:{trigger:'setDiario', id:diario}}, function(data){
    $("#titleData").text(data[0]['data']);
    $("[name=descrizione]").val(data[0]['descrizione']);
  });
}
$('[name=submit]').on('click', function (e) {
  form = $("#formDiario");
  isvalidate = $(form)[0].checkValidity()
  if (isvalidate) {
    e.preventDefault()
    dati={};
    if (diario) {
      dati.diario = diario;
      dati.descrizione = $("[name=descrizione]").val();
      dati.trigger="updateDiario";
      var toast = 'diario modificato correttamente';
    }else {
      dati.scavo = scavo;
      dati.data = $("[name=data]").val();
      dati.operatore = operatore;
      dati.descrizione = $("[name=descrizione]").val();
      dati.trigger="addDiario";
      var toast = 'attività giornaliera inserita correttamente';
    }
    postData("lavoro.php", {dati}, function(data){
      if (data === true) {
        $(".toast").removeClass('[class^="bg-"]').addClass('bg-success');
        $(".toast>.toast-body").text(toast);
        $(".toast").toast({delay:3000});
        $(".toast").toast('show');
        setTimeout(function(){
          $.redirectPost('workPage.php', {id: scavo});
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
