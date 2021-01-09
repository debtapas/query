<?php
/**
 * Template Name: Post Table

 */


get_header(); ?>



<main id="site-content" role="main">

	<div class="section-inner">
		<h4>Display data from Database</h4>
			<table id="example" class="display" style="width:100%">
	        <thead>
	            <tr>
	                <th>Post ID</th>
	                <th>Author</th>
	                <th>Post Date</th>
	                <th>Post Title</th>
	                <th>Post Content</th>
	            </tr>
	        </thead>
	        <tbody>
	        	<?php
		        	global $wpdb;
		        	$all_posts = $wpdb->get_results("SELECT * from wp_posts");

		        	foreach ($all_posts as $print) { ?>		        		
		        		<tr>
							<td><?php echo ($print->ID);?></td>
							<td><?php echo ($print->post_author);?></td>
							<td><?php echo ($print->post_date);?></td>
							<td><?php echo ($print->post_title);?></td>
							<td><?php echo ($print->post_content);?></td>
						</tr>	
		        <?php } ?>

	        </tbody>
	        <tfoot>
	            <tr>
	                <th>Name</th>
	                <th>Position</th>
	                <th>Office</th>
	                <th>Age</th>
	                <th>Start date</th>
	                <th>Salary</th>
	            </tr>
	        </tfoot>
	    </table>

	</div>
</main><!-- #site-content -->


<?php get_footer(); ?>
