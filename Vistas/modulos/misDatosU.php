<div class="content-wrapper">
	<section class="content">
		<div class="box-body">

			<?php

				$datos = new misDatosControlador();
				$datos -> vermisDatosUControlador();

			?>

			<?php

				$guardar = new misDatosControlador();
				$guardar -> guardarDatosUControlador();

			?>


		</div>

		
	</section>
	
</div>