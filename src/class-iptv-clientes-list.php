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
    usort( $data, array( &$this, 'sort_data' ) );
    $currentPage = $this->get_pagenum();
    $perPage = 100;
    $totalItems = sizeof($data);

    $this->set_pagination_args( array(
        'total_items' => $totalItems,
        'per_page'    => $perPage
    ) );
    $data = array_slice($data,(($currentPage-1)*$perPage),$perPage);
    $this->_column_headers = array($columns, $hidden, $sortable);
    $this->items = $data;
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
    $data = $wpdb->get_results("SELECT id, nome, usuario, whatsapp, senha, DATE_FORMAT(criacao,'%d/%m/%Y'), DATE_FORMAT(expiracao,'%d/%m/%Y'), vlr_mensal FROM {$iptv->prefix}clientes;", ARRAY_A);

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
    if( !$wpdb->delete( $iptv->prefix . $cliente->table, array('id' => $id) )){
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
      $cliente->msg_text = "🚨🚨 Aviso de Vencimento🚨🚨

Prezado(a) *[cliente]*

Viemos por meio desta apenas para lembrar-lhe a data do vencimento da sua assinatura dos canais de TV, com vencimento em *[expiracao]*.

Caso o pagamento já tenha sido efetuado, por favor, nos enviar o comprovante e desconsiderar este aviso.

*Valor do Plano - R$ [vlr_mensal]*

19BR - Departamento de Cobrança

*FORMAS DE PAGAMENTO:*   🛒💳

1⃣ *CARTÃO DE CRÉDITO*
Via aplicativo online

2⃣ *ITAÚ*
Código banco: 341
Agência: 1370 - Conta: 08325-3
Conta Corrente - Nome: Leandro Silva Azevedo

3⃣ *BRADESCO*
Agência: 2387 - Conta: 16912-9
Conta corrente - Giovane Lucena da Silva

4⃣ *NUBANK*
Banco: 260 Nu Pagamentos
Agencia: 0001 - Conta: 6784496-2
Nome: Leandro Azevedo

5⃣ *CAIXA*
Código banco: 104
Agência: 4226 - Operação: 013
Conta: 14526-4 - Conta poupança
Nome: Daniela Cristina Silva Azevedo
O depósito pode ser feito diretamente na lotérica

*TRANSFERÊNCIA BANCÁRIA INFORMAR SEU NOME NA IDENTIFICAÇÃO*

6⃣ *BOLETO*

Transferência online (realizar o TED)
Depósito bancário (NA BOCA DO CAIXA)
Enviar comprovante após efetuar o pagamento, liberação instantânea.";

      $cliente->msg_text  = str_replace('[cliente]',$item['nome'],$cliente->msg_text);
      $cliente->msg_text  = str_replace('[vlr_mensal]',number_format(floatval($item['vlr_mensal']),2),$cliente->msg_text);
      $cliente->msg_text  = str_replace('[expiracao]',$item['expiracao'],$cliente->msg_text);

      $cliente->processar_mensagem();

      $link = 'https://api.whatsapp.com/send?phone=' . $item['whatsapp'] . '&' .'text=' . $cliente->msg;

      //add_thickbox();

      $actions = array(
          'edit' => sprintf("<a class='thickbox' href='{$cliente->formfile}&alterar=%s'>%s</a>", $item['id'], __('Editar', 'iptv')),
          'delete' => sprintf("<a href='{$this->link}&deletar=%s'>%s</a> ", $item['id'], __('Deletar', 'iptv')),
          'message' =>
          sprintf("<a id='link-id{$item['id']}' href='$link' target='_blank'>%s</a>",
           __('Msg', 'iptv')),
         );

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
