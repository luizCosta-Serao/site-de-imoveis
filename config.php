<?php

  session_start();

  $autoload = function($class) {
    include('classes/'.$class.'.php');
  };
  spl_autoload_register($autoload);

  define('INCLUDE_PATH', 'http://localhost/gestao_imoveis/');
  define('INCLUDE_PATH_PAINEL', INCLUDE_PATH.'painel/');
  define('BASE_DIR_PAINEL', __DIR__.'/painel');

  define('HOST', 'localhost');
  define('USER', 'root');
  define('PASSWORD', '');
  define('DATABASE', 'gestao_imoveis');

?>