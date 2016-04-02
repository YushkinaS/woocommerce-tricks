function simple_to_variative($post_id,$bus_price) {
	$variations = get_children( array( 
		'post_parent' => $post_id,
		'post_type'   => 'product_variation', 
		'numberposts' => -1,
		'post_status' => 'any') );
    
  	if ($bus_price==0) {
  		//make product simple and delete variations
        	wp_set_post_terms( $post_id, 'simple','product_type' );
        	foreach ($variations as $variation) {
            		wp_delete_post( $variation->ID, true );
        	}
	 }
	else {
  		//set product attribute  
		$attributes  = array(
			"pa_bus" => array(
				"name" => "pa_bus",
				"value" => "",
				"position" => "0",
				"is_visible" => 1,
				"is_variation" => 1,
				"is_taxonomy" => 1
				)
		);
		update_post_meta( $post_id, '_product_attributes', $attributes );
		
		//set attribute values
		wp_set_post_terms( $post_id, array(25,26),'pa_bus' ); //сделать потом term_exists($term, $taxonomy, $parent );
		
		//make product variable
		wp_set_post_terms( $post_id, 'variable','product_type' );
		
		//get product price
		$price = get_post_meta($post_id,"_price",True);
		
		//create 2 variations
		
		//Variation 1
		$my_post = array(
			  'post_title'    => 'Variation #' . 1 . ' of ' .  $post_id,
			  'post_name'     => 'product-' . $post_id . '-variation-' . 1,
			  'post_status'   => 'publish',
			  'post_parent'   => $post_id,
			  'post_type'     => 'product_variation',
			  'guid'          =>  home_url() . '/?product_variation=product-' . $post_id . '-variation-' . 1
			);
        
		if (count($variations) < 2 ) {
		    $id = wp_insert_post( $my_post,true );
	
		}
		else {
			$id = $variations[0]->ID;
			$my_post["ID"] = $id;
			wp_update_post( $my_post );
		}
       
		update_post_meta( $id, 'attribute_pa_bus', 'need');
		update_post_meta( $id, '_price', $price+$bus_price );
		update_post_meta( $id, '_regular_price',  $price+$bus_price);

		//Variation 2
		$my_post = array(
			  'post_title'    => 'Variation #' . 2 . ' of ' .  $post_id,
			  'post_name'     => 'product-' . $post_id . '-variation-' . 2,
			  'post_status'   => 'publish',
			  'post_parent'   => $post_id,
			  'post_type'     => 'product_variation',
			  'guid'          =>  home_url() . '/?product_variation=product-' . $post_id . '-variation-' . 2
			);

		if (count($variations) < 2) {
		    $id = wp_insert_post( $my_post );
		}
		else {
			$id = $variations[1]->ID;
			$my_post["ID"] = $id;
			wp_update_post( $my_post );
		}
		
		update_post_meta( $id, 'attribute_pa_bus', 'not-need');
		update_post_meta( $id, '_price', $price );
		update_post_meta( $id, '_regular_price', $price);
		update_post_meta( $id, '_stock_status', 'instock');

	}
}
