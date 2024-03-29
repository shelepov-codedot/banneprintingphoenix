<?php

class Kama_SEO_Tags
{

    static function init()
    {

        // force WP document_title function to run
        add_theme_support('title-tag');
        add_filter('pre_get_document_title', [__CLASS__, 'meta_title'], 1);

        add_action('wp_head', [__CLASS__, 'meta_description'], 1);
        add_action('wp_head', [__CLASS__, 'meta_keywords'], 1);
        add_action('wp_head', [__CLASS__, 'og_meta'], 1); // Open Graph, twitter данные

        // WP 5.7+
        add_filter('wp_robots', [__CLASS__, 'wp_robots_callback'], 11);
    }

    /**
     * Open Graph, twitter data in `<head>`.
     *
     * @See Documentation: http://ogp.me/
     */
    static function og_meta()
    {

        $obj = get_queried_object();

        if (isset($obj->post_type)) $post = $obj;
        elseif (isset($obj->term_id)) $term = $obj;

        $is_post = isset($post);
        $is_term = isset($term);

        $title = self::meta_title();
        $desc = preg_replace('/^.+content="([^"]*)".*$/s', '$1', self::meta_description());

        // Open Graph
        $els = [];
        $els['og:locale'] = get_locale();
        $els['og:site_name'] = get_bloginfo('name');
        $els['og:title'] = $title;
        $els['og:description'] = $desc;
        $els['og:type'] = is_singular() ? 'article' : 'object';

        // og:url
        if ('url') {

            if ($is_post) $url = get_permalink($post);
            if ($is_term) $url = get_term_link($term);

            if (!empty($url)) {

                $els['og:url'] = $url;

                // relative (not allowed)
                if ('/' === $url[0]) {

                    // without protocol only: //domain.com/path
                    if (substr($url, 0, 2) === '//') {
                        $els['og:url'] = set_url_scheme($url);
                    } // without domain
                    else {
                        $parts = wp_parse_url($url);
                        $els['og:url'] = home_url($parts['path']) . (isset($parts['query']) ? "?{$parts['query']}" : '');
                    }
                }

            }
        }

        /**
         * Allow to disable `article:section` property.
         *
         * @param bool $is_on
         */
        if (apply_filters('kama_og_meta_show_article_section', true)) {

            if (is_singular() && $post_taxname = get_object_taxonomies($post->post_type)) {

                $post_terms = get_the_terms($post, reset($post_taxname));
                if ($post_terms && $post_term = array_shift($post_terms))
                    $els['article:section'] = $post_term->name;
            }
        }

        // og:image
        if ('image') {

            /**
             * Allow to change `og:image` `og:image:width` `og:image:height` values.
             *
             * @param int|string|array|WP_Post $image_data WP attachment ID or Image URL or Array [ image_url, width, height ].
             */
            $image = apply_filters('pre_kama_og_meta_image', null);

            if (!$image) {

                $attach_id_from_text__fn = static function ($text) {

                    if (
                        preg_match('/<img +src *= *[\'"]([^\'"]+)[\'"]/', $text, $mm)
                        &&
                        ('/' === $mm[1][0] || strpos($mm[1], $_SERVER['HTTP_HOST']))
                    ) {
                        $name = basename($mm[1]);
                        $name = preg_replace('~-[0-9]+x[0-9]+(?=\..{2,6})~', '', $name); // удалим размер (-80x80)
                        $name = preg_replace('~\.[^.]+$~', '', $name);                   // удалим расширение
                        $name = sanitize_title(sanitize_file_name($name));

                        global $wpdb;
                        $attach_id = $wpdb->get_var($wpdb->prepare(
                            "SELECT ID FROM $wpdb->posts WHERE post_name = %s AND post_type = 'attachment'", $name
                        ));

                        return (int)$attach_id;
                    }

                    return 0;
                };

                if ($is_post) {

                    $image = get_post_thumbnail_id($post);

                    if (!$image) {

                        /**
                         * Allows to turn off the image search in post content.
                         *
                         * @param bool $is_on
                         */
                        if (apply_filters('kama_og_meta_thumb_id_find_in_content', true)) {

                            // первое вложение поста
                            if (!$image = $attach_id_from_text__fn($post->post_content)) {

                                $attach = get_children([
                                    'numberposts' => 1,
                                    'post_mime_type' => 'image',
                                    'post_type' => 'attachment',
                                    'post_parent' => $post->ID,
                                ]);

                                if ($attach && $attach = array_shift($attach))
                                    $image = $attach->ID;
                            }
                        }
                    }
                } elseif ($is_term) {

                    $image = get_term_meta($term->term_id, '_thumbnail_id', 1);

                    if (!$image)
                        $image = $attach_id_from_text__fn($term->description);
                }

                /**
                 * Allow to set `og:image` `og:image:width` `og:image:height` values if it's not.
                 *
                 * @param int|string|array|WP_Post $image_data WP attachment ID or Image URL or Array [ image_url, width, height ].
                 */
                $image = apply_filters('kama_og_meta_image', $image);
                $image = apply_filters('kama_og_meta_thumb_id', $image); // backcompat
            }

            if ($image) {

                if ($image instanceof WP_Post || is_numeric($image)) {

                    if (is_numeric($image))
                        $image = get_post($image);

                    if ($image) {

                        list(
                            $els['og:image[1]'],
                            $els['og:image[1]:width'],
                            $els['og:image[1]:height'],
                            $els['og:image[1]:alt'],
                            $els['og:image[1]:type']
                            ) = array_merge(
                            array_slice(image_downsize($image->ID, 'full'), 0, 3),
                            [$image->post_excerpt, $image->post_mime_type]
                        );

                        if (!$els['og:image[1]:alt'])
                            unset($els['og:image[1]:alt']);

                        // thumbnail
                        list(
                            $els['og:image[2]'],
                            $els['og:image[2]:width'],
                            $els['og:image[2]:height']
                            ) = array_slice(image_downsize($image->ID, 'thumbnail'), 0, 3);
                    }
                } elseif (is_array($image)) {
                    list(
                        $els['og:image[1]'],
                        $els['og:image[1]:width'],
                        $els['og:image[1]:height']
                        ) = $image;
                } else {
                    $els['og:image[1]'] = $image;
                }

            }

        }

        // twitter
        $els['twitter:card'] = 'summary';
        $els['twitter:title'] = $els['og:title'];
        $els['twitter:description'] = $els['og:description'];
        if (!empty($els['og:image[1]'])) {
            $els['twitter:image'] = $els['og:image[1]'];
        }

        /**
         * Allows change values of og / twitter meta properties.
         *
         * @param array $els
         */
        $els = apply_filters('kama_og_meta_elements_values', $els);
        $els = array_filter($els);
        ksort($els);

        // make <meta> tags
        $metas = [];
        foreach ($els as $key => $val) {

            // og:image[1] > og:image  ||  og:image[1]:width > og:image:width
            $fixed_key = preg_replace('/\[\d\]/', '', $key);

            if (0 === strpos($key, 'twitter:'))
                $metas[] = '<meta name="' . $fixed_key . '" content="' . esc_attr($val) . '" />';
            else
                $metas[] = '<meta property="' . $fixed_key . '" content="' . esc_attr($val) . '" />';
        }

        /**
         * Filter resulting properties. Allows to add or remove any og/twitter properties.
         *
         * @param array $els
         */
        $metas = apply_filters('kama_og_meta_elements', $metas, $els);

        echo "\n\n" . implode("\n", $metas) . "\n\n";
    }

    /**
     * Generate string to show as document title.
     *
     * For posts and taxonomies specific title can be specified as metadata with name `title`.     *
     *
     * @param string $title `pre_get_document_title` passed value.
     *
     * @return string
     */
    static function meta_title($title = '')
    {
        global $post;

        $affter = true;

        // support for `pre_get_document_title` hook.
        if ($title)
            return $title;

        static $cache;
        if ($cache) return $cache;

        $l10n = apply_filters('kama_meta_title_l10n', [
            '404' => '404 error',
            'search' => 'Search results for the query: %s',
            'compage' => 'Comments %s',
            'author' => 'Author\'s articles: %s',
            'archive' => 'Archive for',
            'paged' => 'Page %d',
        ]);

        $parts = [
            'prev' => '',
            'title' => '',
            'page' => '',
            'after' => '',
        ];

        //Seo Tags from admin
        $term = get_queried_object();
        $meta_data = get_field( 'metadata', $term );

        // 404
        if (is_404()) {
            $parts['title'] = $l10n['404'];
        } // search
        elseif (is_search()) {
            $parts['title'] = sprintf($l10n['search'], get_query_var('s'));
        } // front_page
        elseif (is_front_page()) {
            if ( $meta_data['title'] ) {
                $parts['title'] = $meta_data['title'];
            } else {
                if (is_page() && $parts['title'] = get_post_meta($post->ID, 'title', 1)) {
                    // $parts['title'] defined
                } else {
                    $parts['title'] = get_bloginfo('name', 'display');
                    if ($affter) $parts['after'] = '{{description}}';
                }
            }
        } // singular
        elseif (is_singular() || (is_home() && !is_front_page()) || (is_page() && !is_front_page())) {
            if ( $meta_data['title'] ) {
                $parts['title'] = $meta_data['title'];
            } else {
                $parts['title'] = get_post_meta($post->ID, 'title', 1);

                if (!$parts['title']) {
                    /**
                     * Allow to set meta title for singular type page, before the default title will be taken.
                     *
                     * @param string $title
                     * @param WP_Post $post
                     */
                    $parts['title'] = apply_filters('kama_meta_title_singular', '', $post);

                }

                if (!$parts['title']) {
                    $parts['title'] = single_post_title('', 0);
                }

                if (!is_page()) {
                    $parts['title'] = 'Phoenix ' . $parts['title'].' printing - best price';
                }

                if ($cpage = get_query_var('cpage')) {

                    $parts['prev'] = sprintf($l10n['compage'], $cpage);
                }
            }

            var_dump();
        } // post_type_archive
        elseif (is_post_type_archive()) {
            $parts['title'] = post_type_archive_title('', 0);
            if ($affter) $parts['after'] = '{{blog_name}}';
        } // taxonomy
        elseif (is_category() || is_tag() || is_tax()) {
            if ( $meta_data['title'] ) {
                $parts['title'] = $meta_data['title'];
            } else {
                $parts['title'] = $term ? get_term_meta($term->term_id, 'title', 1) : '';

                if (!$parts['title']) {
                    $parts['title'] = single_term_title('', 0);


                    if (is_tax()) {

//                    echo 'prev 2';
                        $parts['prev'] = 'Phoenix ';//get_taxonomy( $term->taxonomy )->labels->name;
                        $parts['title'] .= ' printing - best price';
                    }
                }
            }

            if ($affter) $parts['after'] = '{{blog_name}}';
        } // author posts archive
        elseif (is_author()) {
            $parts['title'] = sprintf($l10n['author'], get_queried_object()->display_name);
            if ($affter) $parts['after'] = '{{blog_name}}';
        } // date archive
        elseif ((get_locale() === 'ru_RU') && (is_day() || is_month() || is_year())) {
            $rus_month = [
                '', 'январь', 'февраль', 'март', 'апрель', 'май', 'июнь',
                'июль', 'август', 'сентябрь', 'октябрь', 'ноябрь', 'декабрь'
            ];
            $rus_month2 = [
                '', 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня',
                'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'
            ];
            $year = get_query_var('year');
            $monthnum = get_query_var('monthnum');
            $day = get_query_var('day');

            if (is_year()) $dat = "$year год";
            elseif (is_month()) $dat = "{$rus_month[ $monthnum ]} $year года";
            elseif (is_day()) $dat = "$day {$rus_month2[ $monthnum ]} $year года";

            $parts['title'] = sprintf($l10n['archive'], $dat);
            if ($affter) $parts['after'] = '{{blog_name}}';
        } // other archives
        else {
            $parts['title'] = get_the_archive_title();
            if ($affter) $parts['after'] = '{{blog_name}}';
        }

        // pagination
        $pagenum = get_query_var('paged') ?: get_query_var('page');
        if ($pagenum && !is_404()) {
            $parts['page'] = sprintf($l10n['paged'], $pagenum);
        }

        /**
         * Allows to change parts of the document title.
         *
         * @param array $parts Title parts. It then will be joined.
         * @param array $l10n Localisation strings.
         */
        $parts = apply_filters('kama_meta_title_parts', $parts, $l10n);

        /** This filter is documented in wp-includes/general-template.php */
        $parts = apply_filters('document_title_parts', $parts);

        // handle placeholders
        if ('{{blog_name}}' === $parts['after']) {
            if ($affter) $parts['after'] = get_bloginfo('name', 'display');
        } elseif ('{{description}}' === $parts['after']) {
            if ($affter) $parts['after'] = get_bloginfo('description', 'display');
        }

        if ($post->ID != 79 && empty($meta_data['title'])) {
            $parts['after'] = '| bannerprintingphoenix.com';
        } else {
            $parts['after'] = '';
        }

        /** This filter is documented in wp-includes/general-template.php */
        $sep = apply_filters('document_title_separator', ' ');

        $title = implode(' ' . trim($sep) . ' ', array_filter($parts));

        $title = wptexturize($title);
        $title = convert_chars($title);
        $title = esc_html($title);
        $title = capital_P_dangit($title);

        $title = preg_replace('/ {2,}/',' ',$title);

        return $cache = $title;
    }

    /**
     * Display `description` metatag.
     *
     * Must be used on hook `wp_head`.
     *
     * Use `description` meta-field to set description for any posts.
     * It also work for page setted as front page.
     *
     * Use `meta_description` meta-field to set description for any terms.
     * Or use default `description` field of a term.
     *
     * @return string Description.
     */
    static function meta_description()
    {

        // called from `wp_head` hook
        $echo_result = (func_num_args() === 1);

        static $cache = null;
        if (isset($cache)) {

            if ($echo_result)
                echo $cache;

            return $cache;
        }

        global $post;

        $desc = '';
        $need_cut = true;
        $term = get_queried_object();
        $meta_data = get_field( 'metadata', $term );

        // front
        if (is_front_page()) {
            if ( $meta_data['description'] ) {
                $desc = $meta_data['description'];
            } else {
                // когда для главной установлена страница
                if (is_page()) {
                    //$desc = get_post_meta($post->ID, 'description', true);
                    $desc = 'Print Banners in Phoenix to promote your Business or Event ⚡️ Free Phoenix Delivery ✅ Call Us 📱 +1 480-885-6844 ';
                    $need_cut = false;
                }

                if (!$desc) {

                    /**
                     * Allow to change front_page meta description.
                     *
                     * @param string $home_description
                     */
                    $desc = apply_filters('home_meta_description', get_bloginfo('description', 'display'));
                }
            }
        } // any post
        elseif (is_singular()) {
            if ( $meta_data['description'] ) {
                $desc = $meta_data['description'];
            } else {
                if ($desc = get_post_meta($post->ID, 'description', true)) {
                    $need_cut = false;
                }

                if (!$desc) {
                    //$desc = $post->post_excerpt ?: $post->post_content;
                    $desc = 'Print ' . $post->post_title . ' in Phoenix ✅ Full color printing ✅ 🔥 Free Phoenix Delivery 🔥 Call us 📱 +1 480-885-6844 ';
                }

                if (get_post_type($post->ID) == 'page') {
                    $desc = $post->post_title . ' ✅ Full color printing ✅ 🔥 Free Phoenix Delivery 🔥 Call us 📱 +1 480-885-6844 ';
                }

                $desc = trim(strip_tags($desc));
            }
        } // any term (taxonomy element)
        elseif (($term = get_queried_object()) && !empty($term->term_id)) {
            if ( $meta_data['description'] ) {
                $desc = $meta_data['description'];
            } else {
                $desc = get_term_meta($term->term_id, 'meta_description', true);

                if (!$desc)
                    $desc = get_term_meta($term->term_id, 'description', true);

                $need_cut = false;
                if (!$desc && $term->description) {
                    $desc = strip_tags($term->description);
                    $need_cut = true;
                }
            }
        }

        $desc = str_replace(["\n", "\r"], ' ', $desc);

        // remove shortcodes, but leave markdown [foo](URL)
        $desc = preg_replace('~\[[^\]]+\](?!\()~', '', $desc);

        /**
         * Allow change or set the meta description.
         *
         * @param string $desc Current description.
         * @param string $origin_desc Description before cut.
         * @param bool $need_cut Is need to cut?
         * @param int $maxchar How many characters leave after cut.
         */
        $desc = apply_filters('kama_meta_description', $desc);

        /**
         * Allow to specify is the meta description need to be cutted.
         *
         * @param bool $need_cut
         */
        $need_cut = apply_filters('kama_meta_description__need_cut', $need_cut);

        if ($need_cut) {

            /**
             * Allow set max length of the meta description.
             *
             * @param int $maxchar
             */
            $maxchar = apply_filters('kama_meta_description__maxchar', 260);

            $char = mb_strlen($desc);

            if ($char > $maxchar) {
                $desc = mb_substr($desc, 0, $maxchar);
                $words = explode(' ', $desc);
                $maxwords = count($words) - 1; // remove last word, it incomplete in 90% cases
                $desc = implode(' ', array_slice($words, 0, $maxwords)) . ' ...';
            }
        }

        // remove multi-space
        $desc = preg_replace('/\s+/s', ' ', $desc);

        if (is_category() || is_tag() || is_tax()) {
            $title = single_term_title('', 0);

            if ( $meta_data['description'] ) {
                $desc = $meta_data['description'];
            } else {
                if (is_tax()) {
                    $desc = 'Print '.$title.' in Phoenix for Indoor and Outdoor Advertising 🔥 Free Phoenix Delivery 🔥 Call us 📱 +1 480-885-6844 ';
                }
            }
        }

        $cache = $desc
            ? sprintf("<meta name=\"description\" content=\"%s\" />\n", esc_attr(trim($desc)))
            : '';

        if ($echo_result)
            echo $cache;

        return $cache;
    }

    /**
     * Wrpper for WP Robots API introduced in WP 5.7+.
     *
     * Must be used on hook `wp_robots`.
     *
     * @param array $robots
     */
    static function wp_robots_callback($robots)
    {

        if (is_singular()) {
            $robots_str = get_post_meta(get_queried_object_id(), 'robots', true);
        } elseif (is_tax() || is_category() || is_tag()) {
            $robots_str = get_term_meta(get_queried_object_id(), 'robots', true);
        }

        if (!empty($robots_str)) {

            // split by spece or comma
            $robots_parts = preg_split('/(?<!:)[\s,]+/', $robots_str, -1, PREG_SPLIT_NO_EMPTY);

            foreach ($robots_parts as $directive) {

                // for max-snippet:2
                if (strpos($directive, ':')) {
                    list($key, $value) = explode(':', $directive);
                    $robots[$key] = $value;
                } else {
                    $robots[$directive] = true;
                }
            }
        }

        if (!empty($robots['none']) || !empty($robots['noindex'])) {
            unset($robots['max-image-preview']);
        }

        return $robots;
    }

    /**
     * Generate `<meta name="keywords">` meta-tag fore <head> part of the page.
     *
     * To set Your own keywords for post, create meta-field with key `keywords`
     * and set the keyword to the value.
     *
     * Default keyword for a post generates from post tags nemes and categories names.
     * If the `keywords` meta-field is not specified.
     *
     * You can specify the keywords for the any taxonomy element (term) using shortcode
     * `[keywords=word1, word2, word3]` in the description field.
     *
     * @param string $home_keywords Keywords for home page. Ex: 'word1, word2, word3'
     * @param string $def_keywords сквозные ключевые слова - укажем и они будут прибавляться
     *                              к остальным на всех страницах.
     */
    static function meta_keywords($home_keywords = '', $def_keywords = '')
    {
        global $post;

        $out = [];

        if (is_front_page()) {
            $out[] = $home_keywords;
        } elseif (is_singular()) {

            $meta_keywords = get_post_meta($post->ID, 'keywords', true);

            if ($meta_keywords) {
                $out[] = $meta_keywords;
            } elseif ($post->post_type === 'post') {

                $res = wp_get_object_terms($post->ID, ['post_tag', 'category'], ['orderby' => 'none']);

                if ($res && !is_wp_error($res)) {
                    foreach ($res as $tag) {
                        $out[] = $tag->name;
                    }
                }
            }

        } elseif (is_category() || is_tag() || is_tax()) {
            $term = get_queried_object();

            // wp 4.4
            if (function_exists('get_term_meta') && $term) {
                $out[] = get_term_meta($term->term_id, 'keywords', true);
            } else {
                preg_match('!\[keywords=([^\]]+)\]!iU', $term->description, $match);
                $out[] = isset($match[1]) ? trim($match[1]) : '';
            }
        }

        if ($def_keywords) {
            $out[] = $def_keywords;
        }

        echo $out
            ? '<meta name="keywords" content="' . implode(', ', $out) . '" />' . "\n"
            : '';
    }

}