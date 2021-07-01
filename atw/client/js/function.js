const API = "../api/";
const toolTipOpt = {container: 'body', boundary: 'windows', selector: '[data-toggle=tooltip]', html: true}
const device = isMobile();
const postId = $("[name=postId]").val();
const usrAct = $("[name=usrAct]").val();
const scavoObj = { tab:'scavo', orderBy:'nome_lungo asc', sel:'select#scavo'};
const userObj = { tab:'users', orderBy:'cognome asc, nome asc', sel:'select#compilatore'};
const tipoUsObj = { tab:'list.tipo_us', orderBy:'id asc', sel:'select#tipo'};
const formazioneUsObj = { tab:'list.formazione_us', orderBy:'id asc', sel:'select#formazione'};
const consistenzaUsObj = { tab:'list.consistenza_us', orderBy:'id asc', sel:'select#consistenza'};
const modalitaUsObj = { tab:'list.modalita_scavo_us', orderBy:'id asc', sel:'select#modalita'};
const conservazioneUsObj = { tab:'list.conservazione_us', orderBy:'id asc', sel:'select#conservazione'};
const affidabilitaUsObj = { tab:'list.affidabilita_us', orderBy:'id asc', sel:'select#affidabilita'};
const materialeRepertoObj = { tab:'list.materiale', orderBy:'value asc', sel:'select#materiale'};
const tipoRepertoObj = { tab:'list.tipologia', orderBy:'value asc', sel:'select#tipologia'};
const tipoCampioneObj = { tab:'list.tipo_campione', orderBy:'value asc', sel:'select#tipologia'};
var tipoUS;
let btnListTxt;

if (!Object.entries) {
  Object.entries = function( obj ){
    var ownProps = Object.keys( obj ), i = ownProps.length,resArray = new Array(i);
    while (i--)
      resArray[i] = [ownProps[i], obj[ownProps[i]]];
    return resArray;
  };
}

function getConfig() {
    var res = $.ajax({
      url: API+'sacchetti.php',
      type: 'POST',
      dataType: 'json',
      data: {dati:{trigger:'scavoConfig', scavo:scavo}},
      async: false
    });
    return res.responseJSON;
}

$(document).ready(function(){
  $("body").tooltip(toolTipOpt);

  $("[name=toggleNav]").on('click', function(){
    $("nav").toggleClass('open closed').toggleClass('shadow-lg');
  });

  if (screen.width < 1200) {
    btnListTxt = 'visualizza liste';
  }else {
    $("div.collapse").addClass('show')
    btnListTxt = 'nascondi liste';
  }

  $(".toggleList").text(btnListTxt).on('click', function(event) {
    target = $(this).data('target');
    if(!$(target).is(':visible')){
      $(this).text('nascondi liste');
    }else {
      $(this).text('visualizza liste');
    }
  })

  $(".collapseBtn").closest('button').click(function () {
    const $el = $(".collapseBtn");
    let testo = $el.text() == "visualizza" ? "nascondi": "visualizza"
    $el.text(testo);
  });
  activeLink();
});
function buildRapportiForm(div,group){
  var grp = '';
  let grpCol= 12 / group.length;
  group.forEach(function(v){
    let inputName = v.rapporto.replace(/\s+/g, '_')
    grp += "<div class='col-md-"+grpCol+"'>";
    grp += "<label for='"+inputName+"'>"+v.rapporto+"</label>";
    grp += "<input type='text' class='form-control form-control-sm' id='"+inputName+"' name='rapporto-"+v.id+"' value='' />";
    grp += "</div>";
  })
  div.html(grp);
}
function datiUs(id){
  dati={};
  dati.trigger="datiUs";
  dati.id = id;
  postData("us.php", {dati}, function(data){
    data.obbligatori.forEach(function(v,i){
      $("[name=compilazione]").val(v.compilazione);
      $("[name=compilatore] option[value="+v.compilatore+"]").prop("selected", true);
      $("[name=definizione]").val(v.definizione);
    });
    if (data.area) {
      for (const[k, v] of Object.entries(data.area)){$("[name="+k+"]").val(v);}
    }
    if (data.info) {
      if (data.info.criterio) {
        let criterio = data.info.criterio.split(',');
        for (c of criterio){
          if (c == 'consistenza') {$("#criterio_consistenza").prop('checked', true);}
          $("#"+c).prop('checked', true);
        }
      }
      if (data.info.formazione) {
        let formazione = data.info.formazione.replace(' ','_').split(',');
        for (f of formazione){$("#"+f).prop('checked', true); }
      }
      $("[name=consistenza] option[value="+data.info.consistenza+"]").prop("selected", true);
      $("[name=colore]").val(data.info.colore);
      $("[name=foto_digitali] option[value="+data.info.foto_digitali+"]").prop("selected", true);
      $("[name=modalita] option[value="+data.info.modalita+"]").prop("selected", true);
      $("[name=conservazione] option[value="+data.info.conservazione+"]").prop("selected", true);
      $("[name=affidabilita] option[value="+data.info.affidabilita+"]").prop("selected", true);
      $("[name=descrizione]").val(data.info.descrizione);
      $("[name=osservazioni]").val(data.info.osservazioni);
      $("[name=interpretazione]").val(data.info.interpretazione);
      $("[name=elem_datanti]").val(data.info.elem_datanti);
      $("[name=datazione]").val(data.info.datazione);
      $("[name=periodo]").val(data.info.periodo);
      $("[name=dati_quantit]").val(data.info.dati_quantit);
    }
    if (data.componenti) {
      for (const[k, v] of Object.entries(data.componenti)){ $("[name="+k+"]").val(v);}
    }
    if(data.rapporti.length > 0){
      for (const[k, v] of Object.entries(data.rapporti)){
        $("[name=rapporto-"+v.rapporto+"]").val(v.us2);
      }
    }
  });
}
function toggleField(id, tipoUS){
  var us,titleForm, classField, prefix;
  if (id) {
    prefix = tipoUS == 2 ? '-' : '';
    var dati={};
    dati.trigger="usNum";
    dati.id = id;
    postData("us.php", {dati}, function(data){
      titleForm = 'Stai modificando la scheda num. '+prefix+data;
      $("#titleForm").text(titleForm);
    });
  }else {
    titleForm = 'Registra una nuova Unità Stratigrafica ';
    switch (parseInt(tipoUS)) {
      case 1: titleForm += 'Positiva'; break;
      case 2: titleForm += 'Negativa'; break;
      case 3: titleForm += 'Scheletrica'; break;
    }
    $("#titleForm").text(titleForm);
  }
  check = (tipoUS == 2 || tipoUS == 3) ? true : false;
  $("input#morfologia").prop('checked', check);
  switch (parseInt(tipoUS)) {
    case 1: classField = 'usp'; break;
    case 2: classField = 'usn'; break;
    case 3: classField = 'uss'; break;
    default:classField = 'allUS';
  }
  $(".field").hide();
  $(".all").show();
  $(".field."+classField).show();
}

function activeLink(){
  let active = window.location.pathname.split("/").pop().split(".")[0];
  $(".mainNav>a").removeClass('active');
  $(".mainNav>#"+active+"Link").addClass('active');
}

function postData(url, dati, callback){
  $.ajax({
    type: "POST",
    url: API+url,
    data: dati,
    dataType: 'json',
    success: function(data){ return callback(data); }
  });
}

function setFilter(f,v){
  localStorage.setItem(f,v);
  if(v == 0){localStorage.removeItem(f)}
  $("[name=clearFilter]").show();
  filterCard();
}
function filterCard(){
  filtri=[];
  Object.keys(localStorage).forEach(function(key){
    if(key == 'sigla' || key == 'nome_lungo'){
      filtri.push(key+" ILIKE '"+localStorage.getItem(key)+"'");
    }else if (key == 'anno') {
      filtri.push("extract('Y' from s.inizio) = "+localStorage.getItem(key));
    } else {
      if (localStorage.getItem('osm_id') > 0) {
        filtri.push("osm_id = "+localStorage.getItem('osm_id'));
      }
      if (localStorage.getItem('osm_id') == 'null') {
        filtri.push("osm_id is null");
      }
    }
  });
  schedeScavi(filtri);
}

function osmReverse(lat,lon, callback){
  const url = 'https://nominatim.openstreetmap.org/reverse?format=json&zoom=10&lat='+lat+'&lon='+lon;
  $.getJSON(url,function(result){
    callback(result);
  })
}
function getLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition, errorCallBack);
  } else {
    $(".toast").removeClass('[class^="bg-"]').addClass('bg-danger').html('Attenzione! Questo browser non supporta la funzione di geolocalizzazione').toast('show',{delay:5000});
    $(".toast").on('hidden.bs.toast', function () { form.trigger('reset'); });
  }
}

function showPosition(position) {
  const lat = position.coords.latitude;
  const lon = position.coords.longitude;
  osmReverse(lat,lon, getComune);
  $("[name=lat]").val(lat);
  $("[name=lon]").val(lon);
}
function errorCallBack(){
  alert('Il GPS è spento!\nAccendilo e ricarica la pagina per ottenere la posizione corretta');
}
function getComune(osm){
  $("[name=comuneLabel]").val(osm.address.city);
  $("[name=comune]").val(osm.osm_id);
}

function isMobile(){
  var check = false;
  (function(a){
    if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino|android|ipad|playbook|silk/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4)))
      check = true;
  })(navigator.userAgent||navigator.vendor||window.opera);
  return check;
}

// jquery extend function
$.extend({
  redirectPost: function(location, args){
    const form = $('<form></form>');
    form.attr("method", "post");
    form.attr("action", location);
    $.each( args, function( key, value ) {
      let field = $('<input></input>');
      field.attr("type", "hidden");
      field.attr("name", key);
      field.attr("value", value);
      form.append(field);
    });
    $(form).appendTo('body').submit();
  }
});

Date.prototype.toDateInputValue = (function() {
  var local = new Date(this);
  local.setMinutes(this.getMinutes() - this.getTimezoneOffset());
  return local.toJSON().slice(0,10);
});

const groupBy = keys => array =>
  array.reduce((objectsByKeyValue, obj) => {
    const value = keys.map(key => obj[key]).join('-');
    objectsByKeyValue[value] = (objectsByKeyValue[value] || []).concat(obj);
    return objectsByKeyValue;
  }, {});

function groupByKey(arr, property) {
  return arr.reduce((acc, cur) => {
    acc[cur[property]] = [...acc[cur[property]] || [], cur];
    return acc;
  }, {});
}

function getList(obj){
  Object.keys(obj).forEach(function (key){
    postData("liste.php", {tab:obj[key]['tab'], orderBy:obj[key]['orderBy']}, function(data){
      if (String(obj[key]['sel']).indexOf("select") !== -1) {buildList(data,obj[key]['sel']);}
    })
  });
}
function buildList(data, sel){
  // console.log(data);
  $.each(data,function(i, v){
    switch (sel) {
      case "select#scavo":
        $("<option/>", {value:v.id, text:v.nome_lungo}).appendTo(sel);
      break;
      case "select#compilatore":
        $("<option/>", {value:v.id, text:v.cognome+" "+v.nome}).appendTo(sel);
      break;
      default: $("<option/>", {value:v.id, text:v.value}).appendTo(sel);
    }
    if (postId) {
      $("[name=scavo]")
        .prop("disabled", true)
        .find(" option[value="+postId+"]")
        .prop("selected", true);
    }
    if (tipoUS) {
      $("[name=tipo]")
        .prop("disabled", true)
        .find(" option[value="+tipoUS+"]")
        .prop("selected", true);
    }
    if (usrAct) {$("[name=compilatore] option[value="+usrAct+"]").prop("selected", true);}
  });
}
function classRubricaSelect(){
  postData("liste.php", {tab:'list.soggetto_rubrica', orderBy:'id asc'}, function(data){
    $.each(data, function(i,v){
      $("<option/>", {value:v.id, text:v.classe}).appendTo("select#classe");
    })
  })
}
function getClassRubrica(){
  var list = [];
  postData("utenti.php", {dati:{trigger:'getClassRubrica'}}, function(data){
    $.each(data,function(i,v){
      list.push(v.classe)
    })
  })
  return list;
}

function getRapporti(tipo, callback){
  var group;
  var option={tab:'list.rapporti_us'}
  switch (parseInt(tipo)) {
    case 1: option.filter = {'us_pos':true}; break;
    case 2: option.filter = {'us_neg':true}; break;
    case 3: option.filter = {'us_tafo':true}; break;
    case 4: option.filter = {'us_muro':true}; break;
    case 5: option.filter = {'us_geo':true}; break;
  }
  postData("liste.php", option, function(data){
    const groupByGruppo = groupBy(['gruppo']);
    group = groupByGruppo(data);
    return callback(group);
  })
}
function direttoreSelect(){
  option={
    tab:'rubrica',
    filter:{classe:1},
    orderBy:'nome asc'
  }
  postData("liste.php", option, function(data){
    $.each(data, function(i,v){
      $("<option/>", {value:v.id, text:v.nome}).appendTo("select#direttore");
    })
  })
}

function truncate(str, words) {return str.split(" ").splice(0,words).join(" ");}
function nl2br(str){return str.replace(/(?:\r\n|\r|\n)/g, '<br>');}
function count_words(str){
  //togli gli spazi all'inizio e alla fine
  count = str.replace(/(^\s*)|(\s*$)/gi,"");
  //riduci gl ispazzi multipli in singoli: questo    spazio -> questo spazio
  count = count.replace(/[ ]{2,}/gi," ");
  // converti gli a capo in spazi
  count = count.replace(/\n /,"\n");
  return count.split(' ').length;
}

function getByte(min,max){
  return Math.floor(Math.random() * (max - min + 1) + min);
}
function randomColor(a){
  r = getByte(0,255);
  g = getByte(0,255);
  b = getByte(0,255);
  // a = (getByte(50, 100) * 0.01).toFixed(2);
  return "rgb("+r+","+g+","+b+","+a+")";
}
