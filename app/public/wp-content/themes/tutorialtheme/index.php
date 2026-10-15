<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

    <?php
        if(have_posts()) {
            while(have_posts()) {
                the_post(); ?>
                <a class="link" href="<?php the_permalink()?>">
                    <h2><?php the_title(); ?></h2>
                    <div><?php the_content(); ?></div>
                </a>
                <?php
            }
        } else {
            echo '<p>No posts found.</p>';
        }
    ?>

    <?php wp_footer(); ?>
</body>
</html>