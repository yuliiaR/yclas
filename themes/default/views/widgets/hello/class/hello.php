<?php defined('SYSPATH') or die('No direct access allowed.');


class Widget_Hello extends Widget
{

	public $placeholder_name = 'footer';

	public static function print()
	{
		echo '$title';
	}
}
