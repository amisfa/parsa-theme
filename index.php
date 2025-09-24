<?php
get_header();

$paged = get_query_var('paged') ? intval(get_query_var('paged')) : 1;
$args = array(
    'post_type' => 'movie',
    'posts_per_page' => 9,
    'paged' => $paged,
);
$movies = new WP_Query($args);
?>

<?php if ($movies->have_posts()) : ?>
    <div id="movie-slider" class="w-full h-full relative">
        <div class="swiper progress-slide-carousel h-full">
            <div class="swiper-wrapper" id="swiper-wrapper">
                <?php while ($movies->have_posts()) : $movies->the_post();
                    $thumb_url = has_post_thumbnail() ? esc_url(wp_get_attachment_image_url(get_post_thumbnail_id(), 'large')) : '';
                    ?>
                    <div class="swiper-slide h-full">
                        <?php
                        $inner_style = '';
                        if ($thumb_url) {
                            $inner_style = "background-image: url('{$thumb_url}'); background-size: cover; background-position: center;h-full";
                        }
                        ?>
                        <div class="bg-indigo-50 rounded-2xl h-full flex justify-center items-center "
                             style="<?php echo $inner_style; ?>">
                            <a href="<?php the_permalink(); ?>"
                               class="w-full h-full flex items-center justify-center rounded-2xl backdrop-brightness-50"
                               title="<?php the_title_attribute(); ?>">
                                <div class="glitch max-lg:text-2xl text-6xl max-sm:text-lg">
                                <span aria-hidden="true">
                                    <?php the_title(); ?>
                                </span>
                                    <?php the_title(); ?>
                                    <span aria-hidden="true">
                                    <?php the_title(); ?>
                                </span>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php endwhile;
                wp_reset_postdata(); ?>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
<?php else: ?>
    <p><?php esc_html_e('No movies found.', 'movie-theme'); ?></p>
<?php endif; ?>

<?php get_footer(); ?>
