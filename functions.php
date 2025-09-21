<?php
if (!defined('ABSPATH')) {
    exit;
}
remove_action('wp_head', 'wp_generator');

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

function movie_theme_after_switch()
{
    movie_register_post_type();
    movie_register_taxonomy();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'movie_theme_after_switch');

function movie_add_meta_boxes()
{
    add_meta_box('movie_details', __('Movie Details', 'movie-theme'), 'movie_render_meta_box', 'movie', 'normal', 'default');
}
add_action('add_meta_boxes', 'movie_add_meta_boxes');

function movie_render_meta_box($post)
{
    wp_nonce_field(basename(__FILE__), 'movie_meta_nonce');
    $release_year = get_post_meta($post->ID, '_movie_release_year', true);
    $director = get_post_meta($post->ID, '_movie_director', true);
    $rating = get_post_meta($post->ID, '_movie_rating', true);
    ?>
    <p>
        <label for="movie_release_year"><?php esc_html_e('Release Year', 'movie-theme'); ?></label><br>
        <input type="number" id="movie_release_year" name="movie_release_year"
               value="<?php echo esc_attr($release_year); ?>" style="width:100px;">
    </p>
    <p>
        <label for="movie_director"><?php esc_html_e('Director', 'movie-theme'); ?></label><br>
        <input type="text" id="movie_director" name="movie_director" value="<?php echo esc_attr($director); ?>"
               style="width:100%;">
    </p>
    <p>
        <label for="movie_rating"><?php esc_html_e('Rating (0-10)', 'movie-theme'); ?></label><br>
        <input type="number" step="0.1" min="0" max="10" id="movie_rating" name="movie_rating"
               value="<?php echo esc_attr($rating); ?>" style="width:100px;">
    </p>
    <?php
}
function movie_save_meta($post_id)
{
    if (!isset($_POST['movie_meta_nonce']) || !wp_verify_nonce($_POST['movie_meta_nonce'], basename(__FILE__))) return $post_id;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
    if (!current_user_can('edit_post', $post_id)) return $post_id;
    if (isset($_POST['movie_release_year'])) update_post_meta($post_id, '_movie_release_year', intval(sanitize_text_field(wp_unslash($_POST['movie_release_year']))));
    if (isset($_POST['movie_director'])) update_post_meta($post_id, '_movie_director', sanitize_text_field(wp_unslash($_POST['movie_director'])));
    if (isset($_POST['movie_rating'])) update_post_meta($post_id, '_movie_rating', floatval(sanitize_text_field(wp_unslash($_POST['movie_rating']))));
}

add_action('save_post', 'movie_save_meta');
