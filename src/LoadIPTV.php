<?php

if( !defined('ABSPATH')){
  die(ERRO_ABSPATH);
}

require_once __DIR__ . '/iptv-vars.php';
require_once __DIR__ . '/class-iptv-instalacao.php';
require_once __DIR__ . '/class-iptv-desinstalacao.php';
require_once __DIR__ . '/iptv-default-menu-pages.php';

require_once __DIR__ . '/class-iptv-elems.php';

require_once __DIR__ . '/class-iptv-pagamentos.php';
require_once __DIR__ . '/class-iptv-pagamentos-list.php';

require_once __DIR__ . '/class-iptv-clientes.php';
require_once __DIR__ . '/class-iptv-clientes-list.php';

require_once __DIR__ . '/iptv-cad-pages-displays.php';
require_once __DIR__ . '/iptv-rel-pages-displays.php';

require_once __DIR__ . '/class-iptv-plugin.php';
