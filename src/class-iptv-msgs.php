<?php

class IPTVMsgs extends IPTVElems{

  public $telefone ;

  function __construct(){
    $this->table = 'mensagens';
    $this->formfile = '?page=iptv-cad-msgs';
    $this->formfile_zstat = $this->formfile;
    $this->set_tipos();
    $this->set_msgs();
  }

  public function get_tipos_msgs(){
    global $wpdb, $iptv;
    return $wpdb->get_results("SELECT * FROM {$iptv->prefix}tipos_msgs where user_id = " . get_current_user_id(), ARRAY_A);
  }

  public function get_msgs(){
    global $wpdb, $iptv;
    return $wpdb->get_results("SELECT * FROM {$iptv->prefix}mensagens where user_id = " . get_current_user_id(), ARRAY_A);
  }

  public function set_tipos($tipos = null, $id = null){

    global $wpdb, $iptv;
    if(!$tipos){
      if($this->get_tipos_msgs()){
        return null;
      }
      $tipos['id'] = 1;
      $tipos['nome'] = 'Aviso Vencim.';
      $tipos['user_id'] = get_current_user_id();
      $wpdb->insert("{$iptv->prefix}tipos_msgs", $tipos);

      $tipos['id'] = 2;
      $tipos['nome'] = 'Formas Pag.';
      $tipos['user_id'] = get_current_user_id();
      $wpdb->insert("{$iptv->prefix}tipos_msgs", $tipos);

      $tipos['id'] = 3;
      $tipos['nome'] = 'Lembrete Vencim';
      $tipos['user_id'] = get_current_user_id();
      $wpdb->insert("{$iptv->prefix}tipos_msgs", $tipos);

      $tipos['id'] = 4;
      $tipos['nome'] = 'Falta Renon';
      $tipos['user_id'] = get_current_user_id();
      $wpdb->insert("{$iptv->prefix}tipos_msgs", $tipos);

    }else{
      $wpdb->update("{$iptv->prefix}tipos_msgs", $tipos, array('id' => $id));
    }
  }

  public function set_msgs($msgs = null, $id = null){
    global $wpdb, $iptv;
    if(!$msgs){
      if($this->get_msgs()){
        return null;
      }
      $msgs['id'] = 1;
      $msgs['tipos_msgs'] = 1;
      $msgs['conteudo'] = '';
      $msgs['user_id'] =  get_current_user_id();
      $wpdb->insert("{$iptv->prefix}mensagens", $msgs);

      $msgs['id'] = 2;
      $msgs['tipos_msgs'] = 2;
      $msgs['conteudo'] = '';
      $msgs['user_id'] = get_current_user_id();
      $wpdb->insert("{$iptv->prefix}mensagens", $msgs);

      $msgs['id'] = 3;
      $msgs['tipos_msgs'] = 3;
      $msgs['conteudo'] = '';
      $msgs['user_id'] = get_current_user_id();
      $wpdb->insert("{$iptv->prefix}mensagens", $msgs);

      $msgs['id'] = 4;
      $msgs['tipos_msgs'] = 4;
      $msgs['conteudo'] = '';
      $msgs['user_id'] = get_current_user_id();
      $wpdb->insert("{$iptv->prefix}mensagens", $msgs);

    }else{
      $wpdb->update("{$iptv->prefix}mensagens", $tipos, array('id' => $id));
    }
  }
}
