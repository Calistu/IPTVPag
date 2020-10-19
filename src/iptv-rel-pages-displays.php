<?php

function iptv_default_rel_page_display(){
  ?>
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

    /*==========================================================================*/
    $cliente = new IPTVCliente();
    global $wpdb;
    global $iptv;
    $action = null;

    if(isset($_REQUEST['action']))
      $action = $_REQUEST['action'];

    if($action==='deletar'){
      if(isset($_REQUEST['id'])){
        $cliente->id = $_REQUEST['id'];
        if( !$wpdb->delete( $iptv->prefix . $cliente->table, array('id' => $cliente->id) )){
          $cliente->PrintErro('Não houve itens deletados');
          if($wpdb->show_errors()){
            $cliente->PrintErro($wpdb->print_error());
          }
        }else{
          $cliente->status_cadastrar();
          $cliente->PrintOk("Cliente deletado com sucesso");
        }
      }else{
        $cliente->PrintErro('Não foi informado id da alteração');
      }
    }

    /*==========================================================================*/

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
