<?php

class IPTVElems{

  public $table = '';
  public $lista = array();
  public $campos = array();

  public function noneOptions(){
    ?>
    <select>
     <option value='vazio'> Nenhuma Cadastrada </option>
   </select>
    <?php
  }

  public function cad_display(){
    ?>
    <table class="form-table">
    <?php
    foreach ($this->campos as $i => $campos) {
      echo "
      <tr>
        <th scope='row'><label>{$campos['descr']}</label></th>
        <td><input type='{$campos['tipo']}' class='{$campos['classe']}' id='{$campos['descr']}'></td>
      </tr>
      ";
    }
    ?>
    </table>
    <?php
  }

  public function getList(){
    global $wpdb;
    global $iptv;
    $this->lista = $wpdb->get_results("SELECT * FROM {$iptv->prefix}{$this->table};", ARRAY_A);
    return $this->lista;
  }

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
