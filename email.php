<?php
echo "hola";
$date = date('d/m/y');
print_r(explode("/", $date));
$parse_date = explode("/", $date);
$path = "upload/".$parse_date[2]."/".$parse_date[1]."/".$parse_date[0];
echo $path;

if(is_dir($path)){echo "exists";}else{mkdir($path, 0755, TRUE);echo "bla";}

?>
