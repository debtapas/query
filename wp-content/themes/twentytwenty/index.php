<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0

	Turotial : https://www.youtube.com/watch?v=Y5rvMw3EH24&t=287s
	Ref :	 https://www.billerickson.net/code/wp_query-arguments/

 */


get_header(); ?>



<main id="site-content" role="main">

	<div class="section-inner">
		<h2>This is custom query</h2>

		<?php

		$arg = array(
			'post_type' => 'post',
			'posts_per_page' => 1,
		);

		$query = new WP_Query($arg);

		while ($query->have_posts()) : $query->the_post();
					$dontshowthispost = $post->ID; // Show Post ID
				?>
		       <h4><?php the_title(); ?></h4>
		   <?php endwhile; wp_reset_query(); ?>

		   <hr>

		 <?php
			 $arg = array(
			 	'post_type' => 'post',									// Custom post name
			 	'posts_per_page'		=>	5,							// Category show per page
			 	//'post__not_in'		=>	array($dontshowthispost),	// Showes except this post
			 	//'meta_key'			=>	'name',						// ACF slug name
			 	//'meta_compare'		=>	'!=',						// Compare with meta_key and meta_value
			 	//'meta_value'			=>	'jill',						// Show by order name
			 	//'orderby'				=>	'meta_value_num',			// Ordered by title, rand, author, comment_count, meta_value
			 	//'order'				=>	'ASC',						// Ordered by ASC and DESC
			 	//'cat'					=>	3,							// Category show by cat ID
			 	//'category_name'		=>	'cookies',					// Category show by category name (not recomended)
			 	//'category__and'		=>	array(4, 5),				// Category show by multiple category ID
			 	//'category__not_in'	=>	array(4, 5),				// Category show except by multiple category 
			 	//'category_in'			=>	array(4, 5),				// Category show by one of them categories
			 	//'tag'					=>	'css',						// Tag show by tag name (not recomended)
			 	//'tag_id'				=>	16,							// Tag show by tag ID
			 	//'tag__and'			=>	array(4, 5),				// Tag show by multiple tag ID
			 	//'tag__not_in'			=>	array(4, 5),				// Tag show except by multiple category 
			 	//'tag_in'				=>	array(4, 5)					// Tag show by one of them tags
			 	//'tag_slug__and'		=>	array('carft', 'sass'),		// Tag show by multiple tag slugs
			 	//'tag_slug__in'		=>	array('carft', 'sass')		// Tag show by slug but show not both, show only one of them tags
			 );

			$query = new WP_Query($arg);

			while($query->have_posts()) : $query->the_post(); ?>
				<h5><?php the_title(); ?></h5>
				<p><?php the_category(); ?></p>
				<p><?php the_tags(); ?></p>	
			<?php endwhile; wp_reset_query(); ?>   


			<hr>

			<h2>Custom Query - Part 05 - meta_query</h2>

			<?php
				$arg = array(
					'post_type'			=>	'post',
					'post_per_page'		=>	-1,
					'meta_query' 		=> array(
												'relation'		=>	'OR', // Relation between those arrays
												array(
													// Short by size meta key 'size'
													array(
														'key'		=> 'size',
														'value'		=> 'xl',
														'type'		=> 'CHAR',
														'compare'	=> '='
													),

													// Short by size meta key 'price'
													array(
														'key'		=>	'color',
														'value'		=>	'red',
														'type'		=>	'CHAR',
														'compare'	=>	'='
													)
												),

												// Short by size meta key 'price'
													array(
														'key'		=>	'price',
														'value'		=>	'100',
														'type'		=>	'NUMERIC',
														'compare'	=>	'>'
													)							

												// Short by size meta key 'price' using 'BETWEEN' key
												/*array(
													'key'		=>	'price',
													'value'		=>	array(30, 450),
													'type'		=>	'NURERIC',
													'compare'	=>	'BETWEEN'
												)*/
						)
				);

				$query = new WP_Query($arg);

			while($query->have_posts()) : $query->the_post(); ?>
				<h4><?php the_title(); ?></h4>
				<p>Price : <?php the_field('price'); ?></p>
				<p>Color : <?php the_field('color')?></p>
				<p>Size : <?php the_field('size'); ?></p>
				<p><?php the_tags(); ?></p>	
			<?php endwhile; wp_reset_query(); ?>  

			<hr>

			<h2>WordPress Custom Query - Part 06 - tax_query</h2>
			






			






</main><!-- #site-content -->


<?php get_footer(); ?>
