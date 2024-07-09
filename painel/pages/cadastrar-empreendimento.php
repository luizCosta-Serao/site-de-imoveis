<section>
  <h2 class="title">Cadastrar Empreendimento</h2>

  <form action="" method="post" enctype="multipart/form-data">
    <?php
      if (isset($_POST['cadastrar'])) {
        // Guardando valores dos inputs em variáveis
        $nome = $_POST['nome'];
        $tipo = $_POST['tipo'];
        $preco = $_POST['preco'];
        $imagem = $_FILES['imagem'];

        // Se tiver selecionado uma imagem e a imagem for válida
        if ($imagem['name'] !== '' && Painel::imagemValida($imagem)) {
          // Realizar upload da imagem
          $idImagem = Painel::uploadFile($imagem);
          // Inserir empreendimento no banco de dados 
          $sql = MySql::connect()->prepare("INSERT INTO `empreendimentos` VALUES (null, ?, ?, ?, ?, ?)");
          $sql->execute(array($nome, $tipo, $preco, $idImagem, 0));
          $lastId = MySql::connect()->lastInsertId();
          MySql::connect()->exec("UPDATE `empreendimentos` SET order_id = $lastId WHERE id = $lastId");
          // Mensagem de sucesso
          Painel::alert('sucesso', 'Cadastro do empreendimento realizado com sucesso');
        } else {
          // Mensagem de erro
          Painel::alert('erro', 'Você precisa selecionar uma imagem válida');
        }

      }
    ?>
    <div class="form-group">
      <label for="nome">Nome</label>
      <input type="text" name="nome" id="nome">
    </div>

    <div class="form-group">
      <label for="tipo">Tipo</label>
      <select name="tipo" id="tipo">
        <option value="residencial">Residencial</option>
        <option value="comercial">Comercial</option>
      </select>
    </div>

    <div class="form-group">
      <label for="preco">Preço</label>
      <input type="text" name="preco" id="preco">
    </div>
    
    <div class="form-group">
      <label for="imagem">Imagem do Imóvel</label>
      <input type="file" name="imagem" id="imagem">
    </div>

    <input type="submit" name="cadastrar" value="Cadastrar">
  </form>
</section>