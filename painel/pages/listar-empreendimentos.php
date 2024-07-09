<?php
  // Deletar um empreendimento
  if(isset($_GET['id'])) {
    $idDeletar = $_GET['id'];
    
    // Deletando imagem na pasta uploads
    $sql = MySql::connect()->prepare("SELECT * FROM `empreendimentos` WHERE id = ?");
    $sql->execute(array($idDeletar));
    $sql = $sql->fetch();
    @unlink(BASE_DIR_PAINEL.'/uploads/'.$sql['imagem']);

    $imoveis = MySql::connect()->prepare("SELECT * FROM `imoveis` WHERE empreendimento_id = ?");
    $imoveis->execute(array($idDeletar));
    $imoveis = $imoveis->fetchAll();

    foreach ($imoveis as $key => $value) {
      $imagensImovel = MySql::connect()->prepare("SELECT * FROM `imagens_imovel` WHERE imovel_id = ?");
      $imagensImovel->execute(array($value['id']));
      $imagensImovel = $imagensImovel->fetchAll();
      foreach ($imagensImovel as $key2 => $value2) {
        @unlink(BASE_DIR_PAINEL.'/uploads/'.$value2['imagem']);
        MySql::connect()->exec("DELETE FROM `imagens_imovel` WHERE id = $value2[id]");
      }
    }
    $sql = MySql::connect()->prepare("DELETE FROM `imoveis` WHERE empreendimento_id = ?");
    $sql->execute(array($idDeletar));
    
    // Deletando empreendimento no banco de dados
    $sql = MySql::connect()->prepare("DELETE FROM `empreendimentos` WHERE id = ?");
    $sql->execute(array($idDeletar));
    
    header('Location: '.INCLUDE_PATH_PAINEL.'listar-empreendimentos');
    die();
  }
?>

<section class="buscar-empreendimento">
  <form method="post">
    <div class="form-group">
      <label for="busca">Buscar por empreendimento</label>
      <input type="text" name="busca" id="busca">
    </div>
    <input type="submit" name="buscar_empreendimento" value="Buscar">
  </form>
</section>

<!-- Adicionando o jQuery UI -->
<section class="lista-empreendimentos" id="sortable">
  <h1 class="title">Lista de Empreendimentos</h1>
  <?php
    // Se busca estiver setado selecionar todos os empreendimentos que contém o valor passado em busca, caso busca não estiver setado mostrar todos os empreendimentos
    if (!isset($_POST['buscar_empreendimento'])) {
      // Ordernando pelo order_id
      $empreendimentos = MySql::connect()->prepare("SELECT * FROM `empreendimentos` ORDER BY order_id ASC");
      $empreendimentos->execute();
      $empreendimentos = $empreendimentos->fetchAll();
    } else {
      $busca = $_POST['busca'];
      // Ordenando pelo order_id
      $empreendimentos = MySql::connect()->prepare("SELECT * FROM `empreendimentos` WHERE nome LIKE '%$busca%' OR tipo LIKE '%$busca%' ORDER BY order_id ASC");
      $empreendimentos->execute();
      $empreendimentos = $empreendimentos->fetchAll();
    }
    foreach ($empreendimentos as $key => $value) {
  ?>
    <div id="item-<?php echo $value['id']; ?>" class="single-empreendimento">
      <img src="<?php echo INCLUDE_PATH_PAINEL; ?>uploads/<?php echo $value['imagem'] ?>" alt="">
      <p><b>Nome:</b> <?php echo $value['nome'] ?></p>
      <p><b>Tipo:</b> <?php echo $value['tipo'] ?></p>
      <p><b>Preço:</b> R$<?php echo $value['preco'] ?></p>
      <a class="btn-deletar" href="<?php echo INCLUDE_PATH_PAINEL; ?>listar-empreendimentos?id=<?php echo $value['id'] ?>">Deletar</a>
      <a class="btn-visualizar" href="<?php echo INCLUDE_PATH_PAINEL; ?>visualizar-empreendimento?id=<?php echo $value['id'] ?>">Visualizar</a>
    </div>
  <?php } ?>
</section>