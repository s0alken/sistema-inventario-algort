<div class="col-sm-2 d-flex">
   
  <div class="logo-form-img-container align-self-center">
    <img src="<?php echo '../img/logo.png?' . rand() ?>">
  </div>

</div>

<div class="col-sm-6">
  
  <div class="py-3 py-sm-0 px-sm-3">
    
    <div class="font-weight-bold h4"><?php echo $empresa->nombre_empresa ?></div>
    <div class="font-weight-bold"><?php echo $empresa->razon_social ?></div>
    <div class="mb-2 text-muted"><?php echo $empresa->giro ?></div>
    <div><?php echo "Dirección: " . $empresa->direccion ?></div>
    <div><?php echo "Teléfono: " . $empresa->telefono ?></div>
    <div><?php echo "Correo: " . $empresa->correo ?></div>

  </div>

</div>