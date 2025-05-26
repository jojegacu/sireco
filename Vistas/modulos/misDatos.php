<div class="content-wrapper">
	<section class="content">
		<div class="box-body">

			<?php

				$datos = new misDatosControlador();
				$datos -> vermisDatosControlador();

			?>

			<?php

				$guardar = new misDatosControlador();
				$guardar -> guardarDatosControlador();

			?>

		</div>
		
	</section>
	
</div>