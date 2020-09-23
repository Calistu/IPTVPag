<?php

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

class IPTVDesinstalacao{


  public static function esconder_warnings(){
    global $iptv;
    $wpdb->query('SET sql_notes = 0;');
  }

  public static function mostrar_warnings(){
    global $wpdb;
    $wpdb->query('SET sql_notes = 1;');
  }

  public static function get_simp_tables_names(){
    $tabelas = array(
      "iptv",
      "clientes",
      "pagamentos"
      );
    return $tabelas;
  }

  public function get_pref_tables_names(){
    global $iptv;
    $tabelas = array(
      'iptv' => "{$iptv->prefix}iptv",
      'clientes' => "{$iptv->prefix}clientes",
      'pagamentos' => "{$iptv->prefix}pagamentos"
    );

    $tabela = $this->get_simp_tables_names();
    $key = 0;

    foreach ($tabelas as $tabela[$key] => $tabela[$key]) {
      $key++;
    }

    return $tabelas;
  }

  public function get_schemas($tabela_name){
    global $wpdb;

    $tabelas = "DROP TABLE IF EXISTS {$this->get_pref_tables_names()[$tabela_name]};" ;

    return $tabelas;

  }

  public function deletar_tabelas($tabelas = null){
    global $wpdb;

    if(!$tabelas)
      $tabelas = $this->get_simp_tables_names();
    $querys = '';
    foreach($tabelas as $tabela){
      if(!$wpdb->query($this->get_schemas($tabela)))
        wp_die('Erro ao deletar tabela: ' . $tabela);
      //$querys .= $this->get_schemas($tabela)."<br>";
    }
    //die($querys);
  }

  public function desinstalar(){
    $this->deletar_tabelas();
  }
}

register_activation_hook( __FILE__, 'criar_tabelas' );
