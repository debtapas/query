<?php
/**
 *	Template Name: Filter

	Turotial : https://www.youtube.com/watch?v=UBhzNpQ6SqQ
	Ref :	 https://www.billerickson.net/code/wp_query-arguments/

 */

get_header(); ?>

<?php
	if ($_GET['minprice'] && !empty($_GET['minprice'])) {
		$minprice = $_GET['minprice'];
	}else{
		$minprice = 0;
	}

	if ($_GET['maxprice'] && !empty($_GET['maxprice'])) {
		$maxprice = $_GET['maxprice'];
	}else{
		$maxprice = 9999999999;
	}

	if ($_GET['size'] && !empty($GET_['size'])) {
		$size = $_GET['size'];
	}

	if($_GET['color'] && !empty($_GET['color'])){
		$color = $_GET['color'];
	}
?>


<main id="site-content" role="main">	

	<div class="section-inner">

		<div class="block">

			<h2>WordPress Custom Query - Part 07 - Custom Filters</h2>
			
	        <form action="" method="get">
	        	<div class="the_field">
	        		<label>min:</label>
	            	<input type="number" name="minprice" value="<?php echo $minprice; ?>">
	        	</div>
	            
	            <div class="the_field">
	            	<label>max:</label>
	            	<input type="number" name="maxprice" value="<?php echo $maxprice; ?>">
	            </div>
	            
	            <div class="the_field">
	            	<label>Size:</label>
		            <select name="size" value="<?php echo $size; ?>" >
		                <option value="">Any</option>
		                <option value="s">S</option>
		                <option value="m">M</option>
		                <option value="l">L</option>
		                <option value="xl">XL</option>               
		            </select>
	            </div>
	            

	            <div class="the_field">
	            	<label>Color:</label>
		            <select name="color" value="<?php echo $color; ?>" >
		                <option value="">Any</option>
		                <option value="red">Red</option>
		                <option value="blue">Blue</option>
		                <option value="green">Green</option>
		                <option value="yellow">Yellow</option>
		                <option value="purple">Purple</option>
		                <option value="black">Black</option>                      
		            </select>
	            </div>

	           <div class="the_field">
	           		<button type="submit" name="">Filter</button>
	           </div>
	            
	        </form>

        </div>
    </div>


	
        	
        <div class="section-inner">

	        <?php
	        	$args = array(
	        		'post_type' 	=>	'post',
	        		'post_per_page'	=>	-1,
	        		'meta_query'	=>	array(
	        			array(
	        				'key'		=>	'price',
	        				'type'		=>	'NUMERIC',
	        				'value'		=>	array($minprice, $maxprice),
	        				'compare'	=>	'BETWEEN'
	        				),
	        			array(
	        				'key' 		=>	'color',
	        				'value'		=>	$color,
	        				'compare'	=>	'LIKE'
	        			),
	        			array(
	        				'key'		=> 'size',
	        				'type'		=>	$size,
	        				'compare'	=>	'LIKE'
	        			)
	        		)

	        	);

	        $query = new WP_Query($args);

	        while( $query->have_posts() ) : $query->the_post(); ?>

	        	<h5><?php the_title(); ?></h5>
	        	<div class="tags">
	        		<strong>Price :</strong> <?php the_field('price'); ?><br>
		            <strong>Color:</strong> <?php the_field('color'); ?><br>
		            <strong>Size:</strong> <?php the_field('size'); ?>
		        </div>

	        <?php endwhile; wp_reset_query(); ?>	   
		
    	</div>



</main><!-- #site-content -->


<?php get_footer(); ?>