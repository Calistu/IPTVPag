<?php

class IPTVCliente extends IPTVElems{

  public $telefone ;

  function __construct(){
    $this->table = 'clientes';
    $this->formfile = '?page=iptv-cad-clientes';
    $this->formfile_zstat = $this->formfile;
  }
  public $msg_text = '';
  public $msg = '';

  public function receber_mensagens($args=''){

    if($args){
      $where = 'where tipos_msgs = ' . $args . ' and m.user_id = ' . get_current_user_id();
    }else{
      $where = 'where m.user_id = ' . get_current_user_id();
    }

    global $wpdb, $iptv;
    $res = $wpdb->get_results("SELECT m.id, tipos_msgs, nome, conteudo FROM {$iptv->prefix}mensagens as m inner join {$iptv->prefix}tipos_msgs as t on t.id = m.tipos_msgs {$where}", ARRAY_A);
    return $res;
  }

  public function get_clientes(){
    global $wpdb, $iptv;
    $data = $wpdb->get_results("SELECT id, nome, usuario, whatsapp, senha, DATE_FORMAT(criacao,'%d/%m/%Y'), DATE_FORMAT(expiracao,'%d/%m/%Y'), vlr_mensal FROM {$iptv->prefix}clientes where user_id = " . get_current_user_id() , ARRAY_A);
    return $data;
  }

  public function processar_mensagem(){
    $this->msg = urlencode($this->msg_text);
    $this->msg  = str_replace('%','%%',$this->msg);
  }

  public function formatar_data(){
    foreach ($this->campos as $key => $campo) {
      if(isset($_REQUEST[$key]) and strlen($_REQUEST[$key])){
        if( $key === 'criacao' || $key === 'expiracao' ){
          $date = strtotime($_REQUEST[$key]);
          if($date && $data = date('Y-m-d', $date)){
            $_REQUEST[$key] = $data;
          }else{
            $this->PrintErro('Data Incorreta');
            wp_die();
          }
        }
      }
    }
  }
}
