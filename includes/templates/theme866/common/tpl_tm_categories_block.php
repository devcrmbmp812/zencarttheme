<?php // if (!$this_is_home_page) { ?>
	<div id="tm_categories_block" class="module_block">
		<div class="module-heading">
			<h3 class="module-head">Categories</h3>
		</div>
		<div id="tm_categories" class="clearfix">
			<?php echo $_SESSION['category_tree']->buildCategoryString(
			'<ul class="{class}">{child}</ul>','<li class="{class}"><a class="{class} category-top" href="{link}" title="{name}"><span class="{class}">{name}</span></a>{child}</li>', 0, 0);?>
		</div>
	</div>
<?php // } ?>