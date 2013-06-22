<?php defined('SYSPATH') or die('No direct access allowed!'); 
  
class TextTest extends Kohana_UnitTest_TestCase 
{ 


    function provider_bb2html()
    {
        return array(
            array('[b]bold[/b]', '<b>bold</b>'),
            array('[i]cursive[/i]', '<i>cursive</i>'),
            array('[u]underline[/u]', '<u>underline</u>'),
        );
    }

    /**
     * @dataProvider provider_bb2html
     */
    public function test_bb2html($bbcode,$html) 
    {
        $this->assertEquals(Text::bb2html($bbcode),$html); 
    } 


}