<?php

function iptv_default_cad_page_display(){
  ?>
  <div class='wrap'>
    <h1 class='wp-heading-inline'>Cadastros IPTV</h1>
    <hr class='wp-head-end'>
    <?php
    /*---------------------------------------------------------------*/
    echo "<h1> Em desenvolvimento </h1>";
    /*---------------------------------------------------------------*/
    ?>
  </div>
  <?php
}

function iptv_clientes_cad_page_display(){
  ?>
  <div class='wrap'>
    <h1 class='wp-heading-inline'>Clientes</h1>
    <hr class='wp-head-end'>
    <?php
    /*---------------------------------------------------------------*/
    $cliente = new IPTVCliente();
    $cliente->getList();
    $cliente->campos = array(

      '0' =>  array(
        'descr' => 'Nome',
        'tipo' => 'text',
        'classe' => 'regular_text'
      ),

      '1' =>  array(
        'descr' => 'Servidor',
        'tipo' => 'text',
        'classe' => 'regular_text'
      ),

      '2' =>  array(
        'descr' => 'Ultimo Pagamento',
        'tipo' => 'date',
        'classe' => 'regular_text'
      ),

      '3' =>  array(
        'descr' => 'Ativo?',
        'tipo' => 'checkbox',
        'classe' => ''
      )
    );

    $cliente->cad_display();
    /*---------------------------------------------------------------*/
    ?>
  </div>
  <?php
}
