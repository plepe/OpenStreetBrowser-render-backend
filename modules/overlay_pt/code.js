function overlay_pt_init() {
  var layer = new OpenLayers.Layer.OSM("Render Route", "tiles/overlay_pt/", {numZoomLevels: 19, isBaseLayer: false, visibility: false });
  map.addLayer(layer);
}

register_hook("init", overlay_pt_init);
