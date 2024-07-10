<?php
  include('../config.php');
  if (Painel::isLogin() === false) {
    die('Você não está logado!');
  }
  header('Content-Type: application/json');
  $busca = $_GET['busca'];
  $sql = MySql::connect()->prepare("SELECT * FROM `imoveis` WHERE nome LIKE '%$busca%'");
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