        <footer class="footer blur-content">

            <div class="footer__content container">
                <div class="row">

                    <?php
                    $footer_content_left = get_field('footer__block-first');
                    if ($footer_content_left):
                        $footer_content_left_title = $footer_content_left['footer__block-first-title'];
                        $footer_content_left_html = $footer_content_left['footer__block-first-html'];
                    ?>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" data-aos="fade-up">
                        <?php
                        if ($footer_content_left_title){
                            echo '<h2 class="footer__content-title">'.$footer_content_left_title.'</h2>';
                        }
                        if ($footer_content_left_html){
                            echo '<div class="footer__content-html">'.$footer_content_left_html.'</div>';
                        }
                        ?>
                    </div>
                    <?php endif; ?>

                    <?php
                    $footer_content_right = get_field('footer__block-second');
                    if ($footer_content_right):
                        $footer_content_right_title = $footer_content_right['footer__block-second-title'];
                        $footer_content_right_html = $footer_content_right['footer__block-second-html'];
                        ?>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" data-aos="fade-up">
                            <?php
                            if ($footer_content_right_title){
                                echo '<h2 class="footer__content-title">'.$footer_content_right_title.'</h2>';
                            }
                            if ($footer_content_right_html){
                                echo '<div class="footer__content-html">'.$footer_content_right_html.'</div>';
                            }
                            ?>
                        </div>
                    <?php endif; ?>

                </div>
                <div class="footer__logo"></div>
            </div>

            <div class="footer__dark-row">
                <div class="container">
                    <div class="row middle-lg">
                        <div class="footer__lang col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <?php
                            if (function_exists ('wpm_language_switcher')){
                                echo '<div class="footer__lang-title">'.__('See_on_language', get_textdomain()).'</div>';
                                wpm_language_switcher ('list', 'name');
                            }
                            ?>
                        </div>
                        <div class="footer__copyr col-lg-8 col-md-8 col-sm-8 col-xs-12">
                            Copyright &copy; <?php echo date('Y').' '; ?>
                            <?php _e('All_rights_reserved', get_textdomain()); ?>
                            <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                                <?php echo $_SERVER['HTTP_HOST']; ?>
                            </a>
                            <br>
                            <?php _e('Unauthorized_copying', get_textdomain()); ?>
                        </div>
                    </div>
                </div>
            </div>

            <a href="#home" class="footer__to-home"></a>

        </footer>


        <?php get_template_part('template_parts/forms/popup-form'); ?>

        <div class="fade-screen"></div>

        <?php wp_footer(); ?>

    </body>
</html>