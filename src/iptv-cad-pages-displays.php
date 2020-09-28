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
     <tbody>
      <?php

      /*---------------------------------------------------------------*/
      $cliente = new IPTVCliente();
      global $wpdb;
      global $iptv;

      $cliente->campos = array(
        'nome' => '',
        'usuario' => '',
        'senha' => '',
        'whatsapp' => '',
        'criacao' => '',
        'expiracao' => '',
        'vlr_mensal' => ''
      );

      $cliente->campos_obrig = array(
        'nome' => '',
        'criacao' => '',
        'expiracao' => '',
        'whatsapp' => '',
      );

      if(isset($_REQUEST['cadastrar'])){
        $cliente->erro = 0;
        $cliente->formatar_data();
        $cliente->guardar_req_inputs();
        if(!$cliente->validar_preenchimento()){
          if(!$wpdb->insert($iptv->prefix . $cliente->table, $cliente->campos)){
            if($wpdb->show_errors()){
              wp_die($wpdb->print_error());
            }
          }else{
            $cliente->PrintOk("Cliente cadastrado com sucesso");
          }
        }else{
          $cliente->status_cadastrar();
        }
      }else
      if(isset($_REQUEST['alterar'])){
        $cliente->id = $_REQUEST['alterar'];
        if($cliente->id){
          $query = "SELECT id, nome, usuario, whatsapp, senha, DATE_FORMAT(criacao,'%Y-%m-%d'), DATE_FORMAT(expiracao,'%Y-%m-%d'), vlr_mensal FROM {$iptv->prefix}clientes " . "where id = $cliente->id;";
          $campos = $wpdb->get_results($query, ARRAY_A);
          if(!$campos){
            $cliente->status_alterar($cliente->id);
            $cliente->PrintErro('Cliente não existente');
            wp_die('');
          }else{
            foreach($campos as &$val){
                $val['criacao'] = $val["DATE_FORMAT(criacao,'%Y-%m-%d')"];
                unset($val["DATE_FORMAT(criacao,'%Y-%m-%d')"]);
                $val['expiracao'] = $val["DATE_FORMAT(expiracao,'%Y-%m-%d')"];
                unset($val["DATE_FORMAT(expiracao,'%Y-%m-%d')"]);
            }
            $cliente->campos = $campos[0];
          }
        }
        else{
          $cliente->status_alterar($cliente->id);
          $cliente->PrintErro('Não foi informado id da alteração');
        }
        $cliente->status_atualizar();
      }else
      if(isset($_REQUEST['atualizar'])){
        $cliente->id = $_REQUEST['atualizar'];
        $cliente->formatar_data();
        $cliente->guardar_req_inputs();
        $cliente->validar_preenchimento();
        if($cliente->id){
          if( ! $wpdb->update( $iptv->prefix . $cliente->table, $cliente->campos, array('id' => $cliente->id) )){
            $cliente->PrintErro('Não houve atualização');
            if($wpdb->show_errors){
              $cliente->PrintErro($wpdb->print_error());
              wp_die('');
            }
          }else{
            $cliente->PrintOk("Cliente atualizado com sucesso");
          }
        }else{
          $cliente->PrintErro('Não foi informado id da alteração');
        }
      }else
      if(isset($_REQUEST['deletar'])){
        $cliente->id = $_REQUEST['deletar'];
        if($cliente->id){
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
      }else{
        $cliente->status_cadastrar();
      }

      $cliente->form = array(

        '0' => array(
          'descr' => 'Nome *',
          'name' => 'nome',
          'tipo' => 'text',
          'valor' => $cliente->campos['nome'],
          'classe' => 'regular_text',
        ),

        '1' => array(
          'descr' => 'Usuário',
          'name' => 'usuario',
          'tipo' => 'text',
          'valor' =>  $cliente->campos['usuario'],
          'classe' => 'regular_text',
        ),

        '2' => array(
          'descr' => 'Senha',
          'name' => 'senha',
          'tipo' => 'password',
          'valor' =>  $cliente->campos['senha'],
          'classe' => 'regular_text'
        ),

        '3' => array(
          'descr' => 'WhatsApp *',
          'name' => 'whatsapp',
          'tipo' => 'text',
          'valor' =>  $cliente->campos['whatsapp'],
          'classe' => 'regular_text'
        ),

        '4' => array(
          'descr' => 'Data Criação *',
          'name' => 'criacao',
          'tipo' => 'date',
          'valor' => $cliente->campos['criacao'],
          'classe' => 'regular_text'
        ),

        '5' => array(
          'descr' => 'Data Expiração *',
          'name' => 'expiracao',
          'tipo' => 'date',
          'valor' =>  $cliente->campos['expiracao'],
          'classe' => 'regular_text'
        ),

        '6' => array(
          'descr' => 'Valor Mensal',
          'name' => 'vlr_mensal',
          'tipo' => 'number',
          'valor' =>  $cliente->campos['vlr_mensal'],
          'classe' => 'small-text'
        ),

        '7' => array(
          'descr' => '',
          'tipo' => 'submit',
          'classe' => 'wp-core-ui button',
          'valor' => 'Concluir'
        ),
      );

      $cliente->getList();
      $cliente->form_display();
      /*---------------------------------------------------------------*/
      ?>
    <tbody>
  </div>
  <?php
}
