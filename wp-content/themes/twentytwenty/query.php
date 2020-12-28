<?php
 /* 
	 Template Name: Custom Query
	 https://www.youtube.com/watch?v=Y5rvMw3EH24&t=287s
	 https://www.billerickson.net/code/wp_query-arguments/
  */ 
 ?>

<main id="site-content" role="main">

	<div class="section-inner">
		<h1>This is custom query</h1>
		<?php

		$arg = array(
			'post_type' => 'post',
			'posts_per_page' => 4
		);

		$query = new WP_Query($arg);

		while ($query->have_posts()) : $query->the_post();?>

		       <h5><?php the_title(); ?></h5>

		   <?php endwhile; wp_reset_query(); ?>
		
	</div>

</main><!-- #site-content -->