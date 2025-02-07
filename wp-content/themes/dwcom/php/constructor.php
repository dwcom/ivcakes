<?php
	$term = get_queried_object();
	
	while (have_rows( 'constructor', $term )){
		the_row();
		include get_template_directory() . "/php/include/" . get_row_layout() . ".php";
	}