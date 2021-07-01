const usConnector = 'us.php';
const usId = $("[name=usId]").val() ? $("[name=usId]").val() : null;
const list = {scavoObj,userObj,tipoUsObj, formazioneUsObj, consistenzaUsObj, modalitaUsObj, conservazioneUsObj, affidabilitaUsObj}
var tipoUS = $("[name=tipoUS]").val();

if (postId) {
  backText = 'back to site';
  backurl = 'workPage.php';
  backData = {id:postId};
}else {
  backText = 'back to dashboard';
  backurl = 'index.php';
  backData = {};
}
if (usId) {
  submitText = 'Aggiorna dati';
  $("#printBtn").show();
  trigger = 'updateUs';
}else {
  submitText = 'Salva dati';
  $("#printBtn").hide();
  trigger = 'addUs';
}

$(".backBtn").on('click', function() { $.redirectPost(backurl, backData);});
$("#backBtn > span").text(backText);
if (tipoUS>0) { $(".hiddenField").fadeIn('fast'); }else { $(".hiddenField").hide(); }

toggleField(usId,tipoUS);
getList(list);
getRapporti(tipoUS, function(group){
  buildRapportiForm($("#contemporaneiDiv"),group[1]);
  buildRapportiForm($("#anterioriDiv"),group[2]);
  buildRapportiForm($("#posterioriDiv"),group[3]);
  $("#alertRapporti").fadeOut('fast');
  $("#rapportiWrap").fadeIn('fast');
});

$("[type=checkbox][name=formazione4]").on('click', function() {
  if ($(this).is(":checked")) {
    $("[name=formazione1],[name=formazione2],[name=formazione3]").prop('checked', false).prop('disabled',true);
  }else {
    $("[name=formazione1],[name=formazione2],[name=formazione3]").prop('disabled',false);
  }
});
$("#rapportiWrap").hide();
$("body").on('change', "[name=tipo]", function(event) {
  var tipo = $(this).val();
  toggleField(usId, tipo);
  getRapporti(tipo, function(group){
    buildRapportiForm($("#contemporaneiDiv"),group[1]);
    buildRapportiForm($("#anterioriDiv"),group[2]);
    buildRapportiForm($("#posterioriDiv"),group[3]);
  });
  $("#alertRapporti").fadeOut('fast');
  $("#rapportiWrap").fadeIn('fast');
  $(".hiddenField").fadeIn('fast');
})
if (usId) {
  $("main > .alert").remove();
  datiUs(usId);
}
$('[name=submit]').text(submitText).on('click', function (e) {
  form = $("#formUs");
  isvalidate = $(form)[0].checkValidity()
  if (isvalidate) {
    e.preventDefault()
    dati={};
    dati.trigger=trigger;

    dati.us={};
    if (usId) {dati.us.id = usId;}
    dati.us.scavo = $("[name=scavo]").val();
    dati.us.tipo = $("[name=tipo]").val();
    dati.us.compilazione = $("[name=compilazione]").val();
    dati.us.compilatore = $("[name=compilatore]").val();
    dati.us.definizione = $("[name=definizione]").val();

    dati.us_area={};
    if ($("[name=area]").val()) {dati.us_area.area = $("[name=area]").val()}
    if ($("[name=saggio]").val()) {dati.us_area.saggio = $("[name=saggio]").val()}
    if ($("[name=settore]").val()) {dati.us_area.settore = $("[name=settore]").val()}
    if ($("[name=ambiente]").val()) {dati.us_area.ambiente = $("[name=ambiente]").val()}
    if ($("[name=quadrato]").val()) {dati.us_area.quadrato = $("[name=quadrato]").val()}

    dati.us_info={};
    if($("[name=descrizione]").val()){dati.us_info.descrizione = $("[name=descrizione]").val()}
    var criterio = $("input[name=criterio]:checked").map(function(){return this.value;}).get();
    if(criterio.length > 0){dati.us_info.criterio = criterio.join(',');}
    if ($("input[name=formazione4]").filter(":checked").length > 0) {
      dati.us_info.formazione = $("input[name=formazione4]:checked").val();
    }else {
      formazione = [];
      if ($("[name=formazione1]:checked").length > 0){formazione.push($("[name=formazione1]:checked").val())}
      if ($("[name=formazione2]:checked").length > 0){formazione.push($("[name=formazione2]:checked").val())}
      if ($("[name=formazione3]:checked").length > 0){formazione.push($("[name=formazione3]:checked").val())};
      if (formazione.length > 0) {dati.us_info.formazione = formazione.join(',');}
    }
    if($("[name=consistenza]").val()){dati.us_info.consistenza = $("[name=consistenza]").val()}
    if($("[name=colore]").val()){dati.us_info.colore = $("[name=colore]").val()}
    if($("[name=foto_digitali]").val()){dati.us_info.foto_digitali = Boolean($("[name=foto_digitali]").val())}
    if($("[name=modalita]").val()){dati.us_info.modalita = $("[name=modalita]").val()}
    if($("[name=conservazione]").val()){dati.us_info.conservazione = $("[name=conservazione]").val()}
    if($("[name=affidabilita]").val()){dati.us_info.affidabilita = $("[name=affidabilita]").val()}
    if($("[name=osservazioni]").val()){dati.us_info.osservazioni = $("[name=osservazioni]").val()}
    if($("[name=interpretazione]").val()){dati.us_info.interpretazione = $("[name=interpretazione]").val()}
    if($("[name=elem_datanti]").val()){dati.us_info.elem_datanti = $("[name=elem_datanti]").val()}
    if($("[name=datazione]").val()){dati.us_info.datazione = $("[name=datazione]").val()}
    if($("[name=periodo]").val()){dati.us_info.periodo = $("[name=periodo]").val()}
    if($("[name=dati_quantit]").val()){dati.us_info.dati_quantit = $("[name=dati_quantit]").val()}

    dati.componenti = {}
    if($("[name=geologici]").val()){dati.componenti.geologici = $("[name=geologici]").val()}
    if($("[name=organici]").val()){dati.componenti.organici = $("[name=organici]").val()}
    if($("[name=artificiali]").val()){dati.componenti.artificiali = $("[name=artificiali]").val()}

    dati.rapporti = {}
    $("#rapportiSection").find('input').each(function(){
      if ($(this).val()) {
        dati.rapporti[$(this).prop('name').slice(9)] = $(this).val();
      }
    })
    console.log(dati);
    postData(usConnector, {dati}, function(data){
      if (data.res === true) {
        $(".toast").removeClass('[class^="bg-"]').addClass('bg-success');
        let usPrefix = dati.tipo == 2 ? '-' : '';
        let msg = !usId ? "l'unità stratigrafica inserita è la numero:<br><span class='d-block' style='font-size:40px'>"+usPrefix+data.us+"</span><br>appena sei pronto chiudi questo messaggio!<br><i class='fas fa-hand-peace'></i> peace&love!" : "la scheda è stata correttamente aggiornata<br><i class='fas fa-hand-peace'></i> peace&love!";
        $(".toast-body").html(msg);
        $(".toast").toast({delay:3000});
        $(".toast").toast('show');
        $('.toast').on('hidden.bs.toast', function () {
          if (usId) {
            location.reload();
          }else {
            $.redirectPost('workPage.php', {id: dati.us.scavo});
          }
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
