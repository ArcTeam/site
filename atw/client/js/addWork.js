direttoreSelect()
$('#inizio').val(new Date().toDateInputValue());
if (device == true) { getLocation(); }else { console.log('no mobile detect');}
$("#comuneLabel").autocomplete({
  source: '../api/json/comuni.php',
  minLength: 2,
  select : setComuneVal
});
function setComuneVal(event,ui){ $("[name=comune]").val(ui.item.id) }

$("#comune-reset").on('click', function(event) { $("#comuneLabel, #comune").val(''); });
$('[name=submit]').on('click', function (e) {
  form = $("#formScavo");
  isvalidate = $(form)[0].checkValidity()
  if (isvalidate) {
    e.preventDefault()
    dati={};
    dati.scavo={};
    dati.localizzazione={};
    dati.config={};
    dati.scavo.nome = $("[name=nome]").val();
    dati.scavo.sigla = $("[name=sigla]").val();
    dati.scavo.descrizione = $("[name=descrizione]").val();
    dati.scavo.inizio = $("[name=inizio]").val();
    dati.scavo.ore = $("[name=ore]").val();
    dati.scavo.direttore = $("[name=direttore]").val();
    if($("[name=lon]").val()){dati.localizzazione.lon = $("[name=lon]").val();}
    if($("[name=lat]").val()){dati.localizzazione.lat = $("[name=lat]").val();}
    if($("[name=comune]").val()){dati.localizzazione.comune = $("[name=comune]").val();}
    if($("[name=localizzazione]").val()){dati.localizzazione.localizzazione = $("[name=localizzazione]").val();}
    dati.config.inventario = $("[name=inventario]").val();
    dati.config.us = $("[name=us]").val();
    dati.config.reperto = $("[name=reperto]").val();
    dati.config.campione = $("[name=campione]").val();
    dati.config.sacchetto = $("[name=sacchetto]").val();
    dati.config.fotopiano = $("[name=fotopiano]").val();
    dati.config.tomba = $("[name=tomba]").val();
    dati.trigger="addWork";

    postData("lavoro.php", {dati}, function(data){
      console.log(data);
      if (data.res === true) {
        $(".toast").removeClass('[class^="bg-"]').addClass('bg-success');
        $(".toast-body").text('scavo inserito correttamente');
        $(".toast").toast({delay:3000});
        $(".toast").toast('show');
        setTimeout(function(){
          $.redirectPost('workPage.php', {id: data.id});
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
