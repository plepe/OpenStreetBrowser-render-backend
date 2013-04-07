<?
function renderd_404($list, $uri) {
  if(preg_match("~^/tiles/~", $_SERVER['REQUEST_URI'])) {
    $list[]=array(0, array(
      'header'    =>array("content-type: image/png"),
      'body'      =>file_get_contents(modulekit_file("renderd", "404.png")),
    ));
  }
}

register_hook("404", "renderd_404");
