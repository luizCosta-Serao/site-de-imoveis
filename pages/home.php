<div class="container">
  <section class="lista-imoveis">
    <?php
      if (!isset($_GET['empreendimento'])) {
        // Se parâmetro empreendimento nao estiver setado
        // Selecionar todos os imóveis
        $selectImoveis = MySql::connect()->prepare("SELECT * FROM `imoveis`");
        $selectImoveis->execute();
        $imoveis = $selectImoveis->fetchAll();
        echo '<h2>Listando '.count($imoveis).' imóveis</h2>';
      } else {
        // Se parâmetro empreendimento estiver setado
        // Obter o valor de GET empreendimento (slug)
        $slugEmpreendimento = $_GET['empreendimento'];
        // Obter o empreendimento
        $empreendimento = MySql::connect()->prepare("SELECT * FROM `empreendimentos` WHERE slug = ?");
        $empreendimento->execute(array($slugEmpreendimento));
        // Se existir empreendimento
        if ($empreendimento->rowCount() === 1) {
          // Obter os imóveis que estão no empreendimento
          $empreendimento = $empreendimento->fetch();
          $selectImoveis = MySql::connect()->prepare("SELECT * FROM `imoveis` WHERE empreendimento_id = ?");
          $selectImoveis->execute(array($empreendimento['id']));
          $imoveis = $selectImoveis->fetchAll();
          echo '<h2>Listando '.count($imoveis).' imóveis</h2>';
        }
      }

      // Iterando os imóveis
      foreach ($imoveis as $key => $value) {
    ?>
    <div class="single-imovel">
        <?php
          // Obter as imagens do imóvel
          $imagensImovel = MySql::connect()->prepare("SELECT * FROM `imagens_imovel` WHERE imovel_id = ? LIMIT 1");
          $imagensImovel->execute(array($value['id']));
          $imagensImovel = $imagensImovel->fetch();
        ?>
        <img src="<?php echo INCLUDE_PATH_PAINEL ?>/uploads/<?php echo $imagensImovel['imagem'] ?>" alt="">
        <div>
          <p>Nome do Imóvel: <?php echo $value['nome'] ?></p>
          <p>Área do Imóvel: <?php echo $value['area'] ?></p>
          <p>Preço do Imóvel: R$ <?php echo Painel::convertMoney($value['preco']); ?></p>
        </div>
    </div>
    <?php } ?>
  </section>
</div>