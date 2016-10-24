var extent, max, newpoi, msgDel, msgUpdate, DeleteFeature, saveStrategy, stylePoi;
var navigate, save, del, draw, edit, divPannello, panel;
var lavoro, ext, coo, mod;
max = '<a href="#" class="olButton" id="max" title="torna allo zoom iniziale"><i class="fa fa-globe"></i></a>';
msgDel1 = "Attenzione, stai per eliminare un'attività!\nSe confermi l'operazione, le geometrie e i dati ad esse associate saranno eliminate definitivamente e non sarà possibile recuperarle.";
msgDel2 = "Ok, l'attività è stata definitivamente eliminata";
msgUpdate = "Ok! La geometria è stata modificata";
msgIns = "Ok! L'attività è stata inserita'";

DeleteFeature = OpenLayers.Class(OpenLayers.Control, {
    initialize: function(layer, options) {
        OpenLayers.Control.prototype.initialize.apply(this, [options]);
        this.layer = layer;
        this.handler = new OpenLayers.Handler.Feature(this, layer, {click: this.clickFeature});
    },
    clickFeature: function(feature) {
        if(feature.fid == undefined) {
            this.layer.destroyFeatures([feature]);
        }else{
            feature.state = OpenLayers.State.DELETE;
            this.layer.events.triggerEvent("afterfeaturemodified",{feature: feature});
            feature.renderIntent = "select";
            this.layer.drawFeature(feature);
            $('#deleteMsg span').text(msgDel1);
            $('#deleteGeom').fadeIn('fast');
        }
    },
    setMap: function(map) { this.handler.setMap(map); OpenLayers.Control.prototype.setMap.apply(this, arguments); },
    CLASS_NAME: "OpenLayers.Control.DeleteFeature"
});

saveStrategy = new OpenLayers.Strategy.Save();

lavoro = document.getElementById('lavoro').value;
ext = document.getElementById('ext').value;
coo = ext.split(",");
mod = document.getElementById('modGeom').value;

function init() {
    OpenLayers.ProxyHost = "/cgi-bin/proxy.cgi?url=";
    extent = new OpenLayers.Bounds(coo[0], coo[1], coo[2], coo[3]);
    map = new OpenLayers.Map('mappa', mapOpt);

    map.addControl(new OpenLayers.Control.Navigation());
    map.addControl(new OpenLayers.Control.MousePosition({div: document.getElementById('coo')}));
    map.addControl(new OpenLayers.Control.Attribution());
    map.addControl(new OpenLayers.Control.Zoom());
    map.addControl(new OpenLayers.Control.TouchNavigation({dragPanOptions: {enableKinetic: true}}));

    osm = new OpenLayers.Layer.OSM.CycleMap("CycleMap");
    map.addLayer(osm);
    stylePoi = new OpenLayers.StyleMap({
        "default": new OpenLayers.Style(null, {
            rules: [
                new OpenLayers.Rule({
                    symbolizer: {
                        pointRadius: 6,
                        fillColor: "#427109",
                        fillOpacity: 1,
                        strokeWidth: 1,
                        strokeColor: "#72B51E"
                    }
                })
            ]
        }),
        "select": new OpenLayers.Style({
            fillColor: "#0C06AF",
            strokeColor: "#00ccff",
            strokeWidth: 1
        }),
        "temporary": new OpenLayers.Style(null, {
            rules: [
                new OpenLayers.Rule({
                    symbolizer: {
                        pointRadius: 6,
                        fillColor: "#0C06AF",
                        fillOpacity: 1,
                        strokeWidth: 1,
                        strokeColor: "#333333"
                    }
                })
            ]
        })
    });
    /*var lavori = new OpenLayers.Layer.WMS("progetti", wmsHost,{
        LAYERS: 'arcteam:lavori'
        ,format: format
        ,tiled: true
        ,tilesOrigin : map.maxExtent.left + ',' + map.maxExtent.bottom
        ,transparent: true
        ,CQL_FILTER: 'id='+lavoro
    },{
        buffer: 10
        ,isBaseLayer: false
        , visibility: true
        , tileSize: new OpenLayers.Size(256,256)
    });
    map.addLayer(lavori);*/

    newpoi = new OpenLayers.Layer.Vector("wfs", {
        //styleMap: stylePoi,
        strategies: [new OpenLayers.Strategy.BBOX(), saveStrategy],
        protocol: new OpenLayers.Protocol.WFS({
            version:       "1.0.0",
            url: "http://localhost:8080/geoserver/arcteam/wfs",
            featureType: "attivita",
            srsName: "EPSG:3857",
            featureNS: "http://www.geoserver.org/arcteam",
            geometryName: "geom",
            schema: "http://localhost:8080/geoserver/arcteam/wfs?service=WFS&version=1.0.0&request=DescribeFeatureType&TypeName=arcteam:attivita"
        })
    });
    map.addLayer(newpoi);


    // add the custom editing toolbar
    navigate = new OpenLayers.Control.DragPan({isDefault: true, title: "Naviga all'interno della mappa", displayClass: "olControlNavigation"});
    save = new OpenLayers.Control.Button({
        title: "Salva le modifiche effettuate e chiudi la sessione di lavoro",
        trigger: function() {
            if(edit.feature) {console.log(msgUpdate);}else{console.log(msgIns);}
            saveStrategy.save();
        },
        displayClass: "olControlSaveFeatures"
    });
    del = new DeleteFeature(newpoi, {title: "Elimina punto"});
    draw = new OpenLayers.Control.DrawFeature(newpoi, OpenLayers.Handler.Point,{
        title: "Inserisci punto",
        displayClass:"olControlDrawFeaturePoint",
        featureAdded: onFeatureInsert
    });
    edit = new OpenLayers.Control.ModifyFeature(newpoi, { title: "Modifica vertici geometria", displayClass: "olControlModifyFeature"});
    divPannello = document.getElementById("panel");
    panel = new OpenLayers.Control.Panel({ defaultControl: navigate, displayClass: 'olControlPanel', div: divPannello });
    panel.addControls([navigate,draw,edit,del]);
    map.addControl(panel);
    if(mod==0){
        //newpoi.setVisibility(false);
        //lavori.setVisibility(true);
        $(".olControlDeleteFeatureItemInactive, .olControlModifyFeatureItemInactive").remove();
    }else{
        //newpoi.setVisibility(true);
        //lavori.setVisibility(false);
        $(".olControlDrawFeaturePointItemInactive").remove();
    }

    newpoi.events.on({"featuremodified": update});
    map.zoomToExtent(extent);

    $('.olControlZoomIn').attr("title","Ingrandisci la mappa");
    $('.olControlZoomOut').attr("title","Diminuisci la mappa");
    $('.olControlZoom').append(max);
    $("#max").click(function(e){e.preventDefault();map.zoomToExtent(extent);});

    $('.olControlZoom').append( $('#panel') );
    $('#panel div').addClass('transition');
    $('.olControlZoom').append('<span id="msg"></span>');

    map.events.register('zoomend', map, function() { console.log(map.getExtent()); });
}

function onFeatureInsert(feature){
   $('#fid').val(feature.id);
   $('#msg span').text('');
   $('#formDiv').fadeIn('fast');
}

// Passa attributi al form
function insert(){
    var fid, script, form, lavoro, attivita, tipo, inizio, fine;
    fid = $("#fid").val();
    form = $("form[name=postForm]");
    lavoro = $("input[name=lavoro]").val();
    attivita = $("input[name=attivita]").val();
    tipo = $("select[name=tipo]");
    inizio = $("input[name=inizio]");
    fine = $("input[name=fine]").val();
    tipo.removeClass('error');
    inizio.removeClass('error');
    if(!tipo.val()){
        $("#msg span").text("Devi selezionare una tipologia di attività dall'elenco!");
        tipo.addClass('error');
    }
    else if(!inizio.val()){
        $("#msg span").text("Devi selezionare la data di inizio dell'attività!");
        inizio.addClass('error');
    }
    else{
        tipo.removeClass('error');
        inizio.removeClass('error');
        var f = newpoi.getFeatureById(fid);
        f.attributes.lavoro = lavoro;
        f.attributes.tipo_lavoro = tipo.val();
        f.attributes.data_inizio = inizio.val();
        //fine = (fine=='NULL'|| fine=='')?'1900-01-01':fine;
        if(fine!='NULL'|| fine==''){f.attributes.data_fine};
        saveStrategy.save();
        if(edit.feature) {
            var msg = msgUpdate;
            var div = $("#mod");
        }else{
            var msg = msgIns;
            var div = $("#fattura");
        }
        $('#salva').fadeOut(1000, function(){
            $('#msg').fadeIn(1000).text(msg).delay(2000).fadeOut(1000, function(){
                div.fadeIn(1000);
            });
        });
    }
}

function update(e){
    var data, anno, mese, giorno, dataArr, data_inizio, data_fine;
    $("#fid").val(e.feature.id);
    $("select[name=tipo] option[value=" + e.feature.attributes.tipo_lavoro + "]").prop("selected", true);
    data_inizio = e.feature.attributes.data_inizio;
    data_fine = e.feature.attributes.data_fine;
    /*dataArr = e.feature.attributes.data_inizio;
    dataArr = dataArr.split('-');
    anno = dataArr[0];
    mese = dataArr[1];
    giorno = dataArr[2].slice(0, -1);
    data = anno+"-"+mese+"-"+giorno;
    $("input[name=inizio]").val(dataArr);*/
    console.log("inizio:"+data_inizio+" fine:"+data_fine);
    $("input[name=inizio]").val(data_inizio);
    $("input[name=fine]").attr("min",data_inizio).val(data_fine);
    $("#formDiv").fadeIn('fast');
}

function elimina(){
    saveStrategy.save();
    $(".preDel, #deleteMsg span").fadeOut(500, function(){
        $('.postDel').fadeIn(500);
        $('#deleteMsg span').text(msgDel2).fadeIn(500);
    });
}

function cercaIndirizzo(q) {
    $.getJSON('https://nominatim.openstreetmap.org/search?format=json&q=' + q, function(data) {
        if(data.length > 0){
            var trovati = [];
            $.each(data, function(key, val) {
                trovati.push("<li class='transition' data-extent='"+val.boundingbox+"' data-lat='"+val.lat +"' data-lon='"+ val.lon +"'>"+ val.display_name + " ("+val.type+")</li>");
            });
            $("#resultSearchList").html(trovati.join(""));
            $("#resultSearchList > li").click(function(){
                var newExt = $(this).data('extent');
                newExt = newExt.split(',');
                var b = new OpenLayers.Bounds(newExt[2], newExt[0], newExt[3], newExt[1]);
                var p3857 = new OpenLayers.Projection("EPSG:3857");
                var p4326 = new OpenLayers.Projection("EPSG:4326");
                b.transform(p4326, p3857);
                map.zoomToExtent(b);
                $("#resultSearch").fadeOut('fast');
                $("#resultSearchList").html('');
            });
        }else{
            $("#resultSearchList").html("<li>Nessun indirizzo trovato, riprova!</li>");
        }
        $("#resultSearch").fadeIn('fast');
        $("#hideSearch").click(function(){
            $("#resultSearch").fadeOut('fast');
            $("#resultSearchList").html('');
        });
    });
}
