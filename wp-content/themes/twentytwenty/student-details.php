<?php
/**
 Template Name: Student details form
 */
get_header();?>

<main id="site-content" role="main">

	<div class="section-inner">

		<form method="post">
			<input placeholder="Student's ID" type="text" class="form-control" name="id">
			<input placeholder="Student's Name" type="text" class="form-control" name="student">
			<input placeholder="Student's Roll number" type="text" class="form-control" name="rol_num">
			<input placeholder="Pass Mark" type="text" class="form-control" name="pass_mark">
			<input placeholder="Get Number" type="text" class="form-control" name="get_mark">
			<textarea name="remarks"></textarea>

			<button type="submit" name="submit">Submit</button>

		</form>
	</div>
</main>


<?php get_footer(); ?>