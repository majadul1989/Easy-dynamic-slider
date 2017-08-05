<?php 

/*********** short the skript******/
function slide_excerpt($num) {
$limit = $num+1;
$excerpt = explode(' ', get_the_excerpt(), $limit);
array_pop($excerpt);
$excerpt = implode(" ",$excerpt);
echo $excerpt;
}
 ?>