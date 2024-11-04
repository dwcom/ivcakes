<?php
	$benefits = get_field('benefit_list', 'option');
?>

<!-- Footer = Benefits -->
<section class="benefit">
	<div class="benefit__wrap">
		<div class="container">
			<div class="benefit__block">
				<div class="benefit__list">
					<?php foreach ( $benefits as $item ) : ?>
						<div class="item">
							<div class="item__content">
								<div class="item__image">
									<img src="<?php echo $item['image'] ?>" alt="<?php echo $item['title'] ?>">
								</div>
								<div class="item__group color-green">
									<div class="item__title fs32 weight-700"><?php echo $item['title'] ?></div>
									<div class="item__subtitle fs20"><?php echo $item['subtitle'] ?></div>
								</div>
							</div>
						</div>
					<?php endforeach ?>
				</div>
			</div>
		</div>
	</div>
</section>