<?php

define ('STATUS_CADASTRANDO',0);
define ('STATUS_ALTERANDO',1);
define ('STATUS_ATUALIZANDO',2);
define ('STATUS_DELETANDO',3);

class IPTVElems{

  public $table = '';
  public $lista = array();
  public $campos = array();
  public $campos_obrig = array();
  public $form;
  public $formfile;
  public $formfile_zstat;
  public $form_stat=0;
  public $erro=0;

  public $args = array();

  public function __construct(){

  }

  public function status_cadastrar(){
    $form_stat = STATUS_CADASTRANDO;
    $this->formfile = $this->formfile_zstat . '&cadastrar';
  }

  public function status_alterar($id){
    $this->form_stat = STATUS_ALTERANDO;
    $this->formfile = $this->formfile_zstat . '&alterar=' . $this->id;
  }

  public function status_atualizar(){
    $this->form_stat = STATUS_ATUALIZANDO;
    $this->formfile = $this->formfile_zstat . '&atualizar=' . $this->id;;
  }

  public function status_deletar($id){
    $this->form_stat = STATUS_DELETANDO;
    query_posts( array( 'cat' => 9993, 'showposts' => 1) );
    $this->formfile = $this->formfile_zstat . '&deletar=' . $this->id;
  }

  public function status_cancelar($msg){
    $this->form_stat = STATUS_CADASTRANDO;
    $this->formfile = $this->formfile_zstat;
    $this->erro = 0;
    ?>
    <script>
    <?php echo('location.href = \''. $this->formfile . '\'');?>

    </script>
    <?php
  }

  public function noneOptions(){
    ?>
    <select>
     <option value='vazio'> Nenhuma Cadastrada </option>
   </select>
    <?php
  }

  //mensagem personalizada sem status
  function Print($msg) {
      ?>
      <div class="notice">
          <p><?php _e( $msg, 'kidspay' ); ?></p>
      </div>
      <?php
  }

  //mensagem personalizada de conclusão
  function PrintOk($msg) {
      ?>
      <div class="notice notice-success is-dismissible">
          <p><?php _e( $msg, 'kidspay' ); ?></p>
      </div>
      <?php
  }

  //mensagem personalizada de erro
  public function PrintErro($msg){
    ?>
     <div class="notice error my-acf-notice is-dismissible" >
        <p>
          <?php
            _e( $msg , 'kidspay' );
            $this->erro = 1;
          ?>
        </p>
    </div>
    <?php
  }


  //imprime formulário utilizando vetor de inputs informado em $this->form
  public function form_display(){
    echo "<form id='form' method='POST' action='$this->formfile'>";
    ?>
      <table class="form-table">
        <div class="meta-box-sortables">
        <?php

        foreach ($this->form as $i => $form) {

          if(!isset($form['name']))
            $form['name'] = '';

          if(!isset($form['descr']))
            $form['descr'] = '';

          if(!isset($form['tipo']))
            $form['tipo'] = '';

          if(!isset($form['classe']))
            $form['classe'] = '';

          if(!isset($form['valor']))
            $form['valor'] = '';

          if(!isset($form['pattern']))
            $form['pattern'] = '';

          if(!isset($form['step']))
            $form['step'] = '';

          echo "
            <tr>
              <th valign='top' scope='row'><label>{$form['descr']}</label></th>
              <td><input type='{$form['tipo']}' class='{$form['classe']}' id='{$form['descr']}' step='{$form['step']}' name='{$form['name']}' value='{$form['valor']}'></td>
            </tr>
          ";
        }
        ?>
        </div>
      </table>
    </form>
    <?php
  }

  //recebe todos campos do $_REQUEST
  public function guardar_req_inputs(){
    foreach ($this->campos as $key => $campo) {
      if(isset($_REQUEST[$key]) and strlen($_REQUEST[$key])){
        $this->campos[$key] = $_REQUEST[$key];
      }
    }
  }

  //verifica se todos campos obrigatórios ($this->campos_obrig) estão preenchidos
  public function validar_preenchimento(){
    $this->erro = 0;

    foreach ($this->campos_obrig as $key => $campo) {

      if(isset($_REQUEST[$key]) and strlen($_REQUEST[$key])){
        $this->campos_obrig[$key] = $_REQUEST[$key];
      }else{
        if(!$this->erro)
          $this->PrintErro('Preencha os campos obrigatórios (*) ' . 'campo: ' . $key);
        $this->erro = 1;
      }
    }

    if($this->erro)
      return 1;

    return 0;
  }

  //recebe a lista de array da tabela padrão do elemento
  public function getList(){
    global $wpdb;
    global $iptv;
    $this->lista = $wpdb->get_results("SELECT * FROM {$iptv->prefix}{$this->table};", ARRAY_A);
    return $this->lista;
  }

  //imprime uma <select> com as options utilizando $this->lista
  public function displayOptions(){
    $options = $this->lista;

    if(!count($options)){
      $this->noneOptions();
    }else{
      echo "<select>";
      foreach ($options as $list) {
        echo "<option value='{$list['nome']}'> {$list['nome']} </option>";
      }
      echo "</select>";
    }

  }
}

?>
