<?php

function iptv_default_cad_page_display(){
  ?>
  <div class='wrap'>
    <h1 class='wp-heading-inline'>Cadastros IPTV</h1>
    <hr class='wp-head-end'>

  </div>
  <?php
}

function iptv_msgs_cad_page_display(){
  ?>
  <div class='wrap'>
     <h1 class='wp-heading-inline'>Mensagens</h1>
     <hr class='wp-head-end'>
     <tbody>
      <?php
      global $iptv, $wpdb;
      $cliente = new IPTVCliente();
      $mensagens = new IPTVMsgs();

      if( isset($_REQUEST['tipo_msg'])&& !isset($_REQUEST['selecting'])){

        if(!isset($_REQUEST['tipo_msg']) or !$_REQUEST['tipo_msg']){
          $mensagens->PrintErro('Escolha o tipo');
        }

        if(!isset($_REQUEST['descricao']) or !$_REQUEST['descricao']){
          $mensagens->PrintErro('Insira a descricao');
        }

        $wpdb->update($iptv->prefix . 'mensagens', array(
          'conteudo' => $_REQUEST['descricao'],
        ), array('tipos_msgs' => $_REQUEST['tipo_msg'] ));

      }else{
        $mensagens->Notif('Preencha os campos');
      }
      ?>
      <?php
      echo "<form id='form_id' method='POST' action='{$mensagens->formfile}' >";
      ?>
      <table class="form-table">
        <div class="meta-box-sortables">
          <tr>
            <th>
              <Label>Tipo e Descrição</Label>
            </th>
          </tr>
          <tr>
            <td>
              <?php

              if(isset($_REQUEST['tipo_msg'])){
                $id = $_REQUEST['tipo_msg'];
              }else
                $id = 1;

              $msg = $cliente->receber_mensagens($id);
              $msgs = new IPTVMsgs();
              $res = $msgs->get_tipos_msgs();

              if($res and count($res)){
                ?> <select name='tipo_msg'> <?php
                foreach ($res as $key => $value) {
                  $select = '';
                  if(isset($_REQUEST['tipo_msg'])){
                    if($value['id'] === $_REQUEST['tipo_msg']){
                      $select  = 'selected';
                    }
                  }
                  ?>
                  <option <?php echo $select?> value='<?php echo $value['id']; ?>' >
                  <?php echo $value['nome']; ?>
                  </option>
                  <?php

                }

                ?> </select> <?php

              }else{
                $mensagens->Notif('Nenhum tipo de mensagem');
              }

              ?>
              <input type='submit' class='wp-core-ui button' name='selecting' value='Selecionar'>
            </td>
          </tr>
          <tr>
            <td>
            <textarea name='descricao' form='form_id' cols='40' rows='10'><?php
                  if(isset( $msg[0]['conteudo'] ) ){
                    echo $msg[0]['conteudo'];
                }
              ?></textarea>
            </td>
          </tr>
          <tr>
            <td>
              <input name='submit' type='submit' class='wp-core-ui button' value='confirmar'>
              <input name='send' type='hidden' value='send'>
            </td>
          </tr>
        </div>
      </table>
    </form>
    </tbody>
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
        'vlr_mensal' => '',
      );

      $action = null;
      if(isset($_REQUEST['action']))
        $action = $_REQUEST['action'];

      if($action === 'cadastrar'){
        $cliente->erro = 0;
        $cliente->formatar_data();
        $cliente->guardar_req_inputs();
        if(!$cliente->validar_preenchimento()){
          if(!$wpdb->insert($iptv->prefix . $cliente->table, $cliente->campos)){
            wp_die($wpdb->print_error());
            if($wpdb->show_errors()){
                echo '-';
            }
          }else{
            $cliente->PrintOk("Cliente cadastrado com sucesso");
          }
        }else{
          $cliente->status_cadastrar();
        }
      }

      if($action === 'alterar'){
        $cliente->id = $_REQUEST['id'];
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
      if($action === 'atualizar'){
        $cliente->id = $_REQUEST['id'];

        $cli_lista = new IPTVCliente();

        $cliente->formatar_data();
        $cliente->guardar_req_inputs();
        if(!$cliente->validar_preenchimento()){
          if($cliente->id){
            if( ! $wpdb->update( $iptv->prefix . $cliente->table, $cliente->campos, array('id' => $cliente->id) )){
              $cliente->PrintErro('Não houve atualização');
              if($wpdb->show_errors){
                $cliente->PrintErro($wpdb->print_error());
              }
            }else{
              $cliente->PrintOk("Cliente atualizado com sucesso");
              $cliente->status_alterar($cliente->id);
            }
          }else{
            $cliente->PrintErro('Não foi informado id da alteração');
          }
        }else{
          $cliente->status_cadastrar();
        }
      }
      if(!$action)
        $cliente->status_cadastrar();

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
          'descr' => 'Valor Mensal*',
          'name' => 'vlr_mensal',
          'tipo' => 'number',
          'valor' =>  $cliente->campos['vlr_mensal'],
          'classe' => 'small-text',
          'step' => "any"
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





//------------------------------------
// importação de dados
//------------------------------------
function file_upload_inputs(){
  ?>
  <table class='widefat fixed' cellspacing='0'>
    <tr>
      <td><input size='50' type='file' class='input' name='filename'><br /></td>
      <td><input type='submit' class='button' name='submit' value='Fazer Upload'></td>
    </tr>
  </table>
  <?php
}

function importar(){
  global $wpdb, $iptv;
  $form = new IPTVElems();

  echo "<table class='widefat fixed' cellspacing='0'>";

  echo "<th class='manage-column column-cb' scope='col'>Status</th>";
  echo "<th class='manage-column column-cb' scope='col'>Nome</th>";
  echo "<th class='manage-column column-cb' scope='col'>Usuário</th>";
  echo "<th class='manage-column column-cb' scope='col'>Senha</th>";
  echo "<th class='manage-column column-cb' scope='col'>WhatsApp</th>";
  echo "<th class='manage-column column-cb' scope='col'>Criação</th>";
  echo "<th class='manage-column column-cb' scope='col'>Expiração</th>";
  echo "<th class='manage-column column-cb' scope='col'>Valor Mensal</th>";

  if (isset($_POST['submit'])) {
    if (is_uploaded_file($_FILES['filename']['tmp_name'])) {
        echo "<h2>Mostrando conteudo:</h2>";
    }
    //Import uploaded file to Database
    if(isset($_FILES['filename']) and $_FILES['filename']['tmp_name']){
      $handle = fopen($_FILES['filename']['tmp_name'], "r");
      if(!$handle){
        $form->PrintErro("Não foi possível abrir arquivo");
        file_upload_inputs();
        return ;
      }
    }else{
      $form->PrintErro("Escolha o arquivo para upload");
      file_upload_inputs();
      return ;
    }

    $uploads = 0;
    $erros = 0;
    $observacoes = 0;
    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {

      $obs = 0;

      for ($i=0; $i <= 6 ; $i++) {
        if(!isset($data[$i])){
          $obs = 1;
          $observacoes++;
          $data[$i] = '';
        }
      }

      $criacao = strtotime($data[4]);
      if(!$criacao){
        $observacoes++;
        $obs = 1;
      }

      $expiracao = strtotime($data[5]);
      if(!$expiracao){
        $observacoes++;
        $obs = 1;
      }

      $res = $wpdb->insert("{$iptv->prefix}clientes",
      array(
        'nome' => $data[0],
        'usuario' => $data[1],
        'senha' => $data[2],
        'whatsapp' => $data[3],
        'criacao' => $criacao,
        'expiracao' => $expiracao,
        'vlr_mensal' => $data[6],
        'user_id' => get_current_user_id())
      );

      echo '<tr class="alternate">';
      if($res){

        if($obs === 1)
          echo "<td class='column-columnname'>Verifique!</td>";
        else
          echo "<td class='column-columnname'>Importado</td>";

        echo "<td class='column-columnname'>{$data[0]}</td>";
        echo "<td class='column-columnname'>{$data[1]}</td>";
        echo "<td class='column-columnname'>{$data[2]}</td>";
        echo "<td class='column-columnname'>{$data[3]}</td>";
        echo "<td class='column-columnname'>{$criacao}</td>";
        echo "<td class='column-columnname'>{$expiracao}</td>";
        echo "<td class='column-columnname'>R$ ". number_format(floatval($data[6]),2). "</td>";
        $uploads++;
      }else{
        echo "<td class='column-columnname'>Não importado</td>";
        echo "<td class='column-columnname'></td>";
        echo "<td class='column-columnname'></td>";
        echo "<td class='column-columnname'></td>";
        echo "<td class='column-columnname'></td>";
        echo "<td class='column-columnname'></td>";
        echo "<td class='column-columnname'></td>";
        echo "<td class='column-columnname'></td>";
        $erros++;
      }
      echo '</tr>';
    }
    echo "</table>";
    if(!$uploads){
      $form->Notif("Não houve uploads");
    }else{
      $form->Notif("Houve {$uploads} uploads");
    }

    if(!$erros){
      $form->Notif("Não houve erros");
    }else{
      $form->Notif("Houve {$erros} erros");
    }

    if($observacoes){
      $form->Notif("Houve {$observacoes} observacoes");
    }

    fclose($handle);
    print "Processo finalizado!";
    //view upload form
  }
  file_upload_inputs();
}

function iptv_imprt_cad_page_display(){
  ?>
  <div class='wrap'>
    <h1 class='wp-heading-inline'>Importação</h1>
    <hr class='wp-head-end'>
    <h3>Upload dos cadastros de clientes.</h3>
    <form method="post" action="?page=iptv-cad-import" enctype="multipart/form-data">
    <?php importar(); ?>
    </form>

  </div>
  <?php
}
