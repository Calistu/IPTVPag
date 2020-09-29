<?php

class IPTVInstalacao{
  public static function get_simp_tables_names(){
    $tabelas = array(
      "iptv",
      "clientes",
      "pagamentos",
      "tipos_msgs",
      "mensagens"
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
          nome varchar(300) not null default ' ',
          usuario varchar(300) not null default ' ',
          senha varchar(300) not null default ' ',
          whatsapp varchar(20) not null default ' ',
          criacao datetime not null default now(),
          expiracao datetime not null default now(),
          vlr_mensal float default 0);",

      'tipos_msgs' => "CREATE TABLE {$iptv->prefix}tipos_msgs(
        id int primary key auto_increment,
        nome varchar(50) not null default '');",

      'mensagens' => "CREATE TABLE {$iptv->prefix}mensagens(
        id int primary key auto_increment,
        nome varchar(50) not null default '',
        tipos_msgs int default not null,
        conteúdo longtext not null default '',
        foreign key(tipos_msgs) references tipos_msgs(id));",

      'pagamentos' => "CREATE TABLE IF NOT EXISTS {$iptv->prefix}pagamentos(
          id int primary key auto_increment,
          nome varchar(300) not null);");

    return $tabelas[$tabela_name];

  }

  public function criar_tabelas(){
    global $wpdb;

    $tabelas = $this->get_simp_tables_names();
    $querys = '';
    foreach($tabelas as $tabela){
      if(!$wpdb->query($this->get_schemas($tabela)))
        if($wpdb->print_error())
          wp_die('Erro ao criar tabela: ' . $tabela . $wpdb->print_error());
      //$querys .= "<br>" . $this->get_schemas($tabela) . "<br>";
    }
    //die($querys);
  }

  public function instalar(){
    $this->criar_tabelas();
  }
}
