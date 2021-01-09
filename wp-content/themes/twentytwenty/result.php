<?php
/**
 Template Name: Student results
 */
get_header();?>

<main id="site-content" role="main">

	<div class="section-inner">

<table id="example" class="display" style="width:100%">
	        <thead>
	            <tr>
	                <th>Post ID</th>
	                <th>Author</th>
	                <th>Post Date</th>
	                <th>Post Title</th>
	                <th>Post Content</th>
	                <th>Action</th>
	            </tr>
	        </thead>
	        <tbody>
	        	<?php
		        	global $wpdb;
		        	$all_posts = $wpdb->get_results("SELECT * from result");

		        	foreach ($all_posts as $print) { ?>		        		
		        		<tr>
							<td><?php echo ($print->id);?></td>
							<td><?php echo ($print->student);?></td>
							<td><?php echo ($print->rol_num);?></td>
							<td><?php echo ($print->pass_mark);?></td>
							<td><?php echo ($print->get_mark);?></td>
							<td>
								<button name="update" value="Update">Update</button>
								<button name="delete" value="Delete">Delete</button>
							</td>
						</tr>
		        <?php } ?>

	        </tbody>
	    </table>
	</div>
</main>

<?php get_footer(); ?>