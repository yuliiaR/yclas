<!-- <iframe frameborder="0" noresize="noresize" 
    height="420px" width="100%" 
    src="<?=Route::url('map')?>?height=400&address=<?=$address?>">
</iframe> -->
<input id="gmap-address" value="<?=$address?>" data-zoom="<?=core::config('advertisement.map_zoom')?>" type="hidden"/>
<div id="gmap" style="height:420px"></div>