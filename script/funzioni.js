(function ($) {
   $.fn.clickToggle = function (func1, func2) {
       var funcs = [func1, func2];
       this.data('toggleclicked', 0);
       this.click(function () {
           var data = $(this).data();
           var tc = data.toggleclicked;
           $.proxy(funcs[tc], this)();
           data.toggleclicked = (tc + 1) % 2;
       });
       return this;
   };
}(jQuery));

function rotate(el,deg){
    var rot = deg;
    el.animate(
        {rotation: rot}
        ,{duration: 300, step: function(now, fx) {
                $(this).css({"transform": "rotate("+now+"deg)"});
            }
        }
    );
}
function rotatey(){
    $('.hover').animate({rotation: 179}
        ,{duration: 300, step: function(now, fx) {
                $(this).css({"transform": "rotateY("+now+"deg)"});
            }
        }
    );
}

function isUrl(url) {
    return /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(url);
}

function formatBytes(bytes){
    var kb = 1024;
    var ndx = Math.floor( Math.log(bytes) / Math.log(kb) );
    var fileSizeTypes = ["bytes", "kb", "mb", "gb", "tb", "pb", "eb", "zb", "yb"];
    return (bytes / kb / kb).toFixed(2)+fileSizeTypes[ndx];
}

function usrAction(id, script, classe, email){
    $.ajax({
        type: "POST",
        url: "inc/"+script,
        data: {id:id, classe:classe, email:email},
        success: function(data){
            $(".dialogResult").html(data).show();
            setTimeout(function(){ location.reload(); }, 5000);
        }
    });
}

function delRec(tab,campo,id,page){
    var msgDel = "Stai per eliminare un record e tutti i dati ad esso associati.\nL'azione non può essere annullata, confermi l'eliminazione?";
    //console.log(tab+" | "+campo+" | "+id+" | "+page);
    $("#deleteMsg").text(msgDel);
    $('#delRec').fadeIn('fast');
    $("button[name='chiudiDel']").on("click", function(){ $('#delRec').fadeOut('fast'); $("#deleteMsg").text(msgDel); });
    $("button[name='confermaDel']").on("click",function(){
        $.post("inc/delRecScript.php", { tab:tab, campo:campo, id:id }, function(data){
            $("#deleteMsg").text(data);
            $('#delRec').delay(2000).fadeOut('fast', function(){  window.location.href = page; });
        });
    });
}

/////////  funzioni per highlight filtro tabelle ////
function filterTable(search, dati) {
  dehighlight(document.getElementById(dati));
  if (search.value.length > 0) highlight(search.value.toLowerCase(), document.getElementById(dati));
}

function dehighlight(container) {
 for (var i = 0; i < container.childNodes.length; i++) {
  var node = container.childNodes[i];
  if (node.attributes && node.attributes['class'] && node.attributes['class'].value == 'red') {
   node.parentNode.parentNode.replaceChild(
    document.createTextNode(node.parentNode.innerHTML.replace(/<[^>]+>/g, "")), node.parentNode
   );
   return;
  } else if (node.nodeType != 3) {
   dehighlight(node);
  }
 }
}

function highlight(search, container) {
  for (var i = 0; i < container.childNodes.length; i++) {
   var node = container.childNodes[i];
   if (node.nodeType == 3) {
    var data = node.data;
    var data_low = data.toLowerCase();
    if (data_low.indexOf(search) >= 0) {
     var new_node = document.createElement('span');
     node.parentNode.replaceChild(new_node, node);
     var result;
     while ((result = data_low.indexOf(search)) != -1) {
      new_node.appendChild(document.createTextNode(data.substr(0, result)));
      new_node.appendChild(create_node(document.createTextNode(data.substr(result, search.length))));
      data = data.substr(result + search.length);
      data_low = data_low.substr(result + search.length);
     }
     new_node.appendChild(document.createTextNode(data));
    }
   } else {
    highlight(search, node);
   }
  }
}

function create_node(child) {
  var node = document.createElement('span');
  node.setAttribute('class', 'red');
  node.attributes['class'].value = 'red';
  node.appendChild(child);
  return node;
}

function tagLength(){
    var tag = $(".tm-tag").length;
    if(tag==0){ $("input[name='tags']").prop("required", true); }else{ $("input[name='tags']").prop("required", false); }
}

function trimString(str, length, delim, appendix) {
    if (str.length <= length) return str;
    var trimmedStr = str.substr(0, length+delim.length);
    var lastDelimIndex = trimmedStr.lastIndexOf(delim);
    if (lastDelimIndex >= 0) trimmedStr = trimmedStr.substr(0, lastDelimIndex);
    if (trimmedStr) trimmedStr += appendix;
    return trimmedStr;
}
////////////////////////////////////////////////////////////////////////////////
///////// FUNZIONI MAPPE /////////////////////
function setCenter(lon,lat){
    var lonlat = new OpenLayers.LonLat(lon,lat);
    var zoom = 17;
    map.setCenter(lonlat, zoom );
}

function clearFilter(){
  $('#filtro').val('');
  $('.footable').trigger('footable_clear_filter');
}

function zoomtocluster(event) {
    var f = event.feature;
    if (f.cluster.length > 1){
        clusterpoints = [];
        for(var i = 0; i<f.cluster.length; i++){ clusterpoints.push(f.cluster[i].geometry); }
        var linestring = new OpenLayers.Geometry.LineString(clusterpoints);
        map.zoomToExtent(linestring.getBounds());
    }
}

function exportTableToCSV($table, filename) {
  var $rows = $table.find('tr'),
  tmpColDelim = String.fromCharCode(11),
  tmpRowDelim = String.fromCharCode(0),
  colDelim = '","',
  rowDelim = '"\r\n"',
  csv = '"' + $rows.map(function (i, row) {
    var $row = $(row), $cols = $row.find('th, td');
    return $cols.map(function (j, col) {
      var $col = $(col), text = $col.text();
      return text.replace(/"/g, '""'); // escape double quotes
    }).get().join(tmpColDelim);
  })
  .get().join(tmpRowDelim)
  .split(tmpRowDelim)
  .join(rowDelim)
  .split(tmpColDelim)
  .join(colDelim) + '"',
  // Data URI
  csvData = 'data:application/csv;charset=utf-8,' + encodeURIComponent(csv);

  $(this).attr({
    'download': filename,
    'href': csvData,
    'target': '_blank'
  });
 }
  

////////////////////////////////////////////////////////////////////////////////
var w=$("#mainWrap").width();
var w2=$("section#main").width();
$("aside#mainAside").css("width",w-w2-20);
var hfooter = $( window ).height();
$("#mainWrap").css("min-height",hfooter);
$('.hover').on("mouseenter click", function(){
    var el = $(this);
    if(el.hasClass('flip')){el.removeClass('flip');}else{el.addClass('flip');}
});
var headW = $("header#main").width();
var headH = $("header#main").height();
var navPos = $("#login").offset();
var logged = $("#logged").offset();
if(headH > 480){$(".panel").css({"height":headW});}
//$(".subMenu").css({"bottom":"0","right":headW+1});
$(".subMenu").css({"margin-top":"-250px","right":headW+1});
var mediaQuery = window.getComputedStyle(document.querySelector('body'),':before').content;
var act = (mediaQuery == '"desktop"') ? "mouseenter mouseleave":"click";
$(".logged").on(act, function(){$(this).children('ul').stop().animate({width: 'toggle'});});
$(".prevent").on("click", function(e){e.preventDefault();});
$("button[name='closeDialog']").on("click", function(){
    $("#dialogWrap").fadeOut('fast');
    $(".dialogForm").hide();
    $(".dialogResult").html('').removeClass();
});
$(".toggle").on("click", function(){ $(".toggled").slideToggle("fast"); });
