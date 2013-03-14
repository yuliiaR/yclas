<?php


function __($s){
	return $s;
}

function T_($s){
	return $s;
}

function hostingAd()
{
    if (SAMBA){
    ?>
    <div class="alert alert-info">We have 100% compatible hosting, starting from $3 montlhy.
	    <a class="btn btn-info" href="http://open-classifieds.com/hosting/">
	        <i class="icon-ok icon-white"></i> Host now!
	    </a>
    </div>
    <?php }
}



///timezones functions
function get_timezones()
{
    if (method_exists('DateTimeZone','listIdentifiers'))
    {
        $timezones = array();
        $timezone_identifiers = DateTimeZone::listIdentifiers();

        foreach( $timezone_identifiers as $value )
        {
            if ( preg_match( '/^(America|Antartica|Africa|Arctic|Asia|Atlantic|Australia|Europe|Indian|Pacific)\//', $value ) )
            {
                $ex=explode('/',$value);//obtain continent,city
                $city = isset($ex[2])? $ex[1].' - '.$ex[2]:$ex[1];//in case a timezone has more than one
                $timezones[$ex[0]][$value] = $city;
            }
        }
        return $timezones;
    }
    else//old php version
    {
        return FALSE;
    }
}

function get_select_timezones($select_name='TIMEZONE',$selected=NULL)
{
	$sel='';
    $timezones = get_timezones();
    $sel.='<select id="'.$select_name.'" name="'.$select_name.'">';
    foreach( $timezones as $continent=>$timezone )
    {
        $sel.= '<optgroup label="'.$continent.'">';
        foreach( $timezone as $city=>$cityname )
        {            
            if ($selected==$city)
            {
                $sel.= '<option selected="selected" value="'.$city.'">'.$cityname.'</option>';
            }
            else $sel.= '<option value="'.$city.'">'.$cityname.'</option>';
        }
        $sel.= '</optgroup>';
    }
    $sel.='</select>';

    return $sel;
}