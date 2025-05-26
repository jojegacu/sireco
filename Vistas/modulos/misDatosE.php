<div class="content-wrapper">
	<section class="content">
		<div class="box-body">

			<?php

				$datos = new misDatosControlador();
				$datos -> vermisDatosEControlador();

			?>

			<?php

				$guardar = new misDatosControlador();
				$guardar -> guardarDatosEControlador();

			?>


		</div>

		
	</section>
	
</div>