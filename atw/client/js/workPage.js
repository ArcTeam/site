const work = $('[name=work]').val();
let name = $('[name=name]').val();
let lon,lat, totOre, percOre, ore = 0, color;
//NOTE: sezione dati generali scavo
postData("lavoro.php", {dati:{trigger:'getWork',id:work}}, function(data){
  totOre = data[0].tot_ore;
  $('[name=name]').val(data[0].nome);
  $(".nomeScavo").text(data[0].nome);
  $("#comune").text(data[0].comune);
  $("#localizzazione").text(data[0].localizzazione);
  $("#nome").text(data[0].nome);
  $("#sigla").text(data[0].sigla);
  $("#inizio").text(data[0].inizio);
  $("#descrizione").text(data[0].descrizione);
  $("#ore").text(data[0].tot_ore);
  $("#direttore").text(data[0].direttore);
  if (data[0].fine && data[0].fine !== null) {
    $(".statoLavoroItem").addClass('bg-danger text-white');
    $("#statoLavoro").text("lavoro chiuso in data: "+data[0].fine);
  }else {
    $(".statoLavoroItem").addClass('bg-success text-white');
    $("#statoLavoro").text("work in progress!");
  }
  if (data[0].lon && data[0].lat) {
    lat = data[0].lat;
    lon = data[0].lon;
  }else {
    lat = 46.37336;
    lon = 11.0337;
  }
  $(".dropdown-inserisci > button").on('click', function(){
    const page = $(this).data('form')+".php";
    // const tipo = $(this).data('tipo');
    let opt = {id:work, name:data[0].nome};
    if(typeof $(this).data('tipo') !== 'undefined') {opt.tipo = $(this).data('tipo'); }
    $.redirectPost(page,opt);
  })
  initMap(lon, lat)
  initSectionOre(totOre)
  initDiario(data[0].nome)
  initReperti()
  initUs(data[0].nome)
})

function initUs(scavo){
  postData("lavoro.php", {dati:{trigger:'getUs',id:work}}, function(data){
    const aperte = data.aperte;
    const numAperte = data.aperte.length;
    const chiuse = data.chiuse;
    const numChiuse = data.chiuse.length;
    const listAperte = $(".list-us-aperte");
    const listChiuse = $(".list-us-chiuse");
    buildUsList(scavo, aperte, listAperte)
    buildUsList(scavo, chiuse, listChiuse)
    buildUsChart(numAperte, numChiuse)
  })
}
function buildUsChart(aperte, chiuse){
  const tot = aperte + chiuse;
  const percAperte = (aperte * 100) / tot;
  const percChiuse = (chiuse * 100) / tot;
  $("#totUs").text(tot);
  $(".usAperte").css({"width":percAperte+"%"}).prop('aria-valuenow',percAperte);
  $(".usChiuse").css({"width":percChiuse+"%"}).prop('aria-valuenow',percChiuse);
  $("#usAperteTot").text(aperte);
  $("#usChiuseTot").text(chiuse);
}
function buildUsList(scavo, data, list){
  $.each(data, function(index, el) {
    let prefix = el.id_tipo == 2 ? '-' : '';
    let item = $("<div/>",{class:'py-2 px-3 border-bottom'}).appendTo(list);
    let title = $("<small/>",{class:'d-block font-weight-bold', text:"US num. "+prefix+el.us}).appendTo(item);
    $("<span/>",{class:'float-right', text:el.compilazione}).appendTo(title);
    let definizione = (el.definizione == el.descrizione) ? el.definizione : el.definizione+"<br/>"+el.descrizione;
    definizione = truncate(definizione,50)
    if (count_words(definizione) == 50) {definizione = definizione+" ...";}
    $("<small/>",{class:'d-block', html:definizione}).appendTo(item);
    let usNavDiv = $("<div/>",{class:'d-flex justify-content-end'}).appendTo(item);

    let usView = $('<button/>',{class:'btn btn-sm btn-light bg-white pointer', name:'usView'}).appendTo(usNavDiv);
    $("<i/>", {class:'fas fa-eye'}).appendTo(usView);
    let usEdit = $('<button/>',{class:'btn btn-sm btn-light bg-white pointer', name:'usView'}).appendTo(usNavDiv);
    $("<i/>", {class:'fas fa-edit'}).appendTo(usEdit);
    usView.on('click', function(event) {
      $(".modal-title").html("<h4>US num. "+prefix+el.us+"</h4><h6>"+el.tipo+"</h6>");
      $(".modal-body").html("<div>"+nl2br(el.definizione)+"</div>");
      $(".modal").modal();
    });
    usEdit.on('click', function(){
      $.redirectPost('addUs.php',{id:work, name:scavo,usId:el.id, tipo:el.id_tipo});
    });
  });
}

//NOTE: sezione gestione ore
function initSectionOre(totOre){
  postData("lavoro.php", {dati:{trigger:'getOre',id:work}}, function(data){
    const groupByData = groupBy(['data']);
    let group = groupByData(data);
    $.each(group, function(key,val){
      let oreGiorno = 0;
      details = $("<details/>").appendTo('.details');
      summary = $("<summary/>").html(key+"<span class='float-right ore"+key+"'></span>").appendTo(details);
      $.each(val,function(i, v) {
        oreGiorno += parseFloat(v.ore);
        item = $("<div/>",{class:'py-1'}).html("<span class='w-75'>"+v.operatore+"</span><span class='w-25 text-right'>"+v.ore+"</span>").appendTo(details);
        $(".ore"+v.data).text(oreGiorno);
      });
    })
    $.each(data, function(i,v){ore += parseFloat(v.ore);})
    $(".totOre").text(ore+"/"+totOre);
    percOre = parseInt((ore*100)/totOre);
    if (percOre <= 50) {
      color = "#28a745";
    }else if (percOre > 50 && percOre <= 75) {
      color = "#ffc107";
    }else {
      color = "#dc3545";
    }
    if (totOre == 0) {
      $(".point>svg").remove();
    }else {
      if (isFinite(percOre)) {
        $(".svg-text").text(percOre+"%").css({"fill":color});
      }else {
        $(".svg-text").text("?!").css({"fill":"red"});
      }
      const $round = $('.round');
      let roundRadius = $round.attr('r');
      let roundPercent = percOre;
      let roundCircum = 2 * roundRadius * Math.PI;
      let roundDraw = roundPercent * roundCircum / 100;
      $round.css({'stroke-dasharray': roundDraw  + ' 999',"stroke":color})
    }
  })
}

//NOTE: sezione gestione diario
function initDiario(name){
  postData("lavoro.php", {dati:{trigger:'getDiario',id:work}}, function(data){
    $.each(data, function(index, val) {
      let item = $("<li/>", {class:'list-group-item'}).appendTo('.list-diario');
      let testo = truncate(val.descrizione,50)
      if(count_words(testo) == 50){ testo = testo+" ..."; }
      $("<small/>",{class:'font-weight-bold d-block', text:val.data}).appendTo(item);
      $("<small/>",{html:nl2br(testo)}).appendTo(item);
      // $("<small/>",{class:'font-weight-bold d-block', html:val.operatore}).appendTo(item);
      let diarioNavDiv = $("<div/>",{class:'d-flex justify-content-end'}).appendTo(item);
      let diarioView = $('<button/>',{class:'btn btn-sm btn-light bg-white pointer', name:'diarioView'}).appendTo(diarioNavDiv);
      $("<i/>", {class:'fas fa-eye'}).appendTo(diarioView);
      let diarioEdit = $('<button/>',{class:'btn btn-sm btn-light bg-white pointer', name:'usView'}).appendTo(diarioNavDiv);
      $("<i/>", {class:'fas fa-edit'}).appendTo(diarioEdit);
      diarioView.on('click', function(event) {
        $(".modal-title").html("<h4>Diario del: "+val.data+"</h4>");
        body = "<div>"+nl2br(val.descrizione)+"</div>";
        body += "<small class='font-weight-bold'>"+val.operatore+"</small>";
        $('.modal-body').html(body);
        $(".modal").modal();
      });
      diarioEdit.on('click', function(){
        $.redirectPost('addDiario.php',{id:work, name:name, diario:val.id});
      });
    });
  })
}

//NOTE: sezione fotopiani
$(".wrapListFotopiani").hide();
postData("lavoro.php", {dati:{trigger:'getFotopiani',id:work}}, function(data){
  const daElaborareTot = data.da_elaborare.length;
  const elaboratiTot = data.elaborati.length;
  const totFotopiani = daElaborareTot + elaboratiTot;
  const daElaborarePerc = (daElaborareTot * 100) / totFotopiani;
  const elaboratiPerc = (elaboratiTot * 100) / totFotopiani;
  $(".noEl").css({"width":daElaborarePerc+"%"}).prop('aria-valuenow',daElaborarePerc);
  $(".el").css({"width":elaboratiPerc+"%"}).prop('aria-valuenow',elaboratiPerc);
  $("#totFotopiani").text(totFotopiani);
  $("#daElaborareTot").text(daElaborareTot);
  $("#elaboratiTot").text(elaboratiTot);

  buildFotopianiList(data.da_elaborare, '.list-fotopiani-daFare')
  buildFotopianiList(data.elaborati, '.list-fotopiani-fatti')

})

function buildFotopianiList(arr, list){
  $.each(arr, function(k, val) {
    item = $("<div/>",{class:'py-2 px-3 border-bottom'}).appendTo(list);
    num = $("<small/>",{class:'d-block font-weight-bold m-0 p-0', text:'fotopiano num. '+val.num_fotopiano}).appendTo(item);
    data = $("<span/>", {class:'float-right', text:'('+val.data+')'}).appendTo(num);
    us = $("<small/>", {class:'d-block', text:val.us}).appendTo(item)
    note = $("<small/>", {class:'d-block', text:val.note}).appendTo(item)
  });
}

function initMap(lon,lat){
  center = [lat,lon];
  zoom = 18;
  map = L.map('map').setView(center,zoom);
  L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
  }).addTo(map);
  L.marker(center).addTo(map);
}

function initReperti(){
  let tipologia=[];
  let totale=[];
  let color = [];
  const canvas = document.getElementById('findPie')
  const ctx = canvas.getContext('2d');
  for (var i = 0; i < 50; i++) { color.push(randomColor(0.5)); }
  color = [ ...new Set(color) ];
  postData("sacchetti.php", {dati:{trigger:'repertiPie', id:work}}, function(data){
    if (data.length > 0) {
      $.each(data, function(i,v){
        tipologia.push(v.tipologia);
        totale.push(v.tot);
      })
      var myChart = new Chart(ctx, {
        type: 'polarArea',
        data: {
          labels: tipologia,
          datasets: [{
            data: totale,
            backgroundColor: color,
            borderColor: color,
            borderWidth: 1
          }]
        },
        options: {}
      });
      tipologia.forEach(function(el){ });
    } else {
      ctx.font = "20px Arial";
      ctx.textAlign = "center";
      ctx.fillText("nessun reperto salvato", 150, 50);
    }
  })

  postData("sacchetti.php", {dati:{trigger:'getReperti',id:work}}, function(data){
    $("#totReperti").text(data.reperti.length);
    $.each(data.reperti, function(index, val) {
      let item = $("<div/>",{class:'py-2 px-3 border-bottom'}).appendTo('.list-reperti');
      $("<small/>",{class:'font-weight-bold d-block', text:"reperto num. "+val.numero+" in US"+val.us}).appendTo(item);
      $("<small/>",{class:'d-block', text:val.tipologia+","+val.materiale}).appendTo(item);
      $("<small/>",{class:'d-block', text:val.note}).appendTo(item);
    });
  })
}
