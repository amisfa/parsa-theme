<?php
if (!defined('ABSPATH')) exit;

remove_action('wp_head', 'wp_generator');
function movie_theme_setup()
{
    load_theme_textdomain('movie-theme', get_template_directory() . '/languages');
    add_theme_support('post-thumbnails', array('post', 'movie'));
    register_nav_menus(array(
        'primary' => __('primary', 'movie-theme'),
    ));
}

add_action('after_setup_theme', 'movie_theme_setup');

function enqueue_assets()
{
    wp_enqueue_style('movie-theme-style', get_stylesheet_uri(), array(), filemtime(get_template_directory() . '/style.css'));
    wp_enqueue_script('movie-theme-main', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), filemtime(get_template_directory() . '/assets/js/main.js'), true);
}

add_action('wp_enqueue_scripts', 'enqueue_assets');

function movie_register_post_type()
{
    $labels = array(
        'name' => __('Movies', 'movie-theme'),
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'movies'),
        'supports' => array('title', 'editor', 'thumbnail'),
        'show_in_rest' => true,
    );
    register_post_type('movie', $args);
}

add_action('init', 'movie_register_post_type');

function movie_register_taxonomy()
{
    $labels = array(
        'name' => __('Genres', 'movie-theme'),
        'singular_name' => __('Genre', 'movie-theme'),
    );
    $args = array(
        'hierarchical' => true,
        'labels' => $labels,
        'rewrite' => array('slug' => 'genres'),
        'show_in_rest' => true,
    );
    register_taxonomy('genre', array('movie'), $args);
}

add_action('init', 'movie_register_taxonomy');

function movie_render_meta_box($post)
{
    wp_nonce_field(basename(__FILE__), 'movie_meta_nonce');
    $release_year = get_post_meta($post->ID, '_movie_release_year', true);
    $director = get_post_meta($post->ID, '_movie_director', true);
    $rating = get_post_meta($post->ID, '_movie_rating', true);
    $imdb = get_post_meta($post->ID, '_movie_imdb_rating', true);
    $site_user_rating = get_post_meta($post->ID, '_movie_site_user_rating', true);
    $site_user_votes = get_post_meta($post->ID, '_movie_site_user_votes', true);
    $rotten = get_post_meta($post->ID, '_movie_rotten_tomatoes', true);
    $metacritic = get_post_meta($post->ID, '_movie_metacritic', true);
    ?>
    <p>
        <label for="movie_release_year"><?php esc_html_e('Release Year', 'movie-theme'); ?></label><br>
        <input type="number" id="movie_release_year" name="movie_release_year"
               value="<?php echo esc_attr($release_year); ?>" style="width:100px;">
    </p>

    <p>
        <label for="movie_director"><?php esc_html_e('Director', 'movie-theme'); ?></label><br>
        <input type="text" id="movie_director" name="movie_director"
               value="<?php echo esc_attr($director); ?>" style="width:100%;">
    </p>

    <p>
        <label for="movie_rating"><?php esc_html_e('Rating (0-10)', 'movie-theme'); ?></label><br>
        <input type="number" step="0.1" min="0" max="10" id="movie_rating" name="movie_rating"
               value="<?php echo esc_attr($rating); ?>" style="width:100px;">
    </p>

    <hr>
    <p>
        <label for="movie_imdb"><?php esc_html_e('IMDb Rating (0-10)', 'movie-theme'); ?></label><br>
        <input type="number" id="movie_imdb" name="movie_imdb" step="0.1" min="0" max="10"
               value="<?php echo esc_attr($imdb); ?>" style="width:100px;">
        <br><small><?php esc_html_e('مثال: 7.8', 'movie-theme'); ?></small>
    </p>

    <p>
        <label for="movie_site_user_rating"><?php esc_html_e('Site Users Rating (0-10)', 'movie-theme'); ?></label><br>
        <input type="number" id="movie_site_user_rating" name="movie_site_user_rating" step="0.1" min="0" max="10"
               value="<?php echo esc_attr($site_user_rating); ?>" style="width:100px;">
    </p>

    <p>
        <label for="movie_site_user_votes"><?php esc_html_e('Site User Votes (count)', 'movie-theme'); ?></label><br>
        <input type="number" id="movie_site_user_votes" name="movie_site_user_votes" step="1" min="0"
               value="<?php echo esc_attr($site_user_votes); ?>" style="width:120px;">
    </p>

    <p>
        <label for="movie_rotten"><?php esc_html_e('Rotten Tomatoes (%)', 'movie-theme'); ?></label><br>
        <input type="number" id="movie_rotten" name="movie_rotten" step="1" min="0" max="100"
               value="<?php echo esc_attr($rotten); ?>" style="width:100px;">
        <br><small><?php esc_html_e('مقدار به صورت درصد (0-100)', 'movie-theme'); ?></small>
    </p>

    <p>
        <label for="movie_metacritic"><?php esc_html_e('Metacritic (0-100)', 'movie-theme'); ?></label><br>
        <input type="number" id="movie_metacritic" name="movie_metacritic" step="1" min="0" max="100"
               value="<?php echo esc_attr($metacritic); ?>" style="width:100px;">
    </p>
    <?php
}

function movie_add_meta_boxes()
{
    add_meta_box('movie_details', __('Movie Details', 'movie-theme'), 'movie_render_meta_box', 'movie', 'normal', 'default');
}

add_action('add_meta_boxes', 'movie_add_meta_boxes');

function movie_sanitize_number($value, $min = null, $max = null, $is_float = false)
{
    if ($value === '' || $value === null) {
        return '';
    }
    if ($is_float) {
        $num = floatval($value);
    } else {
        $num = intval($value);
    }
    if ($min !== null && $num < $min) {
        $num = $min;
    }
    if ($max !== null && $num > $max) {
        $num = $max;
    }
    return $num;
}

function movie_save_meta_box($post_id)
{
    if (!isset($_POST['movie_meta_nonce']) || !wp_verify_nonce($_POST['movie_meta_nonce'], basename(__FILE__))) {
        return $post_id;
    }
    // autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }
    if (wp_is_post_revision($post_id)) {
        return $post_id;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }
    if (isset($_POST['movie_release_year'])) {
        $value = sanitize_text_field($_POST['movie_release_year']);
        if ($value === '') {
            delete_post_meta($post_id, '_movie_release_year');
        } else {
            update_post_meta($post_id, '_movie_release_year', intval($value));
        }
    }
    if (isset($_POST['movie_director'])) {
        $value = sanitize_text_field($_POST['movie_director']);
        if ($value === '') {
            delete_post_meta($post_id, '_movie_director');
        } else {
            update_post_meta($post_id, '_movie_director', $value);
        }
    }
    if (isset($_POST['movie_rating'])) {
        $val = sanitize_text_field($_POST['movie_rating']);
        if ($val === '') {
            delete_post_meta($post_id, '_movie_rating');
        } else {
            update_post_meta($post_id, '_movie_rating', floatval($val));
        }
    }
    // (0-10, float)
    if (isset($_POST['movie_imdb'])) {
        $val = $_POST['movie_imdb'];
        $san = movie_sanitize_number($val, 0, 10, true);
        if ($san === '') {
            delete_post_meta($post_id, '_movie_imdb_rating');
        } else {
            update_post_meta($post_id, '_movie_imdb_rating', $san);
        }
    }
    // (0-10, float)
    if (isset($_POST['movie_site_user_rating'])) {
        $val = $_POST['movie_site_user_rating'];
        $san = movie_sanitize_number($val, 0, 10, true);
        if ($san === '') {
            delete_post_meta($post_id, '_movie_site_user_rating');
        } else {
            update_post_meta($post_id, '_movie_site_user_rating', $san);
        }
    }
    // (integer >=0)
    if (isset($_POST['movie_site_user_votes'])) {
        $val = $_POST['movie_site_user_votes'];
        $san = movie_sanitize_number($val, 0, null, false);
        if ($san === '') {
            delete_post_meta($post_id, '_movie_site_user_votes');
        } else {
            update_post_meta($post_id, '_movie_site_user_votes', intval($san));
        }
    }
    // (0-100 integer)
    if (isset($_POST['movie_rotten'])) {
        $val = $_POST['movie_rotten'];
        $san = movie_sanitize_number($val, 0, 100, false);
        if ($san === '') {
            delete_post_meta($post_id, '_movie_rotten_tomatoes');
        } else {
            update_post_meta($post_id, '_movie_rotten_tomatoes', intval($san));
        }
    }
    // (0-100 integer)
    if (isset($_POST['movie_metacritic'])) {
        $val = $_POST['movie_metacritic'];
        $san = movie_sanitize_number($val, 0, 100, false);
        if ($san === '') {
            delete_post_meta($post_id, '_movie_metacritic');
        } else {
            update_post_meta($post_id, '_movie_metacritic', intval($san));
        }
    }
}

add_action('save_post', 'movie_save_meta_box');
function movie_theme_create_default_menu()
{
    $menu_name = 'primary';
    $menu_location = 'primary';
    $menu_obj = wp_get_nav_menu_object($menu_name);
    if ($menu_obj && !empty($menu_obj->term_id)) {
        $menu_id = (int)$menu_obj->term_id;
    } else {
        $menu_id = wp_create_nav_menu($menu_name);
        if (is_wp_error($menu_id)) {
            return;
        }
    }
    $menu_items = wp_get_nav_menu_items($menu_id);
    if (empty($menu_items)) {
        $menu_items = ['Categories', 'Movie', 'Series', 'Actors', 'Latest dubbing'];
        foreach ($menu_items as $item) {
            wp_update_nav_menu_item($menu_id, 0, array(
                'menu-item-title' => __($item, 'movie-theme'),
                'menu-item-url' => home_url('/#'),
                'menu-item-status' => 'publish'
            ));
        }
    }
    $locations = get_theme_mod('nav_menu_locations');
    if (!is_array($locations)) $locations = array();
    $locations[$menu_location] = $menu_id;
    set_theme_mod('nav_menu_locations', $locations);
}

add_action('after_switch_theme', 'movie_theme_create_default_menu');
function movie_create_default_movies()
{
    if (get_option('movie_theme_default_movies_installed')) {
        return;
    }
    $defaults = array(
        array(
            'title' => 'The Beginning',
            'content' => 'An exciting start to an epic saga.',
            'year' => 2020,
            'director' => 'Jane Doe',
            'rating' => 7.8,
            'image' => 'assets/img/1.jpg',
            'slug' => 'the-beginning',
            'imdb' => 7.8,
            'site_user_rating' => 7.6,
            'site_user_votes' => 214,
            'rotten' => 82,
            'metacritic' => 71,
        ),
        array(
            'title' => 'Lost Stars',
            'content' => 'A dramatic tale of hope and loss.',
            'year' => 2019,
            'director' => 'John Smith',
            'rating' => 8.2,
            'image' => 'assets/img/2.jpg',
            'slug' => 'lost-stars',
            'imdb' => 8.1,
            'site_user_rating' => 8.0,
            'site_user_votes' => 482,
            'rotten' => 88,
            'metacritic' => 77,
        ),
        array(
            'title' => 'Midnight Journey',
            'content' => 'A mysterious road-trip thriller.',
            'year' => 2021,
            'director' => 'Ali Reza',
            'rating' => 7.1,
            'image' => 'assets/img/3.jpg',
            'slug' => 'midnight-journey',
            'imdb' => 7.0,
            'site_user_rating' => 6.9,
            'site_user_votes' => 138,
            'rotten' => 69,
            'metacritic' => 62,
        ),
        array(
            'title' => 'Hidden Truth',
            'content' => 'Secrets come to light in this tense drama.',
            'year' => 2018,
            'director' => 'Sara K.',
            'rating' => 8.0,
            'image' => 'assets/img/4.jpg',
            'slug' => 'hidden-truth',
            'imdb' => 7.9,
            'site_user_rating' => 8.1,
            'site_user_votes' => 305,
            'rotten' => 86,
            'metacritic' => 74,
        ),
        array(
            'title' => 'Final Act',
            'content' => 'A thrilling conclusion you will not forget.',
            'year' => 2022,
            'director' => 'Mehdi N.',
            'rating' => 8.5,
            'image' => 'assets/img/5.jpg',
            'slug' => 'final-act',
            'imdb' => 8.4,
            'site_user_rating' => 8.3,
            'site_user_votes' => 612,
            'rotten' => 91,
            'metacritic' => 85,
        ),
    );
    if (function_exists('movie_register_post_type')) {
        movie_register_post_type();
    }
    $sanitize_number = function ($value, $min = null, $max = null, $is_float = false) {
        if ($value === '' || $value === null) {
            return '';
        }
        $num = $is_float ? floatval($value) : intval($value);
        if ($min !== null && $num < $min) {
            $num = $min;
        }
        if ($max !== null && $num > $max) {
            $num = $max;
        }
        return $num;
    };

    foreach ($defaults as $movie) {
        $exists = get_page_by_path($movie['slug'], OBJECT, 'movie');
        if ($exists) {
            continue;
        }
        $postarr = array(
            'post_title' => wp_strip_all_tags($movie['title']),
            'post_name' => sanitize_title($movie['slug']),
            'post_content' => $movie['content'],
            'post_status' => 'publish',
            'post_type' => 'movie',
        );
        $post_id = wp_insert_post($postarr);
        if (is_wp_error($post_id) || $post_id === 0) {
            error_log("movie_create_default_movies: failed to insert post {$movie['title']}");
            continue;
        }
        update_post_meta($post_id, '_movie_release_year', intval($movie['year']));
        update_post_meta($post_id, '_movie_director', sanitize_text_field($movie['director']));
        update_post_meta($post_id, '_movie_rating', floatval($movie['rating']));
        if (function_exists('movie_sanitize_number')) {
            $sfn = 'movie_sanitize_number';
        } else {
            $sfn = null;
        }
        if (isset($movie['imdb'])) {
            $san = $sfn ? movie_sanitize_number($movie['imdb'], 0, 10, true) : $sanitize_number($movie['imdb'], 0, 10, true);
            if ($san === '') {
                delete_post_meta($post_id, '_movie_imdb_rating');
            } else {
                update_post_meta($post_id, '_movie_imdb_rating', floatval($san));
            }
        }
        if (isset($movie['site_user_rating'])) {
            $san = $sfn ? movie_sanitize_number($movie['site_user_rating'], 0, 10, true) : $sanitize_number($movie['site_user_rating'], 0, 10, true);
            if ($san === '') {
                delete_post_meta($post_id, '_movie_site_user_rating');
            } else {
                update_post_meta($post_id, '_movie_site_user_rating', floatval($san));
            }
        }
        if (isset($movie['site_user_votes'])) {
            $san = $sfn ? movie_sanitize_number($movie['site_user_votes'], 0, null, false) : $sanitize_number($movie['site_user_votes'], 0, null, false);
            if ($san === '') {
                delete_post_meta($post_id, '_movie_site_user_votes');
            } else {
                update_post_meta($post_id, '_movie_site_user_votes', intval($san));
            }
        }
        if (isset($movie['rotten'])) {
            $san = $sfn ? movie_sanitize_number($movie['rotten'], 0, 100, false) : $sanitize_number($movie['rotten'], 0, 100, false);
            if ($san === '') {
                delete_post_meta($post_id, '_movie_rotten_tomatoes');
            } else {
                update_post_meta($post_id, '_movie_rotten_tomatoes', intval($san));
            }
        }
        if (isset($movie['metacritic'])) {
            $san = $sfn ? movie_sanitize_number($movie['metacritic'], 0, 100, false) : $sanitize_number($movie['metacritic'], 0, 100, false);
            if ($san === '') {
                delete_post_meta($post_id, '_movie_metacritic');
            } else {
                update_post_meta($post_id, '_movie_metacritic', intval($san));
            }
        }
        $img = isset($movie['image']) ? $movie['image'] : '';
        $full_path = '';
        $is_temp_download = false;
        if ($img && (strpos($img, get_template_directory_uri()) === 0 || strpos($img, get_stylesheet_directory_uri()) === 0)) {
            $full_path = str_replace(
                array(get_stylesheet_directory_uri(), get_template_directory_uri()),
                array(get_stylesheet_directory(), get_template_directory()),
                $img
            );
        } elseif ($img && preg_match('#^https?://#i', $img)) {
            $resp = wp_remote_get($img);
            if (!is_wp_error($resp) && wp_remote_retrieve_response_code($resp) === 200) {
                $body = wp_remote_retrieve_body($resp);
                $tmp = wp_tempnam($img);
                if ($tmp && file_put_contents($tmp, $body) !== false) {
                    $full_path = $tmp;
                    $is_temp_download = true;
                }
            }
        } else {
            $candidate = trailingslashit(get_stylesheet_directory()) . ltrim($img, '/');
            if (file_exists($candidate)) {
                $full_path = $candidate;
            } else {
                $candidate2 = trailingslashit(get_template_directory()) . ltrim($img, '/');
                if (file_exists($candidate2)) {
                    $full_path = $candidate2;
                } elseif (file_exists($img)) {
                    $full_path = $img;
                }
            }
        }
        if (empty($full_path)) {
            error_log("movie_create_default_movies: image file not found for {$movie['title']} — provided: " . $movie['image']);
            if ($is_temp_download && file_exists($full_path)) {
                @unlink($full_path);
            }
            continue;
        }
        if (!is_readable($full_path)) {
            error_log("movie_create_default_movies: image file not readable: $full_path");
            if ($is_temp_download && file_exists($full_path)) {
                @unlink($full_path);
            }
            continue;
        }
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';
        $upload = wp_upload_dir();
        if (!wp_mkdir_p($upload['path'])) error_log("movie_create_default_movies: failed to ensure upload dir {$upload['path']}");
        $filename = wp_unique_filename($upload['path'], basename($full_path));
        $new_file = trailingslashit($upload['path']) . $filename;
        if (!@copy($full_path, $new_file)) {
            error_log("movie_create_default_movies: failed to copy $full_path to $new_file");
            if ($is_temp_download && file_exists($full_path)) {
                @unlink($full_path);
            }
            continue;
        }
        $filetype = wp_check_filetype($filename, null);
        $attachment = array(
            'guid' => trailingslashit($upload['url']) . $filename,
            'post_mime_type' => $filetype['type'] ?: 'image/jpeg',
            'post_title' => sanitize_file_name(pathinfo($filename, PATHINFO_FILENAME)),
            'post_content' => '',
            'post_status' => 'inherit',
        );
        $attach_id = wp_insert_attachment($attachment, $new_file, $post_id);
        if (is_wp_error($attach_id) || !$attach_id) {
            error_log("movie_create_default_movies: wp_insert_attachment failed for {$new_file}");
            if ($is_temp_download && file_exists($full_path)) {
                @unlink($full_path);
            }
            continue;
        }
        $attach_data = wp_generate_attachment_metadata($attach_id, $new_file);
        wp_update_attachment_metadata($attach_id, $attach_data);

        if (!current_theme_supports('post-thumbnails')) {
            add_theme_support('post-thumbnails');
        }
        if (!set_post_thumbnail($post_id, $attach_id)) {
            error_log("movie_create_default_movies: set_post_thumbnail failed for post {$post_id} attachment {$attach_id}");
        } else {
            error_log("movie_create_default_movies: thumbnail set for post {$post_id} (attachment {$attach_id})");
        }
        if ($is_temp_download && file_exists($full_path)) {
            @unlink($full_path);
        }
    }
    update_option('movie_theme_default_movies_installed', 1);
}

function movie_theme_after_switch()
{
    delete_option('movie_theme_default_movies_installed');
    movie_register_post_type();
    movie_register_taxonomy();
    flush_rewrite_rules();
    if (function_exists('movie_theme_create_default_menu')) {
        movie_theme_create_default_menu();
    }
    movie_create_default_movies();
}

add_action('after_switch_theme', 'movie_theme_after_switch');

