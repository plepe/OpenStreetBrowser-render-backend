<?
$query=array();
$query['highway']= <<<EOT
	  (CASE
	    WHEN tags->'highway' in ('motorway', 'motorway_link', 'trunk', 'trunk_link') THEN 'motorway'
	    WHEN tags->'highway' in ('primary', 'primary_link', 'secondary', 'tertiary') THEN 'major' 
	    WHEN tags->'highway' in ('unclassified', 'road', 'residential') THEN 'minor'
	    WHEN tags->'highway' in ('living_street', 'pedestrian', 'byway') THEN 'pedestrian'
	    WHEN tags->'highway' in ('service', 'bus_guideway', 'track') THEN 'service'
	    WHEN tags->'highway' in ('path', 'cycleway', 'footway', 'bridleway', 'steps') THEN 'path'
	    WHEN tags->'railway' in ('platform') THEN 'path'
	    WHEN tags->'railway' in ('tram', 'light_rail', 'narrow_gauge', 'rail', 'subway', 'preserved', 'monorail') THEN 'railway'
	    WHEN tags->'aeroway' in ('runway') THEN 'aeroway'
	    WHEN tags->'aeroway' in ('taxiway') THEN 'aeroway'
	    WHEN tags->'waterway' in ('river', 'stream', 'canal') THEN 'waterway'
	    WHEN tags?'barrier' THEN 'barrier'
	    WHEN tags->'natural' in ('cliff') THEN 'natural'
	    WHEN tags->'power' in ('line', 'minor_line') THEN 'power'
	    WHEN tags->'man_made' in ('pipeline') THEN 'pipeline'
	    ELSE tags->'highway' END) as highway_type,
	  (CASE
	    /* motorway */
	    WHEN tags->'highway' in ('motorway') THEN 't1'
	    WHEN tags->'highway' in ('trunk') THEN 't2'
	    WHEN tags->'highway' in ('motorway_link') THEN 't3'
	    WHEN tags->'highway' in ('trunk_link') THEN 't4'
	    /* major */
	    WHEN tags->'highway' in ('primary') THEN 't1'
	    WHEN tags->'highway' in ('primary_link') THEN 't2'
	    WHEN tags->'highway' in ('secondary') THEN 't3'
	    WHEN tags->'highway' in ('tertiary') THEN 't4'
	    /* service */
	    WHEN tags->'highway' in ('service', 'bus_guideway') THEN 't1'
	    WHEN tags->'highway' in ('track') THEN 't2'
	    /* path */
	    WHEN tags->'highway' in ('steps') THEN 't1'
	    /* railway */
	    WHEN tags->'railway' in ('tram', 'light_rail', 'narrow_gauge') THEN 't1'
	    WHEN tags->'railway' in ('rail', 'subway', 'preserved', 'monorail') THEN 't2'
	    /* aeroway */
	    WHEN tags->'aeroway' in ('runway') THEN 't1'
	    WHEN tags->'aeroway' in ('taxiway') THEN 't2'
	    /* water */
	    WHEN tags->'waterway' in ('river') THEN 't1'
	    WHEN tags->'waterway' in ('canal') THEN 't2'
	    WHEN tags->'waterway' in ('stream') THEN 't3'
	    /* barrier */
	    WHEN tags->'barrier' in ('wall', 'city_wall') THEN 't1'
	    /* natural */
	    WHEN tags->'natural' in ('cliff') THEN 't1'
	    /* power */
	    WHEN tags->'power' in ('line') and tags->'importance'='international' THEN 't1'
	    WHEN tags->'power' in ('line') and tags->'importance'='national' THEN 't2'
	    WHEN tags->'power' in ('line') and tags->'importance'='regional' THEN 't3'
	    WHEN tags->'power' in ('line') and tags->'importance'='urban' THEN 't4'
	    WHEN tags->'power' in ('line') and tags->'importance'='suburban' THEN 't5'
	    WHEN tags->'power' in ('line') and tags->'importance'='local' THEN 't6'
	    WHEN tags->'power' in ('minor_line') THEN 't6'
	    /* pipeline */
	    WHEN tags->'man_made'='pipeline' and tags->'type' in ('water') THEN 't1'
	    WHEN tags->'man_made'='pipeline' and tags->'type' in ('oil') THEN 't2'
	    WHEN tags->'man_made'='pipeline' and tags->'type' in ('gas') THEN 't3'
	    WHEN tags->'man_made'='pipeline' and tags->'type' in ('sewage') THEN 't4'
	    WHEN tags->'man_made'='pipeline' and tags->'type' in ('heat', 'hot_water') THEN 't5'
	    /* ELSE */
	    ELSE 'default'
	    END) as highway_sub_type,
  (CASE
    WHEN tags->'highway'='pedestrian' THEN 'pedestrian'
    WHEN tags->'amenity'='parking' THEN 'parking'
  END) as highway_poly_type
EOT;
$query['landuse']=<<<EOT
       (CASE
         WHEN tags->'leisure' in ('park')
	   OR tags->'landuse' in ('village_green', 'recreation_ground', 'grass')
	   THEN 'park'
	 WHEN tags->'leisure' in ('golf_course', 'playground', 'sports_centre', 'track',
	                    'pitch', 'water_park', 'piste')
	   THEN 'sport'
	 WHEN tags->'leisure' in ('nature_reserve')
	   THEN 'nature_reserve'
	 WHEN tags->'natural' in ('wood', 'wetland', 'marsh', 'glacier', 'scree', 'scrub', 'heath')
	   THEN 'natural0'
	 WHEN tags->'natural' in ('mud', 'beach', 'cliff', 'rock')
	   THEN 'natural1'
         WHEN tags->'landuse' in ('cemetery')
	   THEN 'cemetery'
	 WHEN tags->'landuse' in ('forest', 'wood')
	   THEN 'natural0'
	 WHEN tags->'leisure' in ('common', 'garden')
	   OR tags->'landuse' in ('meadow', 'farm', 'greenhouse_horticulture', 'farmyard', 'farmland', 'vineyard', 'orchard')
	   OR tags->'natural' in ('fell', 'meadow')
	   THEN 'garden'
	 WHEN tags->'landuse' in ('school')
	   THEN 'education'
	 WHEN tags->'landuse' in ('quarry', 'landfill', 'brownfield', 
	                    'railway', 'construction', 'military', 'industrial')
	   OR tags->'amenity' in ('bus_station')
	   OR tags->'aeroway' in ('aerodrome', 'apron')
	   OR tags->'military' in ('barracks', 'airfield')
	   OR tags->'power' in ('generator', 'station', 'sub_station')
	   THEN 'industrial'
	 WHEN tags->'landuse' in ('residential', 'allotments')
	   THEN 'residential'
	 WHEN tags?'historic'
	   THEN 'historic'
	 WHEN tags?'tourism'
	   THEN 'tourism'
	 WHEN (not tags?'building' OR tags->'building'='no') THEN (CASE
	   WHEN tags->'amenity' in ('college', 'cinema', 'kindergarten', 'library', 'school', 'theatre', 'arts_centre', 'university')
	     THEN 'education'
	    WHEN tags->'amenity' in ('hospital', 'emergency_phone', 'fire_station', 'police')
	      THEN 'emergency'
	    WHEN tags->'amenity' in ('pharmacy', 'baby_hatch', 'dentist', 'doctors', 'veterinary')
	      THEN 'health'
	    WHEN tags->'amenity' in ('government', 'gouvernment', 'public_building', 'court_house', 'embassy', 'prison', 'townhall')
	      THEN 'public'
	    WHEN tags->'amenity' in ('marketplace') THEN 'shop'
	    WHEN tags?'shop' THEN 'shop'
	    END)
	END) as landuse,
	(CASE 
	 WHEN tags->'leisure' in ('golf_course', 'playground', 'sports_centre', 'track',
	                    'pitch', 'water_park', 'piste') THEN
	    (CASE
	      WHEN tags->'landuse' in ('piste') THEN 't1'
	      ELSE 't0'
	      END)
	  WHEN tags->'natural' in ('wood', 'wetland', 'marsh', 'glacier', 'scree', 'scrub', 'heath', 'mud', 'beach') THEN
	    (CASE
	      WHEN tags->'natural' in ('wood', 'scrub') THEN 't0'
	      WHEN tags->'natural' in ('wetland', 'marsh') THEN 't1'
	      WHEN tags->'natural' in ('glacier') THEN 't2'
	      WHEN tags->'natural' in ('scree', 'heath') THEN 't3'
	      END)
	  WHEN tags->'natural' in ('mud', 'beach', 'cliff', 'rock') THEN
	    (CASE
	      WHEN tags->'natural' in ('mud') THEN 't0'
	      WHEN tags->'natural' in ('beach') THEN 't1'
	      WHEN tags->'natural' in ('cliff', 'rock') THEN 't2'
	      END)
	  WHEN tags->'landuse' in ('forest', 'wood') THEN 't0'
 	  WHEN tags->'landuse' in ('quarry', 'farmyard', 'farmland', 'landfill', 'brownfield', 
	                    'railway', 'construction', 'military', 'industrial')
	   OR tags->'amenity' in ('bus_station')
	   OR tags->'aeroway' in ('aerodrome', 'apron')
	   OR tags->'military' in ('barracks', 'airfield')
	   OR tags->'power' in ('station', 'sub_station') THEN
	     (CASE
	       WHEN tags->'landuse' in ('military')
	         OR tags->'military' in ('barracks', 'airfield') THEN 't1'
	       ELSE 't0'
	     END)
	END) as landuse_sub_type
EOT;
$query['base_amenity']=<<<EOT
           (CASE
	     WHEN tags->'natural' in ('peak', 'volcano', 'cliff', 'cave_entrance') THEN 'natural_big'
	     WHEN tags?'natural' THEN 'natural'

	     WHEN tags->'highway' in ('mini_roundabout', 'gate', 'mountain_pass') THEN 'transport'
	     WHEN tags->'railway' in ('level_crossing') THEN 'transport'
	     WHEN tags->'amenity' in ('fountain') THEN 'obstacle'
	     WHEN tags->'historic' in ('monument', 'memorial') THEN 'obstacle'
	     WHEN tags?'power' THEN 'power'
	   END) as amenity_type,
	   (CASE
	     /* type = natural_big and natural */
	     WHEN tags?'natural' THEN (CASE
	       WHEN tags->'natural' in ('peak') THEN 't1'
	       WHEN tags->'natural' in ('cliff') THEN 't2'
	       WHEN tags->'natural' in ('cave_entrance') THEN 't3'
	       WHEN tags->'natural' in ('land') THEN 't4'
	       WHEN tags->'natural' in ('volcano') THEN 't5'

	       WHEN tags->'natural' in ('spring') THEN 't1'
	       WHEN tags->'natural' in ('beach') THEN 't2'
	       WHEN tags->'natural' in ('tree') THEN 't4'
	     END)

	     /* type = transport */
	     WHEN tags->'railway' in ('level_crossing') THEN 't1'
	     WHEN tags->'highway' in ('mini_roundabout') THEN 't2'
             WHEN tags->'highway' in ('gate') THEN 't3'
             WHEN tags->'highway' in ('mountain_pass') THEN 't4'

	     /* type = obstacle */
             WHEN tags->'amenity' in ('fountain') THEN 't1'
             WHEN tags->'historic' in ('monument', 'memorial') THEN 't2'

	     /* type = power */
	     WHEN tags->'power' in ('tower') THEN 't1'
	     WHEN tags->'power' in ('station', 'sub_station', 'generator') THEN 't2'

	   END) as amenity_sub_type,
	   (CASE
	     WHEN tags->'natural' in ('peak', 'volcano', 'glacier') THEN tags->'ele'
	     WHEN tags->'highway' in ('mountain_pass') THEN tags->'ele'
	   END) as amenity_desc
EOT;
$query['places']=<<<EOT
      (select 'node' as type, id_place_node as id, name, way,
       (CASE 
         WHEN tags->'place'='city' AND tags->'population'>=1000000 THEN 'city_large'
	 WHEN tags->'place'='city' AND tags->'population'>=200000 THEN 'city_medium'
	 WHEN tags->'place'='town' AND tags->'population'>=30000 THEN 'town_large'
	 ELSE tags->'place'
       END) as place,
       tags->'label' from planet_place) as places
EOT;
$query['places_sort']=<<<EOT
     (CASE
       WHEN tags->'place'='continent' THEN 0
       WHEN tags->'place'='country'   THEN 1
       WHEN tags->'place'='state'     THEN 2
       WHEN tags->'place'='city'      THEN 3
       WHEN tags->'place'='region'    THEN 4
       WHEN tags->'place'='island'    THEN 5
       WHEN tags->'place'='town'      THEN 6
       WHEN tags->'place'='village'   THEN 7
       WHEN tags->'place'='hamlet'    THEN 8
       WHEN tags->'place'='suburb'    THEN 9
       WHEN tags->'place'='locality'  THEN 10
       WHEN tags->'place'='islet'     THEN 11
       WHEN tags->'place'='isolated_dwelling'     THEN 12
       ELSE                          20
     END) ASC,
     (CASE
       WHEN not tags?'population' THEN 0
       ELSE parse_number(tags->'population')
     END) DESC
EOT;
$query['shop']=<<<EOT
(CASE 
  WHEN tags->'shop' in ('supermarket', 'groceries', 'grocery') THEN 'supermarket'
  WHEN tags->'shop' in ('supermarket', 'groceries', 'grocery') THEN 'health'
  WHEN tags->'amenity' in ('pharmacy') THEN 'health'
  WHEN tags->'amenity'='vending_machine' THEN 'vending'
  WHEN tags->'amenity'='marketplace' THEN 'marketplace'
  WHEN tags?'shop' THEN 'other'
END) as shop_type,
(CASE
  WHEN not tags?'shop' and tags->'amenity' in ('pharmacy') THEN 't1'
END) as shop_sub_type,
(CASE
  WHEN not tags?'shop' and 
    tags->'amenity' in ('pharmacy') THEN tags->'amenity'
  WHEN tags->'amenity'='vending_machine' THEN tags->'vending'
  WHEN tags->'amenity'='marketplace' THEN 'marketplace'
  ELSE tags->'shop'
END) as shop_desc,
(CASE
  WHEN tags->'network' in ('international', 'national') THEN 'national'
  WHEN tags->'network' in ('region', 'urban', 'local') THEN tags->'network'
  WHEN tags->'shop' in ('mall', 'shopping_center', 'shopping_centre') THEN 'region'
  WHEN tags->'shop' in ('supermarket', 'department_store', 'market') THEN 'urban'
  WHEN tags->'amenity' in ('marketplace') THEN 'urban'
  ELSE 'local'
END) as shop_network
EOT;
$query['highway_level']=<<<EOT
(CASE 
  WHEN tags->'highway' in ('motorway', 'motorway_link') THEN 21
  WHEN tags->'highway' in ('trunk', 'trunk_link') THEN 20
  WHEN tags->'highway' in ('primary', 'primary_link') THEN 12
  WHEN tags->'highway' in ('secondary') THEN 11
  WHEN tags->'highway' in ('tertiary') THEN 10
  WHEN tags->'highway' in ('unclassified', 'road', 'residential') THEN 4
  WHEN tags->'highway' in ('living_street', 'service', 'pedestrian', 'steps', 'bus_guideway', 'byway') THEN 3
  WHEN tags->'highway' in ('track', 'path', 'cycleway', 'footway', 'bridleway', 'ford') THEN 2
  WHEN tags->'railway' in ('platform') THEN 2
  WHEN tags->'railway' in ('tram', 'rail', 'narrow_gauge', 'light_rail') THEN 1
  WHEN tags?'barrier' THEN 0
  WHEN tags?'power' THEN 0
  END)
EOT;
$query['power']=<<<EOT
tags->'power' as power_type
EOT;
$query['bridge_tunnel']=<<<EOT
  (CASE 
    WHEN tags->'bridge' in ('yes', 'true', '1', 'viaduct', 'swing', 'aqueduct') THEN 'yes' 
    ELSE 'no'
  END) as bridge,
  (CASE 
    WHEN tags->'tunnel' in ('yes', 'true', '1') THEN 'yes' 
    ELSE 'no'
  END) as tunnel
EOT;
$query['water_area']=<<<EOT
  (CASE
    WHEN tags?'waterway' THEN tags->'waterway'
    WHEN tags?'landuse' THEN tags->'landuse'
    WHEN tags?'natural' THEN tags->'natural'
  END)
EOT;
$query['rail']=<<<EOT
  (CASE 
    WHEN tags->'railway' in ('tram', 'light_rail') THEN 'tram'
    WHEN tags->'railway' in ('rail', 'narrow_gauge', 'monorail', 'subway') THEN 'rail' 
    END) as "railway",
  (CASE WHEN tags->'railway' in ('subway', 'tram', 'light_rail') THEN
    (CASE
      WHEN tags->'tracks' in ('left', 'right') THEN tags->'tracks'
      WHEN tags->'tracks' in ('1', 'single') THEN 'single'
      WHEN tags->'tracks' in ('3', '4', '5', '6') THEN  'multiple'
      ELSE 'double' END) 
  ELSE
    (CASE
      WHEN tags->'tracks' in ('2', 'double') THEN 'double'
      WHEN tags->'tracks' in ('3', '4', '5', '6') THEN  'multiple'
      ELSE 'single' END) END) as "tracks"
EOT;
$query['buildings']=<<<EOT
 (CASE
    WHEN tags->'building' in ('no')
      THEN null
    WHEN tags->'amenity' in ('place_of_worship')
      THEN 'worship'
    WHEN tags->'highway' in ('toll_booth')
      OR tags->'railway' in ('station', 'platform')
      OR tags->'aeroway' in ('terminal', 'helipad')
      OR tags->'aerialway' in ('station')
      OR tags->'amenity' in ('ferry_terminal')
      THEN 'road_amenities'
    WHEN tags->'barrier' in ('hedge', 'fence')
      THEN 'nature_building'
    WHEN tags->'power' in ('generator')
      OR tags->'man_made' in ('gasometer', 'wasterwater_plant', 'watermill', 'water_tower', 'water_works', 'windmill', 'works', 'reservoir_covered')
      THEN 'industrial'
    WHEN tags->'amenity' in ('college', 'cinema', 'kindergarten', 'library', 'school', 'university')
      THEN 'education'
    WHEN tags->'amenity' in ('theatre', 'arts_centre', 'cinema', 'fountain', 'studio')
      THEN 'culture'
    WHEN tags?'shop'
      THEN 'shop'
    WHEN tags->'amenity' in ('hospital', 'emergency_phone', 'fire_station', 'police')
      THEN 'emergency'
    WHEN tags->'amenity' in ('pharmacy', 'baby_hatch', 'dentist', 'doctors', 'veterinary')
      THEN 'health'
    WHEN tags->'amenity' in ('government', 'gouvernment', 'public_building', 'court_house', 'embassy', 'prison', 'townhall')
      THEN 'public'
    WHEN tags->'amenity' in ('post_office')
      THEN 'communication'
    WHEN tags->'amenity' in ('hospital', 'baby_hatch', 'dentist', 'doctors', 'pharmacy', 'veterinary')
      THEN 'public'
    WHEN tags->'tourism' in ('museum', 'artwork', 'attraction', 'viewpoint', 'theme_park', 'zoo')
      THEN 'culture'
    WHEN tags?'military'
      THEN 'military'
    WHEN tags?'historic'
      THEN 'historic'
    WHEN tags->'building' in ('residental', 'residential', 'apartments', 'block', 'flats', 'appartments')
       THEN 'residential'
    WHEN tags->'amenity' in ('bicycle_parking', 'bicycle_rental', 'shelter')
      OR tags->'leisure' in ('sports_centre', 'stadium', 'track', 'pitch', 'ice_rink')
      OR tags?'sport'
      THEN 'sport'
    ELSE
      'default'
  END) as "building"
EOT;
$query['place']=<<<EOT
  (CASE 
    WHEN tags->'place'='country' AND parse_number_or_0(tags->'population')>20000000 THEN 'country_large'
    WHEN tags->'place'='country' AND parse_number_or_0(tags->'population')>1000000 THEN 'country_medium'
    WHEN tags->'place'='city' AND parse_number_or_0(tags->'population')>1000000 THEN 'city_large'
    WHEN tags->'place'='city' AND parse_number_or_0(tags->'population')>200000 THEN 'city_medium'
    WHEN tags->'place'='town' AND parse_number_or_0(tags->'population')>30000 THEN 'town_large'
    ELSE tags->'place'
  END) as "place"
EOT;
