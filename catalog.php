<nav class="menu-catalog<?php
	if ( is_front_page() ) {
		echo ' menu-catalog_expanded';
	} else {
		echo ' menu-catalog_inner-pages';
	}
	?>">
    <div class="menu-catalog__button">
        <div class="brand-logo-sm"></div>
		<?php _e( 'Shop by Category', get_textdomain() ); ?>
    </div>
    <ul class="menu-catalog__list menu-catalog__list_base">

		<?php

		$li_class = 'menu-catalog__list-item';

		$default_args = array(
			'taxonomy'     => 'product_cat',
			'orderby'      => 'name',
			'show_count'   => 0,
			'pad_counts'   => 0,
			'hierarchical' => 1,
			'hide_empty'   => 0,
			'title_li'     => '',
		);

		$all_categories = get_categories( array_merge( $default_args, array(
			'exclude' => 15
		) ) );

		function has_child_categories( $default_args, $cat_id ) {
			$child_categories = get_categories( array_merge( $default_args, array(
				'child_of' => $cat_id
			) ) );
			if ( $child_categories ) {
				return true;
			} else {
				return false;
			}
		}

		function catalog_list_item( $default_args, $current_cat, $li_class ) {

			$li_class_list  = $li_class;
			$current_cat_id = $current_cat->term_id;
			$image          = wp_get_attachment_url( get_woocommerce_term_meta( $current_cat_id, 'thumbnail_id', true ) );

			if ( has_child_categories( $default_args, $current_cat_id ) ) {
				$li_class_list .= ' ' . $li_class . '_has-list';
			}

			if ( $image ) {
				$li_class_list .= ' ' . $li_class . '_has-image';
			}

			echo '<li class="' . $li_class_list . '"><div class="menu-catalog__list-item-wrapper"><a href="' . get_category_link( $current_cat_id ) . '">';

			if ( $image ) {
				echo '<div class="menu-catalog__list-item-image" style="background-image: url(' . $image . ');"></div>';
			}

			echo $current_cat->cat_name . '</a></div>';

			if ( !has_child_categories( $default_args, $current_cat_id ) ) {
				echo "</li>"; // Hasn't Categories
            }

		}

		function get_child_categories( $default_args, $current_cat, $li_class ) {
			$sub_cats = get_categories( array_merge( $default_args, array(
				'child_of' => 0,
				'parent'   => $current_cat->term_id
			) ) );
			if ( $sub_cats ) {
				echo "<ul class='menu-catalog__list'>";
				foreach ( $sub_cats as $sub_category ) {
					if ( $sub_category ) {
						catalog_list_item( $default_args, $sub_category, $li_class );
						get_child_categories( $default_args, $sub_category, $li_class );
					}
				}
				echo "</ul>"; // Close Sublist
				echo "</li>"; // Close List with Sublist
			}
		}


		// FIRST CATALOG LEVEL

		foreach ( $all_categories as $cat ) {
			if ( $cat->category_parent == 0 ) {
				catalog_list_item( $default_args, $cat, $li_class );
				get_child_categories( $default_args, $cat, $li_class );
			}
		}
		?>

    </ul>
</nav>