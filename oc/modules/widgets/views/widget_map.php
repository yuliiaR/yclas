<?php defined('SYSPATH') or die('No direct script access.');?>
<h3><?=$widget->map_title?></h3>

<?=$widget->map->getHeaderJS();?>
<?=$widget->map->getMapJS();?>
<?=$widget->map->printOnLoad();?> 
<?=$widget->map->printMap();?>