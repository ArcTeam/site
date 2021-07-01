const model = 'sacchetti.php';
const scavo = $("[name=postId]").val();
const tipoRecord = $("[name=tipo]").val();
const config = getConfig();
var list = {scavoObj,userObj}

let title;
let tipoId;
if (postId) {
  backurl = 'workPage.php';
  backData = {id:postId};
}else {
  backurl = 'index.php';
  backData = {};
}
$(".backBtn").on('click', function() { $.redirectPost(backurl, backData);});
switch (tipoRecord) {
  case 'rr':
    title = 'reperto';
    tipoId = 2;
    list={scavoObj,userObj,tipoRepertoObj,materialeRepertoObj};
    $(".form-row > .rr").show();
  break;
  case 'sg':
    title = 'sacchetto generico';
    tipoId = 3;
    $(".form-row > .rr").remove();
  break;
  case 'cp':
    title = 'campione';
    tipoId = 1;
    list={scavoObj,userObj,tipoCampioneObj};
    $(".form-row > .divMateriale").remove();
    $(".form-row > .cp").show();
  break;
}
getList(list);
postData(model, {dati:{trigger:'numeroLiberoSacchetto',scavo:scavo, tipoId:tipoId}}, function(data){
  $(".nextInventario").text(data.inventario);
  $(".nextSacchetto").text(data.numero);
});

postData(model, {dati:{trigger:'usList',scavo:scavo}}, function(data){
  data.forEach(function(v,i){
    $("<option/>",{value:v.id, text:v.us+" - "+v.definizione}).appendTo('#us');
    // $("<option/>",{value:v.us+" - "+v.definizione}).attr("data-value",v.id).appendTo('#usList');
  })
})

$(".pageObject").text(title);

$('[name=submit]').on('click', function (e) {
  form = $("#formReperto");
  isvalidate = $(form)[0].checkValidity()
  if (isvalidate) {
    e.preventDefault()
    dati={};
    dati.scavo = scavo;
    dati.tipoId = tipoId;
    dati.trigger="addReperto";
    dati.sacchetto={};
    dati.sacchetto.scavo = scavo;
    dati.sacchetto.tipologia = tipoId;
    dati.sacchetto.data = $("[name=data]").val();
    dati.sacchetto.compilatore = $("[name=compilatore]").val();
    dati.sacchetto.us = $("[name=us]").val();
    dati.sacchetto.descrizione = $("[name=descrizione]").val();
    if (tipoId==2) {
      dati.reperto={};
      dati.reperto.materiale = $("[name=materiale]").val();
      dati.reperto.tipologia = $("[name=tipologia]").val();
    }
    if (tipoId == 1) {
      dati.campione={};
      dati.campione.tipologia = $("[name=tipologia]").val();
    }
    postData(model, {dati}, function(data){
      console.log(data);
      if (data.res === true) {
        $(".toast").removeClass('[class^="bg-"]').addClass('bg-success');
        let msg = "I dati del record appena inserito sono i seguenti:<br> <strong>inventario: </strong>"+data.inventario+"<br><strong>numero: </strong>"+data.numero+"<br>appena sei pronto chiudi questo messaggio!<br><i class='fas fa-hand-peace'></i> peace!"
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
