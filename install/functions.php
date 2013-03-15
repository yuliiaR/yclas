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
    <div class="alert alert-info">Get free 100% compatible hosting or a professional hosting for just $3.95 month.
	    <a class="btn btn-info" href="http://open-classifieds.com/hosting/">
	        <i class="icon-ok icon-white"></i> Sign now!
	    </a>
    </div>
    <?php }
}


function formatOffset($offset) 
{
        $hours = $offset / 3600;
        $remainder = $offset % 3600;
        $sign = $hours > 0 ? '+' : '-';
        $hour = (int) abs($hours);
        $minutes = (int) abs($remainder / 60);

        if ($hour == 0 AND $minutes == 0) {
            $sign = ' ';
        }
        return $sign . str_pad($hour, 2, '0', STR_PAD_LEFT) .':'. str_pad($minutes,2, '0');
}

///timezones functions
function get_timezones()
{
    if (method_exists('DateTimeZone','listIdentifiers'))
    {
        $utc = new DateTimeZone('UTC');
        $dt  = new DateTime('now', $utc);

        $timezones = array();
        $timezone_identifiers = DateTimeZone::listIdentifiers();

        foreach( $timezone_identifiers as $value )
        {
            $current_tz = new DateTimeZone($value);
            $offset     =  $current_tz->getOffset($dt);

            if ( preg_match( '/^(America|Antartica|Africa|Arctic|Asia|Atlantic|Australia|Europe|Indian|Pacific)\//', $value ) )
            {
                $ex=explode('/',$value);//obtain continent,city
                $city = isset($ex[2])? $ex[1].' - '.$ex[2]:$ex[1];//in case a timezone has more than one
                $timezones[$ex[0]][$value] = $city.' ['.formatOffset($offset).']';
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

function cP($name,$default = NULL)
{
    return (isset($_POST[$name])? $_POST[$name]:$default);
}