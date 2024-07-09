<?php
  // Obtendo dados de um produto da tabela estoque
  $id = $_GET['id'];
  $sql = MySql::connect()->prepare("SELECT * FROM `imoveis` WHERE id = ?");
  $sql->execute(array($id));
  if ($sql->rowCount() === 0) {
    Painel::alert('erro', 'O produto que você deseja editar não existe');
    die();
  }
  $infoImovel = $sql->fetch();

  // Obtendo as imagens do produto
  $pegaImagens = MySql::connect()->prepare("SELECT * FROM `imagens_imovel` WHERE imovel_id = ?");
  $pegaImagens->execute(array($id));
  $pegaImagens = $pegaImagens->fetchAll();

  // Deletar imagem do produto
  if (isset($_GET['deletar_imagem'])) {
    $idDeleteImagem = $_GET['deletar_imagem'];

    $imagem = MySql::connect()->prepare("SELECT * FROM `imagens_imovel` WHERE id = ?");
    $imagem->execute(array($idDeleteImagem));
    $imagem = $imagem->fetch();

    @unlink(BASE_DIR_PAINEL.'/uploads/'.$imagem['imagem']);

    $sql = MySql::connect()->prepare("DELETE FROM `imagens_imovel` WHERE id = ?");
    $sql->execute(array($idDeleteImagem));

    Painel::alert('sucesso', 'A imagem foi deletada com sucesso');
    header('Location: '.INCLUDE_PATH_PAINEL.'editar-imovel?id='.$id);
    die();
  }
?>

<section class="imagens-imovel">
  <h1 class="title">Imagens do Imóvel</h1>
  <?php
    // Iterando as imagens do produto
    foreach ($pegaImagens as $key => $value) {
      // Inserindo as imagens na página
  ?>
    <div class="single-imagem-imovel">
      <img src="<?php echo INCLUDE_PATH_PAINEL; ?>uploads/<?php echo $value['imagem'] ?>" alt="">
      <a class="btn-deletar" href="<?php echo INCLUDE_PATH_PAINEL; ?>editar-imovel?id=<?php echo $id ?>&deletar_imagem=<?php echo $value['id']; ?>">Excluir</a>
    </div>
  <?php } ?>

</section>

<form method="post" enctype="multipart/form-data">
  <?php
    if (isset($_POST['acao'])) {
      // Obtendo valores dos inputs
      $nome = $_POST['nome'];
      $preco = $_POST['preco'];
      $area = $_POST['area'];

      // variáveis para upload de imagens
      $imagens = [];
      $amountFiles = count($_FILES['imagem']['name']);

      // variável indicativa de sucesso
      $sucesso = true;

      // Se usuário tiver inserido imagem
      if($_FILES['imagem']['name'][0] !== '') {
        // Verificando se images são válidas
        for ($i=0; $i < $amountFiles; $i++) {
          $imagemAtual = [
            'type' => $_FILES['imagem']['type'][$i],
            'size' => $_FILES['imagem']['size'][$i],
          ];
          if (Painel::imagemValida($imagemAtual) === false) {
            $sucesso = false;
            Painel::alert('erro', 'Uma das imagens selecionadas é inválida');
            break;
          }
        }
      }

      // Se não tiver nenhum problema
      if ($sucesso) {
        // Se usuário tiver selecionado imagens
        if ($_FILES['imagem']['name'][0] !== '') {
          for ($i=0; $i < $amountFiles; $i++) {
            // Fazer o upload das imagens
            $imagemAtual = [
              'tmp_name' => $_FILES['imagem']['tmp_name'][$i],
              'name' => $_FILES['imagem']['name'][$i],
            ];
            $imagens[] = Painel::uploadFile($imagemAtual);
          }

          foreach ($imagens as $key => $value) {
            // Inserindo cada imagem na tabela estoque_imagens
            MySql::connect()->exec("INSERT INTO `imagens_imovel` VALUES (null, $id, '$value')");
          }
        }

        // Atualizando a tabela estoque
        $sql = MySql::connect()->prepare("UPDATE `imoveis` SET nome = ?, preco = ?, area = ? WHERE id = ?");
        $sql->execute(array($nome, $preco, $area, $id));

        Painel::alert('sucesso', 'Você atualizou seu produto com sucesso!');

        // Atualizando a página para novo valores aparecerem
        header('Location: '.INCLUDE_PATH_PAINEL.'editar-imovel?id='.$id);
        die();
      }
    }
  ?>

  <div class="form-group">
    <label for="nome">Nome do Imovel</label>
    <input type="text" name="nome" id="nome" value="<?php echo $infoImovel['nome']; ?>">
  </div>

  <div class="form-group">
    <label for="preco">Preço do Imóvel</label>
    <input type="text" name="preco" id="preco" value="<?php echo $infoImovel['preco'] ?>">
  </div>

  <div class="form-group">
    <label for="area">Área do Imóvel</label>
    <input type="number" name="area" id="area" min="0" max="900" step="1" value="<?php echo $infoImovel['area']; ?>">
  </div>

  <div class="form-group">
    <label for="imagem">Selecione as Imagens</label>
    <input multiple type="file" name="imagem[]" id="imagem">
  </div>

  <input type="submit" name="acao" id="acao" value="Atualizar Imóvel">
</form>

<section class="deletar-imovel">
  <?php
    $sql = MySql::connect()->prepare("SELECT * FROM `imoveis`");
    $sql->execute();
    if ($sql->rowCount() >= 1) {
      if (isset($_GET['deletar'])) {
        $deleteId = $_GET['id'];
        $imagens = MySql::connect()->prepare("SELECT * FROM `imagens_imovel` WHERE imovel_id = $deleteId");
        $imagens->execute();
        $imagens = $imagens->fetchAll();
        foreach ($imagens as $key => $value) {
          @unlink(BASE_DIR_PAINEL.'/uploads/'.$value['imagem']);
        }
    
        $deleteImagensImovel = MySql::connect()->prepare("DELETE FROM `imagens_imovel` WHERE imovel_id = $deleteId");
        $deleteImagensImovel->execute();
        $deleteImovel = MySql::connect()->prepare("DELETE FROM `imoveis` WHERE id = $deleteId");
        $deleteImovel->execute();
        Painel::alert('sucesso', 'O produto foi deletado do estoque com sucesso');
        header('Location: '.INCLUDE_PATH_PAINEL.'listar-empreendimentos');
      }
  }
  ?>
  <a class="btn-deletar" href="<?php echo INCLUDE_PATH_PAINEL ?>editar-imovel?id=<?php echo $id ?>&deletar">Deletar Imóvel</a>
</section>
