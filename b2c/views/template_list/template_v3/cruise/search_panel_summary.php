<style>

	.topssec::after{display:none;}

	

</style>

<div class="modfictions for_hotel_modi layout_modification">

	<div class="modinew">

		<div class="container">

		<div class="contentsdw">

		<div class="col-lg-8 col-sm-8 col-xs-6 hidden-xs nopad">

			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 nopad">

				<div class="pad_ten">

					<div class="boxlabl">

						Cruise Destination

					</div>

					<div class="from_to_place">

						<div class="namefromto set_fromloc"> Destination Name <span class="set_dots hide"></span></div>

					</div>

				</div>

			</div>



			



			</div>



			<div class="col-lg-4 col-sm-4 col-xs-4 nopad">

				<div class="pad_ten">

					<button class="modifysrch btn" data-toggle="collapse" data-target="#modify">Modify</button>

				</div>

			</div>

			

				

				

			</div>

			

			

		</div>

	</div>

</div>



	<div class="modify_search_wrap splmodify">

		<div class="container" style="position: relative;">

		 <div class="">

			<div id="modify" class="collapse araeinner">

				<div class="insplarea">

					<?php echo $GLOBALS['CI']->template->isolated_view('cruise/cruise_search') ?>

				</div>

			</div>

		  </div>	

		</div>

	</div>

	</div>



<script type="text/javascript">

$(document).ready(function(){

	$('.modifysrch').click(function(){

		$(this).stop( true, true ).toggleClass('up');

		$('.search-result').stop( true, true ).toggleClass('flightresltpage');

		$('.modfictions').stop( true, true ).toggleClass('fixd');

	});



});

</script>