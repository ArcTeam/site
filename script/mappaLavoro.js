function init() {
    OpenLayers.ProxyHost = "/cgi-bin/proxy.cgi?url=";
    var extent = document.getElementById('extent').value;
    var lavoro = document.getElementById('lavoro').value;
    var countFeat = document.getElementById('countFeat').value;
    if(countFeat == 0){$("#noGeom").show();}
    var coo = extent.split(",");
    map = new OpenLayers.Map('mappa', mapOpt);
    map.addControl(new OpenLayers.Control.Navigation());
    map.addControl(new OpenLayers.Control.MousePosition({div: document.getElementById('coo')}));
    map.addControl(new OpenLayers.Control.Attribution());
    map.addControl(new OpenLayers.Control.Zoom());
    map.addControl(new OpenLayers.Control.TouchNavigation({dragPanOptions: {enableKinetic: true}}));

    osm = new OpenLayers.Layer.OSM();
    map.addLayer(osm);

    var lavori = new OpenLayers.Layer.WMS("progetti", wmsHost,{
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
    map.addLayer(lavori);


    extent = new OpenLayers.Bounds(coo[0], coo[1], coo[2], coo[3]);
    map.zoomToExtent(extent);
    var max = '<a href="#" class="olButton" id="max" title="torna allo zoom iniziale"><i class="fa fa-globe"></i></a>';
    $('.olControlZoom').append(max);
    $('.olControlZoomIn').attr("title","Ingrandisci la mappa");
    $('.olControlZoomOut').attr("title","Diminuisci la mappa");
    $("#max").click(function(){map.zoomToExtent(extent);});
}
