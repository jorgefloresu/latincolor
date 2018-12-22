	<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog" aria-labelledby="myLargeModalLabel">
  	<div class="modal-dialog modal-lg">

    <!-- Modal content-->
    	<div class="modal-content">
      		<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal">&times;</button>
        		<h4 class="modal-title">Modal Header</h4>
        		<p>Text caption</p>
      		</div>
      		<div class="modal-body">
				<div class="row">
					<div class="col-lg-6 text-center">
						<div class="loading-indicator"><img src="<?php echo base_url('images/ajax-loader.gif'); ?>" /></div> 
						<img id="mod-imagepreview" src="http://" />
					</div>
					<div class="caption col-lg-6 text-left">
						<dl class="dl-horizontal">
							<dt class="fix-dt-width">Color Type:</dt>
							<dd id="mod-colortype" class="fix-dd-margin">Color type</dd>
							<dt class="fix-dt-width">Orientation:</dt>
							<dd id="mod-orientation" class="fix-dd-margin">Orientation</dd>
							<dt class="fix-dt-width">Keywords:</dt>
							<dd id="mod-keywords" class="fix-dd-margin"><small>Loading...</small></dd>
						</dl>
					</div>
				</div>
    		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      		</div>
    	</div>
  	</div>
</div>
					