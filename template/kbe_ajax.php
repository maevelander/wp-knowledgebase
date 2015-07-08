<?php
/* 
 *	This class hooks up AJAX functions to the WordPress ecosystem
 *
 */
Class KBE_AJAX{
	
	public function __construct(){
		
		add_action( 'wp_head', array( $this, 'output_script_variables' ), 0 );
		add_action( 'wp_ajax_nopriv_kbe_livesearch', array( $this, 'kbe_livesearch' ) );
		add_action( 'wp_ajax_kbe_livesearch', array( $this, 'kbe_livesearch' ) );
		
	}
	
	public function output_script_variables(){
		
		echo '<script>var kbe_var = {	ajaxurl : "'.admin_url('admin-ajax.php').'"}</script>';
		
	}
	
	public function kbe_livesearch(){
		
		// Gets the KBE information
		
		wp_send_json_success(array(
			'result'	=>	$this->search_query( $_POST['query'] )
		));
		
	}
	
	//	This does the actual search query
	//	Note: The function uses ob_start and ob_get_clean to return the output data from buffer
	public function search_query( $query ){
		
		$args = array(
			'post_type' => 'kbe_knowledgebase',
			'post_status' => 'publish',
			's' => $query
		);
		$search = new WP_Query( $args );
		
		ob_start();
		
		if ( $search->have_posts() ) {
			echo '<ul id="search-result">';
			while ($search->have_posts()) : $search->the_post();
				echo '<li><a href="'.get_the_permalink().'">'.get_the_title().'</a></li>';
			endwhile;
			echo '</ul>';
		}else{
			echo '<span class="kbe_no_result">Search result not found......</span>';
		}
		$content = ob_get_clean();
		return $content;
		
	}
	
}
new KBE_AJAX;