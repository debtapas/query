<?php
/**
 *	Template Name: Custom Search

	Turotial : https://www.youtube.com/watch?v=CU0hWpu_eHo
	Ref :	 https://www.billerickson.net/code/wp_query-arguments/

 */

get_header(); ?>

<?php

	if($_GET['search_text'] && !empty($_GET['search_text'])){

		$text = $_GET['search_text'];

	}

	if($_GET['type'] && !empty($_GET['type'])){

		$type = $_GET['type'];
	}

?>

<main id="site-content" role="main">

<h4 class="title" >Searching for : <span> <?php echo $text; ?></span></h4>

<?php
	$args = array(
		'post_type' => $type,
		'post_per_page' => -1,
		's' => $text // s mean search
		//'exact' => true, // for exact search case
		//'sebtence' => true
	);
	$query = new WP_Query($args);

	while($query->have_posts()) : $query->the_post();
?>

	<div class="section-inner">		

		<h5><?php the_title();?></h5>
		<strong>
			<?php if(get_post_type() == 'post') { echo 'Post'; } ?>
			<?php if(get_post_type() == 'movie') { echo 'Movies'; } ?>
			<?php if(get_post_type() == 'book') { echo 'Books'; } ?>
		</strong>

	</div>

<?php endwhile; wp_reset_query(); ?>

</main>

<?php get_footer(); ?>
