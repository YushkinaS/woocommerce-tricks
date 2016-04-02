# woocommerce-tricks

# Программно создать вариативный товар:
simple-to-variable.php

Чтобы сделать простой товар вариативным, нужно:
* Сменить тип товара на вариативный
```php
	wp_set_post_terms( $post_id, 'variable','product_type' );
```
* Задать принадлежность товару атрибута, по которому будем создавать вариации. У меня это атрибут pa_bus
```php
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
```
* Задать доступные значения атрибута
```php
	wp_set_post_terms( $post_id, array(25,26),'pa_bus' ); //сделать потом term_exists($term, $taxonomy, $parent );
```
* Для каждого значения атрибута создать вариацию
```php
	$my_post = array(
			  'post_title'    => 'Variation #' . 1 . ' of ' .  $post_id,
			  'post_name'     => 'product-' . $post_id . '-variation-' . 1,
			  'post_status'   => 'publish',
			  'post_parent'   => $post_id,
			  'post_type'     => 'product_variation',
			  'guid'          =>  home_url() . '/?product_variation=product-' . $post_id . '-variation-' . 1
			);
        
	$id = wp_insert_post( $my_post,true );

		update_post_meta( $id, 'attribute_pa_bus', 'need');
		update_post_meta( $id, '_price', $price+$bus_price );
		update_post_meta( $id, '_regular_price',  $price+$bus_price);
```		
