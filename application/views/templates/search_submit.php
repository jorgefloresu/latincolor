<!-- Search field and submit button -->

<div class="input-group">
	<input type="text" class="form-control" id="keywords" 
		name="keywords" placeholder="Enter the keywords" value="<?php echo $keywords; ?>" />
	<span class="input-group-btn">
		<button class="btn btn-primary fix-font" type="submit" name="submit">Buscar</button>
	</span>
	<input type="hidden" name="next" value="0" />
</div>
