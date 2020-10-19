<?php

if( !defined('ABSPATH')){
  die(ERRO_ABSPATH);
}


class IPTVPag{
  public $prefix;
}

global $iptv;
$iptv = new IPTVPag();
$iptv->prefix = 'iptv_';

require_once __DIR__ . '/LoadIPTV.php';

Class IPTVPlugin{
  public static function ativar(){
    $instalacao = new IPTVInstalacao();
    $instalacao->criar_tabelas();
  }

  public static function desativar(){
    $desativacao = new IPTVDesinstalacao();
    $desativacao->desinstalar();
  }

  public static function desinstalar(){
    $desinstalacao = new IPTVDesinstalacao();
    $desinstalacao->desinstalar();
  }
}

function iptv_registrar_cadastros(){
  add_menu_page('WhatsPanel', 'Cadastros', 'read', 'iptv-cad-tools', 'iptv_default_cad_page_display', 'dashicons-table-col-after', 30);
  add_submenu_page('iptv-cad-tools', 'Clientes', 'Clientes', 'read', 'iptv-cad-clientes', 'iptv_clientes_cad_page_display');
  add_submenu_page('iptv-cad-tools', 'Mensagens', 'Mensagens', 'read', 'iptv-cad-msgs', 'iptv_msgs_cad_page_display');
  add_submenu_page('iptv-cad-tools', 'Importação', 'Importação', 'read', 'iptv-cad-import', 'iptv_imprt_cad_page_display');
}

function iptv_registrar_relatorios(){
  add_menu_page('WhatsPanel', 'Lista Clientes', 'read', 'iptv-rel-tools', 'iptv_clientes_rel_page_display', 'dashicons-media-text', 30);
  add_submenu_page('iptv-rel-tools', 'Clientes', 'Clientes', 'read', 'iptv-rel-clientes', 'iptv_clientes_rel_page_display');
}

$IPTV = new IPTVPlugin();

add_action('admin_menu', 'iptv_registrar_cadastros');
add_action('admin_menu', 'iptv_registrar_relatorios');

register_activation_hook(IPTV_PLUGIN_FILE, array($IPTV, 'ativar'));
register_deactivation_hook(IPTV_PLUGIN_FILE, array($IPTV, 'desativar'));
register_uninstall_hook(IPTV_PLUGIN_FILE, array( 'desinstalar' ));
