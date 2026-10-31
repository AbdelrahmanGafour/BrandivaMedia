<?php get_header(); ?>
<main id="main">
<section class="phero"><div class="container">
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<h1 class="h1"><?php the_title(); ?></h1>
<div class="intro lead"><?php the_content(); ?></div>
<?php endwhile; else : ?><p class="lead"><?php bv_te('no_results'); ?></p><?php endif; ?>
</div></section>
</main>
<?php get_footer(); ?>
