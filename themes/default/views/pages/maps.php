<html>
<body>

<head>
    <title><?=$title?></title>
</head>

<body>

    <div id="gmap" style="height:<?=$height?>;width:<?=$width?>;"></div>

</body>

  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
  <script src="http://maps.google.com/maps/api/js?sensor=false&libraries=geometry&v=3.7"></script>
  <script type="text/javascript" src="http://cdn.jsdelivr.net/maplace.js/0.1.2c/maplace.min.js"></script>

  <script type="text/javascript">
    var locations = [
    <?foreach ($ads as $ad):?>
    {
            lat: <?=$ad->lat?>,
            lon: <?=$ad->lon?>,
            title: '<?=$ad->title?>',
            html: '<p><a href="<?=Route::url('ad',  array('category'=>$ad->category,'seotitle'=>$ad->seotitle))?>"><?=$ad->title?></a></p>',
    },
    <?endforeach?>
    ];

    $(function() {
        new Maplace({
            locations: locations,
            controls_on_map: false,
            map_options: {
                set_center: [<?=$center_lat?>,<?=$center_lon?> ],
                zoom: <?=$zoom?>
            }

        }).Load();
    });
  </script>

</html>