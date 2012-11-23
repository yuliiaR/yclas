<?php
//twitter theme initialization
View::$styles	            = array('http://netdna.bootstrapcdn.com/twitter-bootstrap/2.2.1/css/bootstrap-combined.min.css' => 'screen');

//@todo add in the header some sompatibility js?  html5shiv already at main template
View::$scripts['header']	= array('http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js',	
									'http://netdna.bootstrapcdn.com/twitter-bootstrap/2.2.1/js/bootstrap.min.js'
									);

/**
 * custom error alerts
 */
Form::$errors_tpl 	= '<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a>
			       		<h4 class="alert-heading">%s</h4>
			        	<ul>%s</ul></div>';
Form::$error_tpl 	= '<div class="alert"><a class="close" data-dismiss="alert">×</a>%s</div>';


Alert::$tpl 	= 	'<div class="alert alert-%s">
					<a class="close" data-dismiss="alert" href="#">×</a>
					<h4 class="alert-heading">%s</h4>%s
					</div>';