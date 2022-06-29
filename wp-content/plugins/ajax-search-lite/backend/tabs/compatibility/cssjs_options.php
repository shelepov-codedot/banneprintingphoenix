<div class="item">
	<?php
	$o = new wpdreamsCustomSelect("js_source",  __('Javascript source', 'ajax-search-lite'), array(
			'selects'   => array(
				array('option' => __('Non minified', 'ajax-search-lite'), 'value' => 'nomin'),
				array('option' => __('Minified', 'ajax-search-lite'), 'value' => 'min'),
				array('option' => __('Non-minified scoped', 'ajax-search-lite'), 'value' => 'nomin-scoped'),
				array('option' => __('Minified scoped', 'ajax-search-lite'), 'value' => 'min-scoped'),
			),
			'value'     => $com_options['js_source']
		)
	);
	$params[$o->getName()] = $o->getData();
	?>
	<p class="descMsg">
	<ul style="float:right;text-align:left;width:50%;">
		<li><?php echo __('<b>Non minified</b> - Optimal Compatibility, Medium space', 'ajax-search-lite'); ?></li>
		<li><?php echo __('<b>Minified</b> - Optimal Compatibility, Low space (recommended)', 'ajax-search-lite'); ?></li>
		<li><?php echo __('<b>Non minified Scoped</b> - High Compatibility, High space', 'ajax-search-lite'); ?></li>
		<li><?php echo __('<b>Minified Scoped</b> - High Compatibility, Medium space', 'ajax-search-lite'); ?></li>
	</ul>
	<div class="clear"></div>
	</p>
</div>
<div class="item">
	<?php
	$o = new wpdreamsCustomSelect("js_init", __('Javascript init method', 'ajax-search-lite'), array(
			'selects'=>array(
				array('option'=>__('Dynamic (default)', 'ajax-search-lite'), 'value'=>'dynamic'),
				array('option'=>__('Blocking', 'ajax-search-lite'), 'value'=>'blocking')
			),
			'value'=>$com_options['js_init']
		)
	);
	$params[$o->getName()] = $o->getData();
	?>
	<p class="descMsg">
		<?php echo __('Try to choose <strong>Blocking</strong> if the search bar is not responding to anything.', 'ajax-search-lite'); ?>
	</p>
</div>
<div class="item">
	<?php $o = new wpdreamsYesNo("detect_ajax", __('Try to re-initialize if the page was loaded via ajax?', 'ajax-search-lite'),
		$com_options['detect_ajax']
	); ?>
	<p class='descMsg'>
		<?php echo __('Will try to re-initialize the plugin in case an AJAX page loader is used, like Polylang language switcher etc..', 'ajax-search-lite'); ?>
	</p>
</div>
<div class="item">
	<p class='infoMsg'>
		<?php echo __('You can turn some of these off, if you are not using them.', 'ajax-search-lite'); ?>
	</p>
	<?php $o = new wpdreamsYesNo("js_retain_popstate", __('Remember search phrase and options when using the Browser Back button?', 'ajax-search-lite'),
		$com_options['js_retain_popstate']
	); ?>
	<p class='descMsg'>
		<?php echo __('Whenever the user clicks on a live search result, and decides to navigate back, the search will re-trigger and reset the previous options.', 'ajax-search-lite'); ?>
	</p>
</div>
<div class="item">
	<?php $o = new wpdreamsYesNo("js_fix_duplicates", __('Try fixing DOM duplicates of the search bar if they exist?', 'ajax-search-lite'),
		$com_options['js_fix_duplicates']
	); ?>
	<p class='descMsg'>
		<?php echo __('Some menu or widgets scripts tend to <strong>clone</strong> the search bar completely for Mobile viewports, causing a malfunctioning search bar with no event handlers. When this is active, the plugin script will try to fix that, if possible.', 'ajax-search-lite'); ?>
	</p>
</div>
<div class="item">
	<?php $o = new wpdreamsYesNo("load_google_fonts", __('Load the <strong>google fonts</strong> used in the search options?', 'ajax-search-lite'),
		$com_options['load_google_fonts']
	); ?>
	<p class='descMsg'>
		<?php echo __('When <strong>turned off</strong>, the google fonts <strong>will not be loaded</strong> via this plugin at all.<br>Useful if you already have them loaded, to avoid mutliple loading times.', 'ajax-search-lite'); ?>
	</p>
</div>
<div class="item">
	<?php
	$o = new wpdreamsCustomSelect("load_scroll_js", "Load the scrollbar script?", array(
			'selects'=>array(
				array('option'=>'Yes', 'value'=>'yes'),
				array('option'=>'No', 'value'=>'no')
			),
			'value'=>$com_options['load_scroll_js']
		)
	);
	$params[$o->getName()] = $o->getData();
	?>
	<p class='descMsg'>
	<ul>
		<li>When set to <strong>No</strong>, the custom scrollbar will <strong>not be used at all</strong>.</li>
	</ul>
	</p>
</div>
<div class="item">
	<?php $o = new wpdreamsYesNo("old_browser_compatibility", __('Display the default search box on old browsers? (IE<=8)', 'ajax-search-lite'),
		$com_options['old_browser_compatibility']
	); ?>
</div>