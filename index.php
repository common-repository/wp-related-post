<?php
/*
Plugin Name: WP Related Post
Plugin URI: http://www.webingnet.com
Description: WP Related Post is a very simple plugin that can organized your related posts after a single post by Category.
Version: 1.0
Author: Pantho Bihosh
Author URI: http://www.bihosh.com
*/


add_filter('the_content', function($content) {
	$id = get_the_id();
	if ( !is_singular('post')){
		return $content;
	}
	
	$terms = get_the_terms( $id, 'category');
	$cats = array();
	
	foreach( $terms as $term){
		$cats[] = $term->cat_ID;
	}
	
	$loop = new WP_Query(
		array(
			'posts_per_page' => 5,
			'category__in' => $cats,
			'orderby' => 'rand',
			'post__not_in' => array($id)
		)
	);
	
	if ( $loop->have_posts() ) {
		$content .='
		<h2>Related Posts</h2>
		<ul class="related-category-posts">';
		
	while( $loop->have_posts() ) {
		$loop->the_post();
		
		$content .= '
		<li>
			<a href="' .get_permalink() .'">' . get_the_title() . '</a>
		</li>';
	}
	$content .= '</ul>';
	wp_reset_query();
	}
	
	return $content;
});

?>