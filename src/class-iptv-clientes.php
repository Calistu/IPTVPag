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
