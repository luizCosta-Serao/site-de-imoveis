<?php
  include('../../config.php');
  if (isset($_POST['tipo_acao']) && $_POST['tipo_acao'] === 'ordenar_empreendimentos') {
    $ids = $_POST['item'];

    $i = 1;
    foreach ($ids as $key => $value) {
      MySql::connect()->exec("UPDATE `empreendimentos` SET order_id = $i WHERE id = $value");
      $i++;
    }
  }
?>