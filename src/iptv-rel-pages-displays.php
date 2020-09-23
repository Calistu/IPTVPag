<?php

function iptv_default_rel_page_display(){
  ?>
  <link rel='stylesheets' href='{}';>
  <div class='wrap'>
    <h1 class='wp-heading-inline'>Relatórios IPTV</h1>
    <hr class='wp-head-end'>
  <?php
  /*---------------------------------------------------------------*/
  echo "<h1> Em desenvolvimento </h1>"
  /*---------------------------------------------------------------*/
  ?>
  </div>
  <?php
}

function iptv_clientes_rel_page_display(){
  ?>
  <div class='wrap'>
    <h1 class='wp-heading-inline'>Clientes</h1>
    <hr class='wp-head-end'>
    <?php
    /*---------------------------------------------------------------*/
    $compras = new IPTVClientesList();
    $compras->prepare_items();
    $compras->display();
    /*---------------------------------------------------------------*/
    ?>
  </div>
  <?php

}

function iptv_pagamentos_rel_page_display(){
  ?>
  <div class='wrap'>
    <h1 class='wp-heading-inline'>Pagamentos</h1>
    <hr class='wp-head-end'>
    <?php
    /*---------------------------------------------------------------*/
    $pagamentos = new IPTVPagamentosList();
    $pagamentos->prepare_items();
    $pagamentos->display();
    /*---------------------------------------------------------------*/
    ?>
  </div>
  <?php
}
