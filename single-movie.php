<?php get_header();

$paged = get_query_var('paged') ? intval(get_query_var('paged')) : 1;
$args = array(
    'post_type' => 'movie',
    'posts_per_page' => 3,
    'paged' => $paged,
);
$relatedMovies = new WP_Query($args);

?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <?php
    $thumb_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
    if (!$thumb_url) {
        $thumb_url = get_stylesheet_directory_uri() . '/assets/img/default-movie.jpg';
    }
    $imdb = get_post_meta(get_the_ID(), '_movie_imdb_rating', true);
    ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <div class="lg:px-12 sm:px-4 px-2 w-full">
            <div class="flex justify-between w-full py-3">
                <div class="flex flex-column items-center">
                    <div class="flex items-center justify-content-center px-1 lg:hidden">
                        <div
                            class="font-bold px-3 py-3 rounded flex items-center cursor-pointer text-white text-xl bg-[#242629]">
                            <i class="ti ti-bookmarks"></i>
                        </div>
                    </div>
                    <div class="flex items-center justify-content-center px-1 lg:hidden">
                        <div
                            class="font-bold px-3 py-3 rounded flex items-center cursor-pointer text-[#707070] text-xl bg-[#242629]">
                            <i class="ti ti-share"></i>
                        </div>
                    </div>

                </div>
                <div class="flex items-center justify-content-center">
                    <div
                        class="font-bold px-3 py-3 rounded flex items-center justify-center cursor-pointer text-white bg-[#242629]">
                        <div class="text-lg px-1">
                            <div>
                                <?php echo __('Back', 'movie-theme'); ?>
                            </div>
                        </div>
                        <div class="text-sm px-1">
                            <i class="ti ti-chevron-left"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-3">
                <div class="col-span-2 p-4 m-2 w-full max-lg:col-span-3">
                    <div class="flex justify-between w-full max-lg:hidden">
                        <div class="flex">
                            <div class="px-1">
                                <img src="<?php echo get_template_directory_uri() . "/assets/img/title-quality.png" ?>"
                                     class="w-12"/>
                            </div>
                            <div class="text-white px-1 flex items-center text-xl">
                                <?php echo the_title(); ?>
                            </div>
                        </div>
                        <div
                            class="font-bold px-3 py-3 rounded flex items-center justify-center cursor-pointer text-white bg-[#242629]">
                            <div class="text-lg px-1">
                                <div>1080p - webdl کیفیت</div>
                            </div>
                            <div class="text-sm px-1">
                                <i class="ti ti-chevron-down"></i>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col w-full lg:hidden">
                        <div class="text-white px-1 flex items-center text-xl">
                            <?php echo the_title() . ' ' . __('Movie', 'movie-theme') ?>
                        </div>
                        <div class="text-white px-1 flex items-center text-lg">
                            <?php echo __('Path', 'movie-theme') ?>
                        </div>
                        <div class="flex justify-between items-center">
                            <div class="text-white flex">
                                <img src="<?php echo get_template_directory_uri() . "/assets/img/disable-star.png" ?>"
                                     class="h-8 cursor-pointer"/>
                                <img src="<?php echo get_template_directory_uri() . "/assets/img/disable-star.png" ?>"
                                     class="h-8 cursor-pointer"/>
                                <img src="<?php echo get_template_directory_uri() . "/assets/img/disable-star.png" ?>"
                                     class="h-8 cursor-pointer"/>
                                <img src="<?php echo get_template_directory_uri() . "/assets/img/gold-star.png" ?>"
                                     class="h-8 cursor-pointer"/>
                                <img src="<?php echo get_template_directory_uri() . "/assets/img/gold-star.png" ?>"
                                     class="h-8 cursor-pointer"/>
                            </div>
                            <div class="text-white flex justify-between">
                                <div class="flex items-center flex-col justify-center text-lg">
                                    <?php echo $imdb; ?>/10
                                </div>
                                <img src="<?php echo get_template_directory_uri() . "/assets/img/imdb-img.png" ?>"
                                     class="h-12 p-1 cursor-pointer rounded-lg"/>

                            </div>
                        </div>
                    </div>
                    <div class="relative mt-2 rounded-xl overflow-hidden">
                        <img src="<?php echo esc_url($thumb_url); ?>"
                             alt="<?php echo esc_attr(get_the_title()); ?>"
                             class="w-full h-auto"/>
                        <div class="absolute inset-0 flex items-center justify-center z-10"
                             style="backdrop-filter: brightness(0.7);">
                            <i class="ti ti-player-play text-4xl sm:text-6xl md:text-7xl text-white"></i>
                        </div>
                    </div>
                    <div class="flex xl:flex-row-reverse justify-between max-xl:flex-col py-2">
                        <div class="flex justify-between max-xl:pb-2">
                            <div class="px-1">
                                <div
                                    class="font-bold px-3 py-3 rounded flex items-center justify-center cursor-pointer text-[#9DA5B2]">
                                    <div class="text-md px-1">
                                        <div>
                                            <?php echo __('Did you encounter any problems while watching', 'movie-theme'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="px-1">
                                <div
                                    class="font-bold px-3 py-3 rounded flex items-center justify-center cursor-pointer text-white bg-[#242629]">
                                    <div class="text-md px-1">
                                        <div>
                                            <?php echo __('Report a problem', 'movie-theme'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex max-xl:justify-center ">
                            <div class="px-1">
                                <div
                                    class="font-bold px-3 py-3 rounded flex items-center justify-center cursor-pointer text-white bg-[#710F15]">
                                    <div class="text-md px-1">
                                        <div>
                                            <?php echo __('Samsung player', 'movie-theme'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="px-1">
                                <div
                                    class="font-bold px-3 py-3 rounded flex items-center justify-center cursor-pointer text-white bg-[#242629]">
                                    <div class="text-md px-1">
                                        <div>
                                            <?php echo __('Samsung player', 'movie-theme'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="px-1">
                                <div
                                    class="font-bold px-3 py-3 rounded flex items-center justify-center cursor-pointer text-white bg-[#242629]">
                                    <div class="text-md px-1">
                                        <div>
                                            <?php echo __('Samsung player', 'movie-theme'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="xl:hidden bg-[#2e2217] rounded-md mt-2">
                            <div>
                                <div
                                    class="font-bold rounded flex items-center justify-between text-white">
                                    <div class="flex items-center">
                                        <div class="flex items-center justify-content-center pl-1">
                                            <div
                                                class="font-bold px-3 py-3 rounded flex items-center cursor-pointer text-[#fb7800] text-xl bg-[#493018]">
                                                <i class="ti ti-alert-square-rounded"></i>
                                            </div>
                                        </div>
                                        <div class="text-sm ">
                                            <?php echo __('Turn off your VPN before you start watching online.', 'movie-theme') ?>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-content-center">
                                        <div
                                            class="font-bold px-3 py-2 rounded flex items-center cursor-pointer text-[#fb7800] text-xl bg-[#1b1c1f] border-1 border-solid">
                                            <?php echo __('Buy a subscription', 'movie-theme') ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-span-1 p-4 m-2 w-full max-lg:col-span-3">
                    <div class="flex justify-between items-center max-lg:hidden">
                        <div class="text-white flex">
                            <img src="<?php echo get_template_directory_uri() . "/assets/img/disable-star.png" ?>"
                                 class="h-8 cursor-pointer"/>
                            <img src="<?php echo get_template_directory_uri() . "/assets/img/disable-star.png" ?>"
                                 class="h-8 cursor-pointer"/>
                            <img src="<?php echo get_template_directory_uri() . "/assets/img/disable-star.png" ?>"
                                 class="h-8 cursor-pointer"/>
                            <img src="<?php echo get_template_directory_uri() . "/assets/img/gold-star.png" ?>"
                                 class="h-8 cursor-pointer"/>
                            <img src="<?php echo get_template_directory_uri() . "/assets/img/gold-star.png" ?>"
                                 class="h-8 cursor-pointer"/>
                        </div>
                        <div class="text-white flex justify-between">
                            <div class="flex items-center flex-col justify-center text-lg">
                                <?php echo $imdb; ?>/10
                            </div>
                            <img src="<?php echo get_template_directory_uri() . "/assets/img/imdb-img.png" ?>"
                                 class="h-12 p-1 cursor-pointer rounded-lg"/>
                            <div class="flex flex-column items-center">
                                <div class="flex items-center justify-content-center px-1">
                                    <div
                                        class="font-bold px-3 py-3 rounded flex items-center cursor-pointer text-white text-xl bg-[#242629]">
                                        <i class="ti ti-bookmarks"></i>
                                    </div>
                                </div>
                                <div class="flex items-center justify-content-center px-1">
                                    <div
                                        class="font-bold px-3 py-3 rounded flex items-center cursor-pointer text-[#707070] text-xl bg-[#242629]">
                                        <i class="ti ti-share"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <?php while ($relatedMovies->have_posts()) :
                        $relatedMovies->the_post();
                        $thumb_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
                        if (!$thumb_url) {
                            $thumb_url = get_stylesheet_directory_uri() . '/assets/img/default-movie.jpg';
                        }
                        $release_year = get_post_meta(get_the_ID(), '_movie_release_year', true);
                        $director = get_post_meta(get_the_ID(), '_movie_director', true);
                        $rating = get_post_meta(get_the_ID(), '_movie_rating', true);
                        $imdb = get_post_meta(get_the_ID(), '_movie_imdb_rating', true);
                        $site_user_rating = get_post_meta(get_the_ID(), '_movie_site_user_rating', true);
                        $site_user_votes = get_post_meta(get_the_ID(), '_movie_site_user_votes', true);
                        $rotten = get_post_meta(get_the_ID(), '_movie_rotten_tomatoes', true);
                        $metacritic = get_post_meta(get_the_ID(), '_movie_metacritic', true);
                        ?>
                        <div class="flex w-full px-3 py-2">
                            <div class="w-24 h-32 my-1"
                                 style="background-image: url(<?php echo esc_url($thumb_url); ?>); background-size: cover; background-position: center;">
                                <div class="flex justify-center h-full items-end">
                                    <div class="flex items-center justify-content-center">
                                        <div
                                            class="font-bold p-1 rounded flex items-center cursor-pointer text-white text-md bg-[#fb7800]">
                                            <i class="ti ti-flame"></i>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-content-center px-1">
                                        <div
                                            class="font-bold p-1 rounded flex items-center cursor-pointer text-white text-md bg-[#245def]">
                                            <i class="ti ti-badge-cc"></i>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-content-center">
                                        <div
                                            class="font-bold p-1 rounded flex items-center cursor-pointer text-white text-md bg-[#43b100]">
                                            <i class="ti ti-microphone"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-col w-full">
                                <div class="text-white px-1 flex items-center text-md">
                                    <?php echo the_title(); ?>
                                </div>
                                <div class="text-[#8d949f] px-1 flex items-center text-md">
                                    6.5 per meter
                                </div>
                                <div class="text-[#8d949f] px-1 flex items-center text-md">
                                    اجتماعی،سیاسی، هیجانی
                                </div>
                                <div class="flex justify-between items-center">
                                    <div class="text-white">2012</div>
                                    <div class="text-white flex justify-between">
                                        <div class="flex items-center flex-col justify-center text-md">
                                            <?php echo __('(12 Vote)', 'movie-theme') . ' ' . $rating; ?>
                                        </div>
                                        <img
                                            src="<?php echo get_template_directory_uri() . "/assets/img/gold-star.png" ?>"
                                            class="h-8 p-1 cursor-pointer rounded-lg"/>
                                    </div>
                                </div>
                                <div class="text-white flex justify-between px-2">
                                    <div class="text-white flex justify-between">
                                        <div class="flex items-center flex-col justify-center text-md">
                                            <?php echo $imdb; ?>/10
                                        </div>
                                        <img src="<?php echo get_template_directory_uri() . "/assets/img/imdb-img.png" ?>"
                                             class="h-8 p-1 cursor-pointer rounded-lg"/>
                                    </div>
                                    <div class="text-white flex justify-between">
                                        <div class="flex items-center flex-col justify-center text-md">
                                            <?php echo $rotten; ?>/10
                                        </div>
                                        <img src="<?php echo get_template_directory_uri() . "/assets/img/rt.png" ?>"
                                             class="h-8 p-1 cursor-pointer rounded-lg"/>
                                    </div>
                                    <div class="text-white flex justify-between">
                                        <div class="flex items-center flex-col justify-center text-md">
                                            <?php echo $metacritic; ?>
                                        </div>
                                        <img src="<?php echo get_template_directory_uri() . "/assets/img/m.png" ?>"
                                             class="h-8 p-1 cursor-pointer rounded-lg"/>
                                    </div>
                                </div>

                            </div>
                        </div>
                    <?php endwhile;wp_reset_postdata(); ?>
                        <div class="flex justify-center max-xl:pb-2">
                            <div class="px-1">
                                <div class="font-bold px-3 py-3 rounded flex items-center justify-center cursor-pointer text-white">
                                    <div class="text-md px-1 flex">
                                        <div class="px-2">
                                            <?php echo __('View movies', 'movie-theme'); ?>
                                        </div>
                                        <i class="ti ti-arrow-left text-xl"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
            </div>
        </div>
        </div>


    </article>
<?php endwhile; else: ?>
    <p><?php esc_html_e('No posts found', 'movie-theme'); ?></p>
<?php endif; ?>

<?php get_footer(); ?>
