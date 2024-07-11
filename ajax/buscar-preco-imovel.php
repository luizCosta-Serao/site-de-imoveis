<?php
  include('../config.php');
  if (Painel::isLogin() === false) {
    die('Você não está logado!');
  }
  header('Content-Type: application/json');
  // coluna preco na tabela do banco de dados deve ser definido como decimal 10,2

  $precoMinimo = $_GET['precoMinimo'];
  $precoMinimo = str_replace('.', '', $precoMinimo);
  $precoMinimo = str_replace(',', '.', $precoMinimo);
  $precoMinimo = str_replace('R$', '', $precoMinimo);

  $precoMaximo = $_GET['precoMaximo'];
  $precoMaximo = str_replace('.', '', $precoMaximo);
  $precoMaximo = str_replace(',', '.', $precoMaximo);
  $precoMaximo = str_replace('R$', '', $precoMaximo);

  $imoveis = [];
  if ($precoMinimo && !$precoMaximo) {
    $sql = MySql::connect()->prepare("SELECT * FROM `imoveis` WHERE preco > ?");
    $sql->execute(array($precoMinimo));
    $imoveis = $sql->fetchAll(PDO::FETCH_ASSOC);
  } else if ($precoMaximo && !$precoMinimo) {
    $sql = MySql::connect()->prepare("SELECT * FROM `imoveis` WHERE preco < ?");
    $sql->execute(array($precoMaximo));
    $imoveis = $sql->fetchAll(PDO::FETCH_ASSOC);
  } else if ($precoMinimo && $precoMaximo) {
    $sql = MySql::connect()->prepare("SELECT * FROM `imoveis` WHERE preco > ? AND preco < ?");
    $sql->execute(array($precoMinimo, $precoMaximo));
    $imoveis = $sql->fetchAll(PDO::FETCH_ASSOC);
  }

  foreach ($imoveis as $key => $value) {
    $imagens = MySql::connect()->prepare("SELECT * FROM `imagens_imovel` WHERE imovel_id = ? LIMIT 1");
    $imagens->execute(array($value['id']));
    $imagens = $imagens->fetch(PDO::FETCH_ASSOC);
    array_push($imoveis[$key], $imagens['imagem']);
  }
  if (count($imoveis) >= 1) {
    // Será que posso colocar o codigo HTML aqui para renderizar na página automaticamente?
    echo json_encode($imoveis);
  } else {
    echo json_encode('Ocorreu um erro ao exibir os clientes');
  }

  ?>