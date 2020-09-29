<?php

class IPTVMsgs extends IPTVElems{

  public $telefone ;

  function __construct(){
    $this->table = 'mensagens';
    $this->formfile = '?page=iptv-cad-msgs';
    $this->formfile_zstat = $this->formfile;
  }


}
