<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="pagination">
  <ul>
		<li <?=(!$first_page)?'class="active"':''?>>
			<a title="<?=__('First')?> <?=$page->title()?>" href="<?=HTML::chars($page->url($first_page))?>" rel="first"><i class="icon-step-backward"></i></a>
		</li>
	
		<li <?=(!$previous_page)?'class="active"':''?>>
			<a title="<?=__('Previous')?> <?=$page->title()?>" href="<?=HTML::chars($page->url($previous_page))?>" rel="prev"><i class="icon-backward"></i></a>
		</li>
	
	<?for ($i = 1; $i <= $total_pages; $i++): ?>
		<li <?=($i == $current_page)?'class="active"':''?>>
			<a title="<?=__('Page')?> <?=$i?> <?=$page->title()?>" href="<?=HTML::chars($page->url($i)) ?>"><?=$i?></a>
		</li>
	<?endfor ?>

		<li <?=(!$next_page)?'class="active"':''?>>
			<a title="<?=__('Next')?> <?=$page->title()?>" href="<?=HTML::chars($page->url($next_page)) ?>" rel="next"><i class="icon-forward"></i></a>
		</li>

		<li <?=(!$last_page)?'class="active"':''?>>
			<a title="<?=__('Last')?> <?=$page->title()?>" href="<?=HTML::chars($page->url($last_page)) ?>" rel="last"><i class="icon-step-forward"></i></a>
		</li>
  </ul>
</div><!-- .pagination -->