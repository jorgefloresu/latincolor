<?php foreach($result as $images): ?> 
	<?php if( !empty($images['thumburl']) ): ?>
		<div class="col-lg-2 fix-thumb-height">
			<div class="text-center">
				<a href="#" class="img-thumbnail" rel="popover" title="<?php echo $images['code']; ?>" 
					data-img="<?php echo $images['caption']; ?>">
					<img src="<?php echo $images['thumburl']; ?>" >
				</a>			
				<div class="caption">
					<div class="row">
						<div class="col-lg-2">
							<small>
								<?php echo $images['provider'].':'.$images['code']; ?>
							</small>
						</div>
						<div class="col-lg-10 text-right">
							<a href="#"
								class="btn btn-default btn-xs" 
								role="button" data-toggle="modal" data-target="#myModal" 									
								data-url="<?php echo site_url($search_source.'/imgdetail').'/'.
											$images['provider'].'/'. $images['code']; ?>"
								data-img="<?php echo $images['code']; ?>"
								data-caption="<?php echo $images['caption']; ?>">
								<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>
<?php endforeach ?>
