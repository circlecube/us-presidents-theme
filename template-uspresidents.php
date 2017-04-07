<?php
/**
 * Template Name: US Presidents
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * e.g., it puts together the home page when no home.php file exists.
 *
 * Learn more: {@link https://codex.wordpress.org/Template_Hierarchy}
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */
wp_head();
// get_header(); 

function get_ordinal($number){
	
	$ends = array('th','st','nd','rd','th','th','th','th','th','th');
	if (($number %100) >= 11 && ($number%100) <= 13) {
	   $ordinal = $number . 'th';
	}
	else {
	   $ordinal = $number . $ends[$number % 10];
	}
	
	return $ordinal;
}
?>
				
		<?php if ( have_posts() ) : ?>
 	
			<style>
				body:before{
					content: none;
				}
			</style>
			<?php
			// echo 'player roster';
			echo '<pre>var presidents = [
';
			
			$president_args = array(
				'post_type' => 'president',
				'posts_per_page' => -1,
				'meta_key' => 'number',
				'orderby' => 'meta_value_num',
				'order' => 'ASC'
			);
			$president_query = new WP_Query( $president_args );
			// The Loop
			if ( $president_query->have_posts() ) {
				while ( $president_query->have_posts() ) {
					$president_query->the_post();
										
					//IMG
					$imgs = get_field('portrait');
					$img = $imgs[0][url];
					
					//ordinal
					$number = get_field('number');
					if ( strstr($number, '.') ) {
						$number = explode('.', $number);
					}
					if (is_array($number)){
						$ord = [];
						foreach( $number as $num ){
							array_push( $ord, get_ordinal( $num ) );	
						}
						$ordinal = implode (' & ', $ord );
					}
					else {
						$ordinal = get_ordinal( $number );
					}
					if ( is_array($number) ){
						$number = implode (' & ', $number );
					}
					
					//terms
					$terms = [];
					if( have_rows('terms') ) {
						while ( have_rows('terms') ) {
							the_row();
							array_push($terms, get_sub_field('term_number'));
						}
					}
					$term = implode(',', $terms);
					
?>
	{
		
		'id': '<?php echo $number; ?>',
		'ordinal': '<?php echo $ordinal; ?>', 
		'name': '<?php the_title(); ?>',
		'first_name': '<?php the_field('first_name'); ?>',
		'last_name': '<?php the_field('last_name'); ?>',
		'photo': '<?php echo $img; ?>',
		'birth': '<?php the_field('birthdate'); ?>',
		'birthplace': '<?php the_field('birthplace'); ?>',
		'death': '<?php the_field('death_date'); ?>',
		'tookoffice': '<?php the_field('took_office'); ?>',
		'leftoffice': '<?php the_field('left_office'); ?>',
		'terms': '<?php echo $term; ?>', 
		'party': '<?php the_field('party'); ?>', 
		'previous': '<?php the_field('previous_office'); ?>', 
		'vp': '<?php the_field('vice_president'); ?>',

	},
<?php
					
		    	} // end while 
			}// end loop if
				
			wp_reset_query();
			echo '];</pre>';
			?>

			<?php
			// Start the loop.
			while ( have_posts() ) : the_post();

				/*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */
				//get_template_part( 'content', get_post_format() );

			// End the loop.
			endwhile;


		endif;
		?>

		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php 
// get_footer(); 
wp_footer(); 
?>
