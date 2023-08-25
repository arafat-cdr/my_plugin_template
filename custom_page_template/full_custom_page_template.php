<?php
	// Write Require Php Codes Here
	
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php  wp_head(); ?>
</head>
<body>
	<div class="row">
		<?php 

			// custom post codes here
			if($_SERVER['REQUEST_METHOD'] === 'POST'):
		?>

		<a href="<?php echo $current_url; ?>">
			<img src="<?php echo WL_RT_URL.'templates/'; ?>card_icon/back.png" alt="back icon" width='50px' height='50px' class='rt_bk_img'>
		</a>

		<?php 
			endif; 
			// end custom post codes
		?>
	</div>

	<div class="row">
		<h3>Hello wrold form custom template</h3>
	</div>


<?php get_footer(); ?>
</body>
</html>
