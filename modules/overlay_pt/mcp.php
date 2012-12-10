<?
function overlay_pt_init($renderd) {
  $prefix=modulekit_file("overlay_pt", "overlay_pt", true);

  if(filemtime("$prefix.mml")>filemtime("$prefix.mapnik")) {
    print "Recompiling overlay_pt\n";
    cascadenik_compile("$prefix.mml");
    mapnik_colorsvg_process("$prefix.mapnik");
    mapnik_rotate_process("$prefix.mapnik");
  }

  $renderd['overlay_pt']=array("file"=>"$prefix.mapnik");
}

register_hook("renderd_get_maps", "overlay_pt_init");
