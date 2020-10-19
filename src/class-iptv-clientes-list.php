<?php

if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/screen.php' );
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class IPTVClientesList extends WP_List_Table{

  public $link = '?page=iptv-rel-tools';

  public function prepare_items(){

    $columns = $this->get_columns();
    $hidden = $this->get_hidden_columns();
    $sortable = $this->get_sortable_columns();
    $data = $this->table_data();
    $this->_column_headers = array($columns, $hidden, $sortable);
    $currentPage = $this->get_pagenum();
    $perPage = 100;

    if($data){
      usort( $data, array( &$this, 'sort_data' ) );

      $data = array_slice($data,(($currentPage-1)*$perPage),$perPage);
      $totalItems = sizeof($data);
      $this->items = $data;

      $this->set_pagination_args( array(
        'total_items' => $totalItems,
        'per_page'    => $perPage
      ) );
    }

  }

  public function get_columns(){
    return array(
      'cb' => 'Seleção',
      'id' => 'ID',
      'nome' => 'Nome',
      'usuario' => 'Usuario',
      'senha' => 'Senha',
      'whatsapp' => 'WhatsApp',
      'criacao' => 'Criado em',
      'expiracao' => 'Expira em',
      'vlr_mensal' => 'Valor Mensal',
    );
  }

  public function get_hidden_columns(){
    return array(
      'id'
    );
  }

  public function get_sortable_columns(){
    return array(
      'nome' => array('nome',true),
      'usuario' => array('usuario', true),
      'criacao' => array('criacao',true),
      'expiracao' => array('expiracao',true),
    );
  }

  private function table_data(){
    global $wpdb;
    global $iptv;
    $cliente  = new IPTVCliente();
    $data = $cliente->get_clientes();
    if(!$data){
      return $data;
    }

    foreach($data as &$val){
        $val['criacao'] = $val["DATE_FORMAT(criacao,'%d/%m/%Y')"];
        $val['expiracao'] = $val["DATE_FORMAT(expiracao,'%d/%m/%Y')"];
        unset($val["DATE_FORMAT(criacao,'d/m/Y')"]);
    }

    return $data;
  }

  public function get_bulk_actions(){

    return array(
      'edit' => 'Editar',
      'delete' =>   'Deletar',
    );
  }

  function column_cb($item){
    return "<input type='checkbox'>";
  }

  public function delete_clientes($id){
    if( !$wpdb->delete( $iptv->prefix . $cliente->table, array('id' => $id, 'user_id' => get_current_user_id()) )){
      $cliente->PrintErro('Não houve itens deletados');
      if($wpdb->show_errors()){
        $cliente->PrintErro($wpdb->print_error());
      }
    }else{
      $cliente->status_cadastrar();
      $cliente->PrintOk("Cliente deletado com sucesso");
    }
  }

  function column_nome($item)
  {

      $cliente = new IPTVCliente();

      $msg = $cliente->receber_mensagens();

      if(!count($msg)){
        $cliente->msg_text = 'Sem mensagens';
      }
      //add_thickbox();

      $actions['edit'] = sprintf("<a class='thickbox' href='{$cliente->formfile}&action=alterar&id=%s'>%s</a>", $item['id'], __('Editar', 'iptv'));
      $actions['delete'] = sprintf("<a href='{$this->link}&action=deletar&id=%s'>%s</a> ", $item['id'], __('Deletar', 'iptv'));

      foreach ($msg as $key => $value) {

        $cliente->msg_text = $value['conteudo'];

        $cliente->msg_text  = str_replace('[cliente]',$item['nome'],$cliente->msg_text);
        $cliente->msg_text  = str_replace('[vlr_mensal]',number_format(floatval($item['vlr_mensal']),2),$cliente->msg_text);
        $cliente->msg_text  = str_replace('[expiracao]',$item['expiracao'],$cliente->msg_text);

        $cliente->processar_mensagem();

        $link = 'https://api.whatsapp.com/send?phone=' . $item['whatsapp'] . '&' .'text=' . $cliente->msg;

        $pula_linha = '';
        if(strlen($value['nome'])>=10){
          $pula_linha = '<br>';
        }

        $actions[ $value['nome'] ] = sprintf("{$pula_linha}<a id='link-id{$item['id']}' href='$link' target='_blank'>%s</a>",__($value['nome'], 'iptv'));

      }

      return sprintf('%s %s',
          $item['nome'],
          $this->row_actions($actions)
      );
  }

  public function column_default( $item, $column_name ){
    switch($column_name){

      case 'cb':
        return '<input typecheckbox>';
      case 'id':
      case 'nome':
      case 'usuario':
      case 'senha':
      case 'whatsapp':
      case 'criacao':
      case 'expiracao':
        return $item[ $column_name ];
      case 'vlr_mensal':
        return 'R$ ' . number_format(floatval($item[$column_name]),2);

      default:
          return print_r( $item, true ) ;
    }
  }

  private function sort_data( $a, $b ){
    $orderby = 'id';
    $order = 'asc';

    if(!empty($_GET['orderby'])){
      $orderby = $_GET['orderby'];
    }

    if(!empty($_GET['order'])){
      $order = $_GET['order'];
    }

    $result = strcmp( $a[$orderby], $b[$orderby] );
    if($order === 'asc')
    {
        return $result;
    }

    return -$result;
  }

  private function call_link(){

  }

}
