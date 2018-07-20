<?php if ( have_rows('packs') ): $packs = get_field('packs'); ?>
<section id="packs" class="packs anchor content-block content-block_brand">

    <div class="content-block__animation" data-aos="fade"></div>

    <div class="container">

        <div class="row">
            <div class="col-xs">
                <h2 class="content-block__title" data-aos="fade-up"><?php the_field('packs__block-title'); ?></h2>
            </div>
        </div>

	    <?php

	    function the_pack_list($pack){
		    if ($pack_list = $pack['packs__list']):
			    echo '<ul class="packs__item-list">';
			    foreach($pack_list as $pack_list_item){
				    echo '<li class="packs__item-list-el">'.$pack_list_item['packs__list-descr'];
				    if ($pack_list_item['packs__list-icon']){
					    echo '<div class="packs__item-list-el-ico" style="background-image: url('.$pack_list_item['packs__list-icon'].');"></div>';
				    }
				    echo '</li>';
			    }
			    echo '</ul>';
		    endif;
	    }

	    function the_packs_info($pack){
		    if ($pack_info = $pack['packs__info']):
			    echo '<p class="packs__item-info">'.$pack_info.'</p>';
            endif;
        }

        $pack1 = $packs[0]; if ($pack1):

        ?>
        <div class="packs__container row">
            <div class="col-xs">
                <div class="packs__item packs__item_first container-fluid" data-aos="zoom-in-left" data-aos-offset="200">
                    <div class="row">
                        <div class="packs__item-col col-lg-4 col-md-4 col-sm-12 col-xs-12">

                            <div class="packs__item-wrapper">
                                <h2 class="packs__item-title"><?php echo $pack1['packs__title']; ?></h2>
                                <?php the_pack_list($pack1); ?>
                                <?php the_packs_info($pack1); ?>
                                <button class="packs__item-button open-popup-form button button_center button_lg button_green"
                                        data-message-subject="<?php
                                            echo __('Form_packs_subject', get_textdomain()).strip_tags($pack1['packs__title']);
                                        ?>">
                                    <?php _e('Order', get_textdomain()); ?>
                                </button>
                            </div>

                        </div>
	                    <?php $pack2 = $packs[1]; if ($pack2): ?>
                        <div class="packs__item-col col-lg-8 col-md-8 col-sm-12 col-xs-12">
                            <div class="packs__item packs__item_second container-fluid" data-aos="zoom-in-left" data-aos-offset="184" data-aos-delay="200">
                                <div class="row">
                                    <div class="packs__item-col col-lg-6 col-md-6 col-sm-12 col-xs-12">

                                        <div class="packs__item-wrapper">
                                            <h2 class="packs__item-title"><?php echo $pack2['packs__title']; ?></h2>
	                                        <?php the_pack_list($pack2); ?>
	                                        <?php the_packs_info($pack2); ?>
                                            <button class="packs__item-button open-popup-form button button_center button_lg button_orange"
                                                    data-message-subject="<?php
                                                        echo __('Form_packs_subject', get_textdomain()).strip_tags($pack2['packs__title']);
                                                    ?>">
                                                <?php _e('Order', get_textdomain()); ?>
                                            </button>
                                        </div>

                                    </div>
		                            <?php $pack3 = $packs[2]; if ($pack3): ?>
                                    <div class="packs__item-col col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <div class="packs__item packs__item_third container-fluid" data-aos="zoom-in-left" data-aos-offset="168" data-aos-delay="400">
                                            <div class="row">
                                                <div class="packs__item-col col-xs-12">

                                                    <div class="packs__item-wrapper">
                                                        <h2 class="packs__item-title"><?php echo $pack3['packs__title']; ?></h2>
	                                                    <?php the_pack_list($pack3); ?>
	                                                    <?php the_packs_info($pack3); ?>
                                                        <button class="packs__item-button open-popup-form button button_center button_lg button_gray"
                                                                data-message-subject="<?php
                                                                    echo __('Form_packs_subject', get_textdomain()).strip_tags($pack3['packs__title']);
                                                                ?>">
                                                            <?php _e('Order', get_textdomain()); ?>
                                                        </button>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
		                <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
	    <?php endif; ?>

    </div>

</section>

<?php get_template_part('template_parts/forms/form-packs_popup'); ?>

<?php endif; ?>