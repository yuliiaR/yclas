<?php defined('SYSPATH') or die('No direct script access.');?>
<div style="background: #F5F5F5;">
	<div class="container">
		<div class="row">
			<ul class="breadcrumb" style="margin-bottom:0px;">
				<? foreach ($breadcrumbs as $crumb) : ?>
					<? if ($crumb->get_url() !== NULL) :  ?>
						<li>
							<a title="<?=HTML::chars($crumb->get_title())?>" href="<?=$crumb->get_url()?>">
							<?=$crumb->get_title()?>
							</a>
						</li>
					<? else : ?>
						<li class="active"><?=$crumb->get_title()?></li>
					<? endif; ?>
				<?endforeach; ?>
			</ul>
		</div>
	</div>
</div>
