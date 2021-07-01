localStorage.clear();
$("[name=clearFilter]").hide();
postData("index.php", {dati:{trigger:'getFilterLavoro'}}, function(data){
  if (data['anno'].length > 0) {
    $.each(data['anno'], function(i,v){
      $("<button/>",{class:'dropdown-item', type:'button', value:v.anno, text:v.anno, id:'filtro'+i, name:'filter'}).attr('data-filtro','anno').appendTo('.dropdown-anno');
    });
    $.each(data['comune'], function(i,v){
      $("<button/>",{class:'dropdown-item', type:'button', value:v.osm_id, text:v.name, id:'filtro'+i, name:'filter'}).attr('data-filtro','osm_id').appendTo('.dropdown-comune');
    });
    $.each(data['sigla'], function(i,v){$("<option/>",{value:v.sigla}).appendTo('datalist#sigle');});
    $.each(data['scavo'], function(i,v){$("<option/>",{value:v.scavo}).appendTo('datalist#scavi');});
    schedeScavi(null);
  }else {
    $("#filtroLavori").text('non ci sono dati da mostrare')
  }
})

$("body").on('click', 'button[name=filter]', function() {
  let f = $(this).data('filtro');
  let v = $(this).val();
  let t = $(this).text();
  $(this).parent().siblings('button').text(t)
  setFilter(f,v);
});

$("body").on('change', 'input[name=filter]', function(){
  let f = $(this).data('filtro');
  let v = $(this).val().toLowerCase();
  if(v){
    setFilter(f,v);
  }else {
    localStorage.removeItem(f)
    filterCard();
  }
})

$("[name=clearFilter]").on('click', function(){
  localStorage.clear();
  $('input[name=filter]').val('');
  $("#dropdownMenuAnno").text('filtra anno');
  $("#dropdownMenuComune").text('filtra Comune');
  $(this).fadeOut('fast');
  schedeScavi(null);
})

function schedeScavi(filtri){
  dati={};
  dati.trigger = 'getLavoro';
  if(filtri){dati.filtri = filtri;}
  $(".card-columns").html('');
  postData("index.php", {dati}, function(data){
    $.each(data, function(i,v){
      let card = $("<div/>").addClass('card')
        .attr("data-anno", v.anno)
        .attr("data-osm_id", v.osm_id)
        .attr("data-nome_lungo", v.nome)
        .attr("data-sigla", v.sigla)
        .appendTo('.card-columns')
        .hide()
        .fadeIn('fast');
      let body = $("<div/>").addClass('card-body').appendTo(card);
      let title = $("<h5/>",{text:v.nome}).addClass('card-title border-bottom').appendTo(body);
      let subTitle = $("<h6/>", {html:"<span>"+v.comune+", "+v.sigla+ ", "+v.anno+"</span>"}).addClass('card-subtitle text-muted mb-2').appendTo(body);
      let text = $("<p/>",{text:v.descrizione}).addClass('card-text').appendTo(body);
      let footer = $("<div/>").addClass('card-footer text-right').appendTo(card);
      $("<button/>",{type: 'button', name:'link2work', value:v.id, class:'btn btn-sm btn-outline-info', text:'apri scavo'}).appendTo(footer).on('click', function(){
        $.redirectPost('workPage.php', {id: v.id});
      });
    });
  })
}
