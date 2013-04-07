<?
function basemap_404($list, $uri) {
  if(preg_match("~^/tiles/basemap_base/~", $_SERVER['REQUEST_URI'])) {
    $list[]=array(0, array(
      'header'    =>array("content-type: image/png"),
      'body'      =>file_get_contents(modulekit_file("basemap", "404.png")),
    ));
  }
}

register_hook("404", "basemap_404");
