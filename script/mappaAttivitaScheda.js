var extent, max, newpoi,msgUpdate, saveStrategy;
var navigate, save, del, draw, edit, divPannello, panel;
var lavoro, attivita, ext, coo, mod,ll;
max = '<a href="#" class="olButton" id="max" title="torna allo zoom iniziale"><i class="fa fa-globe"></i></a>';
msgUpdate = "Ok! La geometria è stata modificata";

saveStrategy = new OpenLayers.Strategy.Save();
function init() {
    OpenLayers.ProxyHost = "/cgi-bin/proxy.cgi?url=";
    extent = document.getElementById('extent').value;
    lavoro = document.getElementById('lavoro').value;
    attivita = document.getElementById('attivita').value;
    ll = document.getElementById('lonlat').value;
    coo = extent.split(",");
    ll = ll.split(",");
    map = new OpenLayers.Map('mappa', mapOpt);
    map.addControl(new OpenLayers.Control.Navigation());
    map.addControl(new OpenLayers.Control.MousePosition({div: document.getElementById('coo')}));
    map.addControl(new OpenLayers.Control.Attribution());
    map.addControl(new OpenLayers.Control.Zoom());
    map.addControl(new OpenLayers.Control.TouchNavigation({dragPanOptions: {enableKinetic: true}}));

    osm = new OpenLayers.Layer.OSM();
    map.addLayer(osm);

    newpoi = new OpenLayers.Layer.Vector("wfs", {
        strategies: [new OpenLayers.Strategy.BBOX(), saveStrategy],
        protocol: new OpenLayers.Protocol.WFS({
            version:       "1.0.0",
            url: "http://localhost:8080/geoserver/arcteam/wfs",
            featureType: "attivita",
            srsName: "EPSG:3857",
            featureNS: "http://www.geoserver.org/arcteam",
            geometryName: "geom",
            schema: "http://localhost:8080/geoserver/arcteam/wfs?service=WFS&version=1.0.0&request=DescribeFeatureType&TypeName=arcteam:attivita"
        }),
        filter: new OpenLayers.Filter.Comparison({
          type: OpenLayers.Filter.Comparison.EQUAL_TO,
          property: "gid",
          value: attivita
        })
    });
    map.addLayer(newpoi);
    newpoi.events.on({"featuremodified": update});

    // add the custom editing toolbar
    navigate = new OpenLayers.Control.DragPan({isDefault: true, title: "Naviga all'interno della mappa", displayClass: "olControlNavigation"});
    edit = new OpenLayers.Control.ModifyFeature(newpoi, { title: "Modifica vertici geometria", displayClass: "olControlModifyFeature"});
    divPannello = document.getElementById("panel");
    panel = new OpenLayers.Control.Panel({ defaultControl: navigate, displayClass: 'olControlPanel', div: divPannello });
    panel.addControls([navigate,edit]);
    map.addControl(panel);

    var max = '<a href="#" class="olButton" id="max" title="torna allo zoom iniziale"><i class="fa fa-globe"></i></a>';
    $('.olControlZoomIn').attr("title","Ingrandisci la mappa");
    $('.olControlZoomOut').attr("title","Diminuisci la mappa");
    $('.olControlZoom').append(max);
    $("#max").click(function(e){e.preventDefault();map.zoomToExtent(extent);});
    $('.olControlZoom').append( $('#panel') );
    $('#panel div').addClass('transition');

    extent = new OpenLayers.Bounds(coo[0], coo[1], coo[2], coo[3]);
    map.zoomToExtent(extent);
    setCenter(ll[0],ll[1]);
}

function update(e){
  saveStrategy.save();
  $("#msgGeom").show().delay(3000).fadeOut('fast');
}
