<?php
  // Obtendo o empreendimento
  $id = $_GET['id'];
  $empreendimento = MySql::connect()->prepare("SELECT * FROM `empreendimentos` WHERE id = ?");
  $empreendimento->execute(array($id));
  $empreendimento = $empreendimento->fetch();
?>

<section class="empreendimento">
  <p>Empreendimento: <h1 class="title"><?php echo $empreendimento['nome']; ?></h1></p>
  <div class="imagem-empreendimento">
    <h2>Imagem do Empreendimento</h2>
    <img src="<?php echo INCLUDE_PATH_PAINEL; ?>uploads/<?php echo $empreendimento['imagem'] ?>" alt="">
  </div>
  <div class="info-empreendimento">
    <h2>Informações do Empreendimento</h2>
    <p><b>Nome do Empreendimento:</b> <?php echo $empreendimento['nome'] ?></p>
    <p><b>Tipo do Empreendimento:</b> <?php echo $empreendimento['tipo'] ?></p>
  </div>
</section>

<section class="cadastrar-imovel">
  <form method="post" enctype="multipart/form-data">
  <?php
      if (isset($_POST['cadastrar'])) {
        // Obtendo valores dos inputs
        $empreendimentoId = $id;
        $nome = $_POST['nome'];
        $preco = $_POST['preco'];
        $area = $_POST['area'];

        // obtendo todas as imagens selecionadas
        $imagens = array();
        // obtendo a quantidade de imagens selecionadas
        $amountFiles = count($_FILES['imagem']['name']);

        $sucesso = true;

        // Se tiver selecionado uma imagem
        if ($_FILES['imagem']['name'][0] !== '') {
          // Loop nas imagens
          for ($i=0; $i < $amountFiles; $i++) {
            // obtendo o type e size de cada imagem 
            $imagemAtual = [
              'type' => $_FILES['imagem']['type'][$i],
              'size' => $_FILES['imagem']['size'][$i],
            ];
            // Se imagem tiver um formato inválido
            if (Painel::imagemValida($imagemAtual) === false) {
              $sucesso = false;
              Painel::alert('erro', 'Uma das imagens selecionadas é inválida');
              break;
            }
          }
        } else {
          // Se não tiver selecionado nenhuma imagem
          $sucesso = false;
          Painel::alert('erro', 'Você precisa selecionar pelo menos uma imagem');
        }

        if ($sucesso) {
          // Loop por cada imagem
          for ($i=0; $i < $amountFiles; $i++) {
            // obtendo o tmp_name e o name de cada imagem para fazer o upload 
            $imagemAtual = [
              'tmp_name' => $_FILES['imagem']['tmp_name'][$i],
              'name' => $_FILES['imagem']['name'][$i],
            ];
            $imagens[] = Painel::uploadFile($imagemAtual);
          }

          // Inserindo no banco de dados as informações do produto
          $sql = MySql::connect()->prepare("INSERT INTO `imoveis` VALUES (null, ?, ?, ?, ?)");
          $sql->execute(array($empreendimentoId, $nome, $preco, $area));

          // Obtendo o id da última inserção no banco de dados
          $lastId = MySql::connect()->lastInsertId();
          // Loop foreach nas imagens
          foreach ($imagens as $key => $value) {
            // Inserindo cada imagem no banco de dados
            MySql::connect()->exec("INSERT INTO `imagens_imovel` VALUES (null, $lastId, '$value')");
          }

          Painel::alert('sucesso', 'Imóvel cadastrado com sucesso');

        }

      }
    ?>
    <div class="form-group">
      <label for="nome">Nome</label>
      <input type="text"  name="nome" id="nome">
    </div>

    <div class="form-group">
      <label for="preco">Preço</label>
      <input type="text" name="preco" id="preco">
    </div>

    <div class="form-group">
      <label for="area">Área</label>
      <input type="number" min="0" max="900" step="1" name="area" id="area">
    </div>

    <div class="form-group">
      <label for="imagem">Selecione as imagens do Imóvel</label>
      <input type="file" multiple name="imagem[]" id="imagem">
    </div>

    <input type="submit" name="cadastrar" value="Cadastrar Imóvel">
  </form>
</section>

<section class="tabela">
  <div class="titulo-itens">
    <p>Nome</p>
    <p>Preço</p>
    <p>Área</p>
    <p>#</p>
  </div>
  <div class="valor-itens">
    <?php
      $imoveis = MySql::connect()->prepare("SELECT * FROM `imoveis` WHERE empreendimento_id = ?");
      $imoveis->execute(array($id));
      $imoveis = $imoveis->fetchAll();
      foreach ($imoveis as $key => $value) {
    ?>
      <div>
        <p><?php echo $value['nome'] ?></p>
        <p><?php echo $value['preco'] ?></p>
        <p><?php echo $value['area'] ?></p>
        <p><a class="btn-visualizar" href="<?php echo INCLUDE_PATH_PAINEL; ?>editar-imovel?id=<?php echo $value['id'] ?>">Editar</a></p>
      </div>
    <?php } ?>
  </div>
</section>
