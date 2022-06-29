<?php


function render_pagination_links($cur_page = 1, $max_pages = 1)
{
    $pagenum_link = html_entity_decode(get_pagenum_link());
    $url_parts = explode('?', $pagenum_link);
    $pagenum_link = trailingslashit($url_parts[0]) . '%_%';
    if (isset($url_parts[1])) {
        $pagenum_link .= '?' . $url_parts[1];
    }

    if ($cur_page == 0) $cur_page = 1;
    if ($cur_page <= $max_pages && $max_pages != 1):
        ?>

        <div class="pagination">
            <div class="pagination__count">
                <?php echo sprintf('Page %s from %s', $cur_page, $max_pages); ?>
            </div>
            <ul class="pagination__list">
                <?php if ($cur_page > 1 && $cur_page <= $max_pages):
                    if ($cur_page-1 == 1):
                        ?>
                        <li class="pagination__item is-back">
                            <a href="<?= str_replace('%_%', '?page=', '?page='.($cur_page - 1)); ?>"
                               class="pagination__link">
                                < </a>
                        </li>
                    <?php else: ?>
                        <li class="pagination__item is-back">
                            <a href="<?= str_replace('%_%', '?page=' . ($cur_page - 1), $pagenum_link); ?>"
                               class="pagination__link">
                                < </a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $max_pages; $i++): ?>
                    <li class="pagination__item <?php if ($i == $cur_page) echo 'is-active' ?>">
                        <a href="<?= str_replace('%_%', '?page=' . $i, '?page=' . $i); ?>"
                           class="pagination__link"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
                <?php if ($cur_page != $max_pages && $cur_page < $max_pages): ?>
                    <li class="pagination__item is-next">
                        <a href="<?= str_replace('%_%', '?page=' . ($cur_page + 1), '?page=' . ($cur_page + 1)); ?>"
                           class="pagination__link">
                            > </a>
                    </li>
                    <li class="pagination__item is-forward">
                        <a href="<?= str_replace('%_%', '?page=', '?page='.$max_pages); ?>"
                           class="pagination__link"> >> </a>
                    </li>
                <?php endif; ?>

            </ul>

        </div>

    <?php endif;
}