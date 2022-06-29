<ul id="subtabs"  class='tabs'>
    <li><a tabid="101" class='subtheme current'>Sources & Basics</a></li>
    <li><a tabid="102" class='subtheme'>Behavior</a></li>
    <li><a tabid="104" class='subtheme'>Ordering</a></li>
    <li><a tabid="103" class='subtheme'>Autocomplete & Suggestions</a></li>
</ul>
<div class='tabscontent'>
    <div tabid="101">
        <fieldset>
            <legend><?php _e("Sources & Basics", "ajax-search-lite"); ?>
				<span class="asl_legend_docs">
					<a target="_blank" href="https://documentation.ajaxsearchlite.com/general-options/sources-and-basics"><span class="fa fa-book"></span>
						<?php echo __('Documentation', 'ajax-search-lite'); ?>
					</a>
				</span>
			</legend>
            <?php include(ASL_PATH."backend/tabs/instance/general/sources.php"); ?>
        </fieldset>
    </div>
    <div tabid="102">
		<?php include(ASL_PATH."backend/tabs/instance/general/behavior.php"); ?>
    </div>
    <div tabid="104">
        <fieldset>
            <legend><?php _e("Ordering", "ajax-search-lite"); ?>
				<span class="asl_legend_docs">
					<a target="_blank" href="https://documentation.ajaxsearchlite.com/general-options/ordering"><span class="fa fa-book"></span>
						<?php echo __('Documentation', 'ajax-search-lite'); ?>
					</a>
				</span>
			</legend>
            <?php include(ASL_PATH."backend/tabs/instance/general/ordering.php"); ?>
        </fieldset>
    </div>
    <div tabid="103">
        <fieldset>
            <legend><?php _e("Autocomplete & Suggestions", "ajax-search-lite"); ?>
				<span class="asl_legend_docs">
					<a target="_blank" href="https://documentation.ajaxsearchlite.com/general-options/autocomplete-and-keyword-suggestions"><span class="fa fa-book"></span>
						<?php echo __('Documentation', 'ajax-search-lite'); ?>
					</a>
				</span>
			</legend>
            <?php include(ASL_PATH."backend/tabs/instance/general/autocomplete.php"); ?>
        </fieldset>
    </div>
</div>
<div class="item">
    <input type="hidden" name='asl_submit' value=1 />
    <input name="submit_asl" type="submit" value="<?php _e("Save options!", "ajax-search-lite"); ?>" />
</div>