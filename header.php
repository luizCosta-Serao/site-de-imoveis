<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="<?php echo INCLUDE_PATH ?>css/style.css">
  <title>Inovel - Imóveis</title>
</head>
<body>
  
<header class="header">
  <section class="container">
    <div class="logo">Inovel</div>
    <nav class="menu">
      <ul>
        <li><a href="">Centro Empresarial 1</a></li>
        <li><a href="">Centro Empresarial 2</a></li>
        <li><a href="">Centro Empresarial 3</a></li>
      </ul>
    </nav>
  </section>
</header>

<div class="container">
<section class="search1">
    <h2>O que você procura?</h2>
    <input type="text" name="texto-busca">
</section>

<section class="search2">
  <form action="" method="post">
      <h2>Filtros</h2>
      <div class="form-group">
        <label for="area_minima">Área Mínima</label>
        <input type="number" min="0" max="100000" step="100" name="area_minima" id="area_minima">
      </div>

      <div class="form-group">
        <label for="area_maxima">Área Máxima</label>
        <input type="number" min="0" max="100000" step="100" name="area_maxima" id="area_maxima">
      </div>

      <div class="form-group">
        <label for="preco_minimo">Preço Mínimo</label>
        <input type="text" name="preco_minimo" id="preco_minimo">
      </div>

      <div class="form-group">
        <label for="preco_maximo">Preço Máximo</label>
        <input type="text" name="preco_maximo" id="preco_maximo">
      </div>
    </form>
  </section>
</div>