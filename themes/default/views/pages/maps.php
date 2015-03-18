<html>
<body>

<head>
    <title><?=$title?></title>
</head>

<body>

    <div id="gmap" style="height:<?=$height?>;width:<?=$width?>;"></div>

</body>

  <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
  <script src="//maps.google.com/maps/api/js?sensor=false&libraries=geometry&v=3.7"></script>
  <script type="text/javascript" src="//cdn.jsdelivr.net/maplace.js/0.1.2c/maplace.min.js"></script>

  <script type="text/javascript">
    var locations = [
    <?foreach ($ads as $ad):?>
    {       
            lat: <?=$ad->latitude?>,
            lon: <?=$ad->longitude?>,

            title: '<?=htmlentities(json_encode($ad->title),ENT_QUOTES)?>',
            <?if($ad->get_first_image() !== NULL AND !is_numeric(core::get('id_ad'))):?>
                icon: new google.maps.MarkerImage(
                        '<?=Core::S3_domain().$ad->get_first_image()?>',
                        null,
                        null,
                        null,
                        new google.maps.Size(<?=$width_thumb?>, <?=$height_thumb?>)),
            <?endif?>
            animation: google.maps.Animation.DROP,
            html: '<div style="overflow: visible; cursor: default; clear: both; position: relative; background-color: rgb(255, 255, 255); border-top-right-radius: 10px; border-bottom-right-radius: 10px; border-bottom-left-radius: 10px; padding: 6px 0; width: 100%; height: auto;"><p style="margin-bottom:10px;margin-top:-5px;"><a target="_blank" style="text-decoration:none; margin-bottom:15px; color:#4272db;" href="<?=Route::url('ad',  array('category'=>$ad->category,'seotitle'=>$ad->seotitle))?>"><?=$ad->address?><?=htmlentities($ad->title,ENT_QUOTES)?></a></p><p style="margin:0;"><?if($ad->get_first_image() !== NULL):?><img src="<?=Core::S3_domain().$ad->get_first_image()?>" style="float:left; width:100px; margin-right:10px; margin-bottom:6px;"><?endif?><?=htmlentities(Text::limit_chars(Text::removebbcode($ad->description), 255, NULL, TRUE),ENT_QUOTES)?></p></div>',

    },

    <?endforeach?>
    ];

    $(function() {
        new Maplace({
            locations: locations,
            controls_on_map: false,
            map_options: {
                set_center: [<?=$center_lat?>,<?=$center_lon?>],
                zoom: <?=$zoom?>
            }

        }).Load();
    });
  </script>

<?=(Kohana::$environment === Kohana::DEVELOPMENT)? View::factory('profiler'):''?>
</html>