<?php
  include('../config.php');
  if (Painel::isLogin() === false) {
    die('Você não está logado!');
  }
  header('Content-Type: application/json');
  $areaMinima = $_GET['areaMinima'];
  $areaMaxima = $_GET['areaMaxima'];
  if ($areaMinima && !$areaMaxima) {
    $sql = MySql::connect()->prepare("SELECT * FROM `imoveis` WHERE area > $areaMinima");
  } else if ($areaMaxima && !$areaMinima) {
    $sql = MySql::connect()->prepare("SELECT * FROM `imoveis` WHERE area < $areaMaxima");
  } else if ($areaMinima && $areaMaxima) {
    $sql = MySql::connect()->prepare("SELECT * FROM `imoveis` WHERE area > $areaMinima AND area < $areaMaxima");

  }
  $sql->execute();
  $imoveis = $sql->fetchAll(PDO::FETCH_ASSOC);
  foreach ($imoveis as $key => $value) {
    $imagens = MySql::connect()->prepare("SELECT * FROM `imagens_imovel` WHERE imovel_id = ? LIMIT 1");
    $imagens->execute(array($value['id']));
    $imagens = $imagens->fetch(PDO::FETCH_ASSOC);
    array_push($imoveis[$key], $imagens['imagem']);
  }
  if ($sql->rowCount() >= 1) {
    echo json_encode($imoveis);
  } else {
    echo 'false';
    echo json_encode('Ocorreu um erro ao exibir os clientes');
  }

  ?>