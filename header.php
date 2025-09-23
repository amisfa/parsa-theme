<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

</head>
<body <?php body_class(); ?>>
<header class="site-header">
    <div class="flex shadow-md py-2 pl-4 pr-2 sm:px-10 bg-[#242629] min-h-[70px] tracking-wide relative z-50">
        <div class="flex items-center justify-start w-full max-lg:justify-between justify-start">
            <div class="flex w-18 justify-center">
                <button id="toggleOpen" class="lg:hidden cursor-pointer text-white text-2xl">
                    <i class="ti ti-menu-2"></i>
                </button>
            </div>
            <a href="javascript:void(0)">
                <img src="<?php echo get_template_directory_uri() . "/assets/img/logo-parsa.png" ?>" alt="logo"
                     class="w-36"/></a>
            <div id="collapseMenu"
                 class="max-lg:hidden lg:!block max-lg:before:fixed max-lg:before:bg-black max-lg:before:opacity-50 max-lg:before:inset-0 max-lg:before:z-50">
                <button id="toggleClose"
                        class="lg:hidden fixed top-2 right-4 z-[100] rounded-full bg-white w-9 h-9 flex items-center justify-center border border-gray-200 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 fill-black"
                         viewBox="0 0 320.591 320.591">
                        <path
                            d="M30.391 318.583a30.37 30.37 0 0 1-21.56-7.288c-11.774-11.844-11.774-30.973 0-42.817L266.643 10.665c12.246-11.459 31.462-10.822 42.921 1.424 10.362 11.074 10.966 28.095 1.414 39.875L51.647 311.295a30.366 30.366 0 0 1-21.256 7.288z"
                            data-original="#000000"></path>
                        <path
                            d="M287.9 318.583a30.37 30.37 0 0 1-21.257-8.806L8.83 51.963C-2.078 39.225-.595 20.055 12.143 9.146c11.369-9.736 28.136-9.736 39.504 0l259.331 257.813c12.243 11.462 12.876 30.679 1.414 42.922-.456.487-.927.958-1.414 1.414a30.368 30.368 0 0 1-23.078 7.288z"
                            data-original="#000000"></path>
                    </svg>
                </button>
                <?php if (has_nav_menu('primary')) : ?>
                    <ul
                        class="lg:flex mb-0 gap-x-4 max-lg:fixed max-lg:bg-white max-lg:w-1/2 max-lg:min-w-[300px] max-lg:top-0 max-lg:left-0 max-lg:px-6 max-lg:h-full max-lg:shadow-md max-lg:overflow-auto z-50">
                        <?php
                        $items = wp_get_nav_menu_items('primary');
                        foreach ($items as $item) {
                            echo "<li class='max-lg:border-b max-lg:border-gray-300 max-lg:py-3 px-3'><a href='javascript:void(0)'
                           class='block font-medium text-[15px] lg:text-white'>{$item->title}</a></li>";
                        }
                        ?>
                    </ul>
                <?php endif; ?>
            </div>
            <div class="flex flex-row lg:hidden">
                <div class="flex items-center justify-content-center">
                    <div class="font-bold px-3 py-3 rounded flex items-center cursor-pointer text-white text-xl">
                        <i class="ti ti-bell"></i>
                    </div>
                </div>
                <div class="flex items-center justify-content-center">
                    <div class="font-bold px-3 py-3 rounded flex items-center cursor-pointer text-white text-xl">
                        <i class="ti ti-chevron-left"></i>
                    </div>
                </div>
            </div>

        </div>
        <div class="flex flex-row-reverse max-lg:hidden">
            <div class="flex items-center justify-content-center px-1">
                <div class="bg-[#1b1c1f] font-bold px-3 py-3 rounded flex items-center cursor-pointer text-white text-xl max-lg:text-dark">
                    <i class="ti ti-user-circle"></i>
                </div>
            </div>
            <div class="flex items-center justify-content-center px-1">
                <div class="bg-[#1b1c1f] font-bold px-3 py-3 rounded flex items-center cursor-pointer text-white text-xl max-lg:text-dark">
                    <i class="ti ti-search"></i>
                </div>
            </div>
            <div class="flex items-center justify-content-center px-1">
                <div class="bg-[#1b1c1f] font-bold px-3 py-3 rounded flex items-center cursor-pointer text-white text-xl max-lg:text-dark">
                    <i class="ti ti-bookmarks"></i>
                </div>
            </div>
            <div class="flex items-center justify-content-center px-1">
                <div class="bg-[#1b1c1f] font-bold px-3 py-3 rounded flex items-center cursor-pointer text-white text-xl max-lg:text-dark">
                    <i class="ti ti-bell"></i>
                </div>
            </div>
            <div class="flex items-center justify-content-center">
                <div class="font-bold px-3 py-3 rounded flex items-center cursor-pointer text-[#545454] text-lg">
                    <i class="ti ti-chevron-left"></i>
                </div>
            </div>

        </div>
    </div>
</header>
<main id="main" class="site-main wrap">
