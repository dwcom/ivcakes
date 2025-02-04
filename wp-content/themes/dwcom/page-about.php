<?php get_header() ?>

<?php include get_template_directory() . "/php/include/blocks/breadcrumb.php"; ?>


<article class="about">
    <div class="about__wrap">
        <div class="container">
            
            <div class="about__block">
	            
	            <?php
		            while (have_rows('repeater')) : the_row();
			            $title = get_sub_field('title');
			            $subtitle = get_sub_field('subtitle');
			            $image = get_sub_field('image');
			            ?>
                        <div class="about__item item grid">
                            <div class="item__left">
                                <div class="item__image">
                                    <img src="<?php echo $image ?>" alt="<?php echo $title ?>">
                                </div>
                            </div>
                            <div class="item__right">
                                <div class="item__content fs20">
                                    <div class="item__editor editor"><?php echo $subtitle ?></div>
                                </div>
                            </div>
                        </div>
		            <?php endwhile ?>
              
            </div>
            
        </div>
    </div>
    
</article>

<?php include get_template_directory() . "/php/include/blocks/feedback.php"; ?>
<?php include get_template_directory() . "/php/include/blocks/image.php"; ?>
<?php include get_template_directory() . "/php/include/blocks/benefit.php"; ?>
<?php include get_template_directory() . "/php/include/blocks/contact.php"; ?>

<?php get_footer() ?>

