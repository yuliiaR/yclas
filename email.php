<?php
/**
 *  THIS IS TEST .php
 */
// // /echo "hola";
// $data = date('y/m/d');

// $parse_data = explode("/", $data);
// //$path = "upload/".$parse_data[2]."/".$parse_data[1]."/".$parse_data[0];

// $path = "upload/";
// // /echo count($parse_data);
// for ($i=0; $i < count($parse_data); $i++) { 
// 	$path .= $parse_data[$i].'/'; 
// 	if(!is_dir($path)){
// 		echo $i." loop".PHP_EOL;
// 		echo "not exists".PHP_EOL;
// 		mkdir($path, 0755, TRUE);
// 	}else{
// 		echo $i." loop".PHP_EOL;
// 		echo " exists".PHP_EOL;
// 	}
// }
// echo PHP_EOL.$path;
//if(is_dir($path)){echo "exists";}else{mkdir($path, 0755, TRUE);echo "bla";}

$bla = session_name() ;
var_dump($bla);
?>
