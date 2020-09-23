<?php

class IPTVInstalacao{
  public static function get_simp_tables_names(){
    $tabelas = array(
      "iptv",
      "clientes",
      "pagamentos"
      );
    return $tabelas;
  }

  public static function pegar_warnings(){
    global $wpdb;
    return $wpdb->get_results('SHOW WARNINGS;');
  }

  public static function esconder_warnings(){
    global $wpdb;
    $wpdb->query('SET sql_notes = 0;');
  }

  public static function mostrar_warnings(){
    global $wpdb;
    $wpdb->query('SET sql_notes = 1;');
  }

  public static function get_schemas($tabela_name){
    global $iptv;
    $tabelas = array(

      'iptv' => "CREATE TABLE IF NOT EXISTS {$iptv->prefix}iptv(
        id int primary key auto_increment,
        nome varchar(300) not null);",

      'clientes' => "CREATE TABLE IF NOT EXISTS {$iptv->prefix}clientes(
          id int primary key auto_increment,
          nome varchar(300) not null);",

      'pagamentos' => "CREATE TABLE IF NOT EXISTS {$iptv->prefix}pagamentos(
          id int primary key auto_increment,
          nome varchar(300) not null);"
        );

    return $tabelas[$tabela_name];

  }

  public function criar_tabelas(){
    global $wpdb;

    $tabelas = $this->get_simp_tables_names();
    $querys = '';
    foreach($tabelas as $tabela){
      if(!$wpdb->query($this->get_schemas($tabela)))
        wp_die('Erro ao criar tabela: ' . $tabela);
      //$querys .= $this->get_schemas($tabela);
    }
    //die($querys);
  }

  public function instalar(){
    $this->criar_tabelas();
  }
}
