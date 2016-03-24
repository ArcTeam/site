var map,arrayOSM, osm, lavori, extent,cql, filter,layers;
var prj4326 = new OpenLayers.Projection("EPSG:4326");
var prj3857 = new OpenLayers.Projection("EPSG:3857");
var res = [156543.03390625, 78271.516953125, 39135.7584765625, 19567.87923828125, 9783.939619140625, 4891.9698095703125, 2445.9849047851562, 1222.9924523925781, 611.4962261962891, 305.74811309814453, 152.87405654907226, 76.43702827453613, 38.218514137268066, 19.109257068634033, 9.554628534317017, 4.777314267158508, 2.388657133579254, 1.194328566789627, 0.5971642833948135, 0.29858214169740677, 0.14929107084870338, 0.07464553542435169, 0.037322767712175846, 0.018661383856087923, 0.009330691928043961, 0.004665345964021981, 0.0023326729820109904, 0.0011663364910054952, 5.831682455027476E-4, 2.915841227513738E-4, 1.457920613756869E-4];
var maxExt = new OpenLayers.Bounds (-20037508.34,-20037508.34,20037508.34,20037508.34);
var units = 'm';
var mapOpt = {projection:prj3857,displayProjection:prj4326,resolutions:res,units:units,controls:[]};
var format = 'image/png';
var wmsHost = "http://localhost:8080/geoserver/arcteam/wms";
var jsonFormat ='application/json';
var styleMap = new OpenLayers.StyleMap({
        "default": new OpenLayers.Style({fillOpacity:0,strokeOpacity:0}),
        "select": new OpenLayers.Style({strokeColor: "#1D22CF",strokeWidth:3,fillColor: "#1D22CF", fillOpacity:0.6, graphicZIndex: 2}),
        "active": new OpenLayers.Style({fillColor: "#7578F5", fillOpacity:0.6, graphicZIndex: 2})
});
var osmAttr = "Data, imagery and map information provided by <a href='http://www.mapquest.com/'  target='_blank'>MapQuest</a>, <a href='http://www.openstreetmap.org/' target='_blank'>Open Street Map</a> and contributors, <a href='http://creativecommons.org/licenses/by-sa/2.0/' target='_blank'>CC-BY-SA</a> <img src='http://developer.mapquest.com/content/osm/mq_logo.png' border='0'>";
var extent = document.getElementById('extent').value;
var coo = extent.split(",");

$("input[name=layer]").on("change", function(){
  $(this).closest('label').toggleClass('layerAct');
  $(this).next('i').toggleClass('fa-check-square-o fa-square-o');
  lavori.setVisibility(false);
  var tot=$('input[name="layer"]').length;
  var length=$('input[name="layer"]:checked').length;
  if(length==0){
    lavori.setVisibility(false);
    return;
  }else if(length==tot){
    filter = 'tipo_id > 0';
    info.cql_filter=filter;
    lavori.mergeNewParams({'CQL_FILTER': "tipo_id > 0"});
    lavori.setVisibility(true);
    return;
  }else{
    cql = [];
    $('input[name="layer"]:checked').each(function(){
      var param = $(this).val();
      cql.push("tipo_id = " + param);
    });
    filter = cql.join(" OR ");
    console.log(filter);
    lavori.setVisibility(true);
    lavori.mergeNewParams({'CQL_FILTER': filter});
    info.cql_filter=filter;
    lavori.redraw();
    return;
  }
});


function init() {
    OpenLayers.ProxyHost = "/cgi-bin/proxy.cgi?url=";
    map = new OpenLayers.Map('mappa', mapOpt);
    map.addControl(new OpenLayers.Control.Navigation());
    map.addControl(new OpenLayers.Control.MousePosition());
    map.addControl(new OpenLayers.Control.Attribution());
    map.addControl(new OpenLayers.Control.Zoom());
    map.addControl(new OpenLayers.Control.TouchNavigation({dragPanOptions: {enableKinetic: true}}));

    arrayOSM = ["http://otile1.mqcdn.com/tiles/1.0.0/map/${z}/${x}/${y}.jpg", "http://otile2.mqcdn.com/tiles/1.0.0/map/${z}/${x}/${y}.jpg","http://otile3.mqcdn.com/tiles/1.0.0/map/${z}/${x}/${y}.jpg", "http://otile4.mqcdn.com/tiles/1.0.0/map/${z}/${x}/${y}.jpg"];
    osm = new OpenLayers.Layer.OSM("MapQuest-OSM Tiles", arrayOSM, { attribution: osmAttr, transitionEffect: "resize"});
    map.addLayer(osm);
    lavori = new OpenLayers.Layer.WMS("progetti", wmsHost,{
      LAYERS: 'arcteam:lavori'
      ,format: format
      ,tiled: true
      ,tilesOrigin : map.maxExtent.left + ',' + map.maxExtent.bottom
      ,transparent: true
    },{
      buffer: 10
      ,isBaseLayer: false
      , visibility: true
      , tileSize: new OpenLayers.Size(256,256)
    });
    map.addLayer(lavori);
    layers=[lavori];
    info = new OpenLayers.Control.WMSGetFeatureInfo({
        url: wmsHost,
        title: 'Informazioni sui livelli interrogati',
        queryVisible: true,
        layers: layers,
        infoFormat: 'application/vnd.ogc.gml',
        vendorParams: {buffer: 10},
        eventListeners: {
            getfeatureinfo: function(event) {
                var arr = new Array();
                for (var i = 0; i < event.features.length; i++) {
                   var feature = event.features[i];
                   var attributes = feature.attributes;
                   arr.push(attributes.id);
                   $("#infoJob").html("<header class='sub'>"+attributes.nome+"</header><div class='infoJobContent'><span>"+attributes.tipologia+"</span>"+attributes.descrizione+"</div>");
                }
            },
            beforegetfeatureinfo: function(event){
              var lyrCQL = lavori.params.CQL_FILTER;
              if (lyrCQL != null) {filter = lyrCQL;}
              info.vendorParams = { 'CQL_FILTER': filter, buffer: 10	};
            }
        }
    });
    map.addControl(info);
    info.activate();

    extent = new OpenLayers.Bounds(coo[0], coo[1], coo[2], coo[3]);
    map.zoomToExtent(extent);

    $('.olControlZoom').append('<a href="#" id="max" title="torna allo zoom iniziale"><i class="fa fa-globe"></i></a>');
    $('.olControlZoomIn').attr("title","Ingrandisci la mappa");
    $('.olControlZoomOut').attr("title","Diminuisci la mappa");
    $("#max").click(function(){map.zoomToExtent(extent);});
}
