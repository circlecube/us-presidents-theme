<?php
/**
 * Template Name: Presidents Table
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * e.g., it puts together the home page when no home.php file exists.
 *
 * Learn more: {@link https://codex.wordpress.org/Template_Hierarchy}
 *
  * @package Presidents
  */

get_header();

$leader_args = array(
	'post_type' => 'president',
	'posts_per_page' => -1,
	'orderby' => 'meta_value',
	'meta_key' => 'took_office'
);

$leader_query = new WP_Query( $leader_args );

// The Loop
if ( $leader_query->have_posts() ) {
	
	$leader_table = "<table>
		<thead>
			<tr>
				<th>Img</th>
				<th>Name</th>
				<th>Birthdate</th>
				<th>Took Office</th>
				<th>Left Office</th>
				<th>Death</th>
			</tr>
		</thead>
		<tbody>";
	
	while ( $leader_query->have_posts() ) {
		$leader_query->the_post();
		
		//STATUS
		// $leader_group_terms = get_the_terms( get_the_ID(), 'types');
		// $leader_group = [];
		// foreach ( $leader_group_terms as $term ) {
		// 	$leader_group[] = $term->name;
		// }
		// $groups = implode(",", $leader_group);

		//get first portrait thumbnail url
		$portrait = get_field('portrait');
		$portrait_url = $portrait[0]['sizes']['thumbnail'];


		$birthday_str = '';
		$took_office_str = '';
		$left_office_str = '';
		$death_date_str = '';

		if ( get_field('birthdate') > 0 ) {
			$birthday = DateTime::createFromFormat('Ymd', get_field('birthdate'));
			$birthday_str = $birthday->format('M d, Y');
		}
		if ( get_field('took_office') > 0 ) {
			$took_office = DateTime::createFromFormat('Ymd', get_field('took_office'));
			$took_office_str = $took_office->format('M d, Y');
		}
		if ( get_field('left_office') > 0 ) {
			$left_office = DateTime::createFromFormat('Ymd', get_field('left_office'));
			$left_office_str = $left_office->format('M d, Y');
		}
		
		if ( get_field('death_date') > 0 ) {
			$death_date = DateTime::createFromFormat('Ymd', get_field('death_date'));
			$death_date_str = $death_date->format('M d, Y');
		}


		$leader_table .= "<tr>
			<td><img src='" . $portrait_url . "'/></td>
			<td><a href='" . get_the_permalink() . "'>" . get_the_title() . "</a></td>
			<td data-sort-value='" . get_field('birthdate') . "'>" . $birthday_str . "</td>
			<td data-sort-value='" . get_field('took_office') . "'>" . $took_office_str . "</td>
			<td data-sort-value='" . get_field('left_office') . "'>" . $left_office_str . "</td>
			<td data-sort-value='" . get_field('death_date') . "'>" . $death_date_str . "</td>
		</tr>";
		
	} // end while 
}// end loop if
	
wp_reset_query();

$leader_table .= "</tbody>
</table>";

echo $leader_table;

?>


<?php get_footer(); ?>
