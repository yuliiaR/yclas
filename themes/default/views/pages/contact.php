<?php defined('SYSPATH') or die('No direct script access.');?>


Captcha*:<br />
<?php echo captcha::image_tag('contact');?><br />
<input id="captcha" name="captcha" type="text"  />
