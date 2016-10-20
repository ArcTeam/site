function init() {
    OpenLayers.ProxyHost = proxy;
    var extent = document.getElementById('extent').value;
    var coo = extent.split(",");
    map = new OpenLayers.Map('mappa', mapOpt);
    map.addControl(new OpenLayers.Control.Navigation());
    map.addControl(new OpenLayers.Control.MousePosition({div: document.getElementById('coo')}));
    map.addControl(new OpenLayers.Control.Attribution());
    map.addControl(new OpenLayers.Control.Zoom());
    map.addControl(new OpenLayers.Control.TouchNavigation({dragPanOptions: {enableKinetic: true}}));

    osm = new OpenLayers.Layer.OSM.CycleMap("CycleMap");
    map.addLayer(osm);
    var s = new OpenLayers.StyleMap({
       "default": new OpenLayers.Style({fillOpacity:0,strokeOpacity:0}),
       "select": new OpenLayers.Style({strokeColor: "#1D22CF",strokeWidth:3,fillColor: "#1D22CF", fillOpacity:0.6, graphicZIndex: 2}),
       "active": new OpenLayers.Style({fillColor: "#7578F5", fillOpacity:0.6, graphicZIndex: 2})
    });

    var colors = {low: "rgb(0, 125, 0)", middle: "rgb(255, 190, 0)", high: "rgb(255, 255, 0)"};
    // Define three rules to style the cluster features.
    var lowRule = new OpenLayers.Rule({
     filter: new OpenLayers.Filter.Comparison({
      type: OpenLayers.Filter.Comparison.LESS_THAN,
      property: "count",
      value: 15
     }),
     symbolizer: {
      fillColor: colors.low,
      fillOpacity: 0.9,
      strokeColor: colors.low,
      strokeOpacity: 0.5,
      strokeWidth: 12,
      pointRadius: 12,
      label: "${count}",
      labelOutlineWidth: 1,
      fontColor: "#eee",
      //fontOpacity: 0.8,
      fontSize: "12px"
     }
    });
    var middleRule = new OpenLayers.Rule({
     filter: new OpenLayers.Filter.Comparison({
      type: OpenLayers.Filter.Comparison.BETWEEN,
      property: "count",
      lowerBoundary: 15,
      upperBoundary: 50
     }),
     symbolizer: {
      fillColor: colors.middle,
      fillOpacity: 0.9,
      strokeColor: colors.middle,
      strokeOpacity: 0.5,
      strokeWidth: 12,
      pointRadius: 15,
      label: "${count}",
      labelOutlineWidth: 1,
      fontColor: "#eee",
      //fontOpacity: 0.8,
      fontSize: "12px"
     }
    });
    var highRule = new OpenLayers.Rule({
     filter: new OpenLayers.Filter.Comparison({
      type: OpenLayers.Filter.Comparison.GREATER_THAN,
      property: "count",
      value: 50
     }),
     symbolizer: {
      fillColor: colors.high,
      fillOpacity: 0.9,
      strokeColor: colors.high,
      strokeOpacity: 0.5,
      strokeWidth: 12,
      pointRadius: 20,
      label: "${count}",
      //labelOutlineWidth: 1,
      fontColor: "#eee",
      fontOpacity: 0.8,
      fontSize: "12px"
     }
    });

    // Create a Style that uses the three previous rules
    var style = new OpenLayers.Style(null, {rules: [lowRule, middleRule, highRule]});

    var renderer = OpenLayers.Util.getParameters(window.location.href).renderer;
    renderer = (renderer) ? [renderer] : OpenLayers.Layer.Vector.prototype.renderers;

    cluster = new OpenLayers.Layer.Vector("punti", {
        strategies: [
            new OpenLayers.Strategy.Fixed()
            ,new OpenLayers.Strategy.Cluster({distance: 45,animationMethod: OpenLayers.Easing.Expo.easeOut,animationDuration: 10})
        ],
        projection: new OpenLayers.Projection("EPSG:3857"),
        renderers: ['Canvas','SVG'],
        protocol: new OpenLayers.Protocol.WFS({
            version:       "1.0.0",
            url:           wfsHost,
            featureType:   "lavori",
            featureNS:     "http://www.geoserver.org/arcteam",
            geometryName:  "geom",
        }),
        styleMap:  new OpenLayers.StyleMap(style)
    });
    map.addLayer(cluster);

    lavori = new OpenLayers.Layer.WMS("progetti", wmsHost,{
      LAYERS: 'arcteam:lavori'
      ,format: format
      ,tiled: true
      ,tilesOrigin : map.maxExtent.left + ',' + map.maxExtent.bottom
      ,transparent: true
    },{
      buffer: 10
      ,isBaseLayer: false
      , visibility: false
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
                    var data_inizio,anno_inizio, data_fine, anno_fine, anno;
                    var feature = event.features[i];
                    var attributes = feature.attributes;
                    arr.push(attributes.id);
                    data_inizio = attributes.data_inizio.split("-");
                    anno_inizio = data_inizio[0];
                    if(attributes.data_fine){
                        data_fine = attributes.data_fine.split("-");
                        anno_fine = data_fine[0];
                    }
                    if(anno_inizio == anno_fine || !attributes.data_fine){anno = anno_inizio}
                    else{ anno = anno_inizio+"-"+anno_fine}
                    $("#infoJob").html("<header class='sub'>"+attributes.nome+"</header><div class='infoJobContent'><span>categoria: "+attributes.categoria+" | "+anno+" | attività svolta: "+attributes.sottocategoria+"</span>"+attributes.descrizione+"</div>");
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

    previousMapScale = map.getScale();
    map.events.register('zoomend', map, function() {
      if ((previousMapScale > CLUSTER_SCALE_THRESHOLD) && (this.getScale() < CLUSTER_SCALE_THRESHOLD)) {
         cluster.strategies[1].deactivate();
         cluster.refresh({force: true});
         lavori.setVisibility(true);
         $("#legend").fadeIn('fast');
      }
      if ((previousMapScale < CLUSTER_SCALE_THRESHOLD) && (this.getScale() > CLUSTER_SCALE_THRESHOLD)) {
         cluster.strategies[1].activate();
         cluster.refresh({force: true});
         lavori.setVisibility(false);
         $("#legend").fadeOut('fast');
      }
      previousMapScale = this.getScale();
    });


    extent = new OpenLayers.Bounds(coo[0], coo[1], coo[2], coo[3]);
    map.zoomToExtent(extent);
    $('.olControlZoom').append('<a href="#" id="max" title="torna allo zoom iniziale"><i class="fa fa-globe"></i></a>');
    $('.olControlZoomIn').attr("title","Ingrandisci la mappa");
    $('.olControlZoomOut').attr("title","Diminuisci la mappa");
    $("#max").click(function(){map.zoomToExtent(extent);});
}
