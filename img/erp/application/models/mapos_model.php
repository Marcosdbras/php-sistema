<?php
class Mapos_model extends CI_Model {

    /**
     * author: Ramon Silva 
     * email: silva018-mg@yahoo.com.br
     * 
     */
    
    function __construct() {
        parent::__construct();
    }

    
    function get($table,$fields,$where='',$perpage=0,$start=0,$one=false,$array='array'){
        
        $idusumestre = $this->session->userdata('idusumestre');
        
        $this->db->select($fields);
        $this->db->from($table);
        $this->db->where('idusumestre',$idusumestre);
        $this->db->limit($perpage,$start);
        if($where){
            $this->db->where($where);
        }
        
        $query = $this->db->get();
        
        $result =  !$one  ? $query->result() : $query->row();
        return $result;
    }

    function getById($id){
        $this->db->from('usuarios');
        $this->db->select('usuarios.*, permissoes.nome as permissao');
        $this->db->join('permissoes', 'permissoes.idPermissao = usuarios.permissoes_id', 'left');
        $this->db->where('idUsuarios',$id);
        $this->db->limit(1);
        return $this->db->get()->row();
    }

    public function alterarSenha($senha,$oldSenha,$id){

        $this->db->where('idUsuarios', $id);
        $this->db->limit(1);
        $usuario = $this->db->get('usuarios')->row();

        if($usuario->senha != $oldSenha){
            return false;
        }
        else{
            $this->db->set('senha',$senha);
            $this->db->where('idUsuarios',$id);
            return $this->db->update('usuarios');    
        }

        
    }

    function pesquisar($termo){
         
        $data = array();
        
        $idusumestre = $this->session->userdata('idusumestre');
        
         // buscando clientes
         $this->db->where('idusumestre',$idusumestre);     
         $this->db->like('nomeCliente',$termo);
         $this->db->limit(5);
         $data['clientes'] = $this->db->get('clientes')->result();

         // buscando fornecedores
         $this->db->where('idusumestre',$idusumestre);     
         $this->db->like('nomeFornecedor',$termo);
         $this->db->limit(5);
         $data['fornecedores'] = $this->db->get('fornecedores')->result();

         // buscando os
         $this->db->where('idusumestre',$idusumestre);
         //$this->db->like('idOs',$termo);
         $this->db->like('iddetalhe',$termo);
         $this->db->limit(5);
         $data['os'] = $this->db->get('os')->result();

         // buscando produtos
         $this->db->where('idusumestre',$idusumestre);
         $this->db->like('descricao',$termo);
         $this->db->limit(5);
         $data['produtos'] = $this->db->get('produtos')->result();

         //buscando serviços
         $this->db->where('idusumestre',$idusumestre);
         $this->db->like('nome',$termo);
         $this->db->limit(5);
         $data['servicos'] = $this->db->get('servicos')->result();

         return $data;


    }

    
    function add($table,$data){
        $this->db->insert($table, $data);         
        if ($this->db->affected_rows() == '1')
		{
			return TRUE;
		}
		
		return FALSE;       
    }
    
    function edit($table,$data,$fieldID,$ID){
        $this->db->where($fieldID,$ID);
        $this->db->update($table, $data);

        if ($this->db->affected_rows() >= 0)
		{
			return TRUE;
		}
		
		return FALSE;       
    }
    
    function delete($table,$fieldID,$ID){
        $this->db->where($fieldID,$ID);
        $this->db->delete($table);
        if ($this->db->affected_rows() == '1')
		{
			return TRUE;
		}
		
		return FALSE;        
    }   
	
	function count($table){
	    
            
            $idusumestre = $this->session->userdata('idusumestre');
            $this->db->where('idusumestre',$idusumestre);
            return $this->db->count_all($table);
	}

    function getOsAbertas(){
        $idusumestre = $this->session->userdata('idusumestre');
        
        $this->db->select('os.*, clientes.nomeCliente');
        $this->db->from('os');
        $this->db->join('clientes', 'clientes.idClientes = os.clientes_id');
        $this->db->where('os.status','Aberto');
        $this->db->where('os.idusumestre',$idusumestre);
        $this->db->limit(10);
        return $this->db->get()->result();
    }

    function getProdutosMinimo(){

        $idusumestre = $this->session->userdata('idusumestre');
        
        $sql = "SELECT * FROM produtos WHERE  idusumestre = $idusumestre and estoque <= estoqueMinimo LIMIT 10"; 
        return $this->db->query($sql)->result();

    }

    function getOsEstatisticas(){
        $idusumestre = $this->session->userdata('idusumestre');
        
        $sql = "SELECT idusumestre, status, COUNT(status) as total FROM os GROUP BY status HAVING idusumestre = $idusumestre ORDER BY status";
        return $this->db->query($sql)->result();
    }

    public function getEstatisticasFinanceiro(){
        
        $idusumestre = $this->session->userdata('idusumestre');
        
        $sql = "SELECT idusumestre, SUM(CASE WHEN baixado = 1 AND tipo = 'receita' THEN valor END) as total_receita, 
                                    SUM(CASE WHEN baixado = 1 AND tipo = 'despesa' THEN valor END) as total_despesa,
                                    SUM(CASE WHEN baixado = 0 AND tipo = 'receita' THEN valor END) as total_receita_pendente,
                                    SUM(CASE WHEN baixado = 0 AND tipo = 'despesa' THEN valor END) as total_despesa_pendente FROM lancamentos where idusumestre = $idusumestre";
        return $this->db->query($sql)->row();
    }


    public function getEmitente()
    {
        $idusumestre = $this->session->userdata('idusumestre');
        $this->db->where('idusumestre',$idusumestre);
        
        return $this->db->get('emitente')->result();
    }

    public function addEmitente($nome, $cnpj, $ie, $logradouro, $numero, $bairro, $cidade, $uf,$telefone,$email, $logo, $cep, $ibge, $venda, $os, $comanda, $nfe){
       
       $idusumestre = $this->session->userdata('idusumestre'); 
        
       $this->db->set('nome', $nome);
       $this->db->set('cnpj', $cnpj);
       $this->db->set('ie', $ie);
       $this->db->set('rua', $logradouro);
       $this->db->set('numero', $numero);
       $this->db->set('bairro', $bairro);
       $this->db->set('cidade', $cidade);
       $this->db->set('uf', $uf);
       $this->db->set('telefone', $telefone);
       $this->db->set('email', $email);
       $this->db->set('url_logo', $logo);
       
       $this->db->set('idusumestre', $idusumestre);
       $this->db->set('cep',$cep);
       $this->db->set('ibge',$ibge);
       
       $this->db->set('venda',$venda);
       $this->db->set('os',$os);
       $this->db->set('comanda',$comanda); 
       $this->db->set('nfe',$nfe);
       
       return $this->db->insert('emitente');
    }


    public function editEmitente($id, $nome, $cnpj, $ie, $logradouro, $numero, $bairro, $cidade, $uf,$telefone,$email, $cep, $ibge, $venda, $os, $comanda, $nfe){
        
       
        
       $this->db->set('nome', $nome);
       $this->db->set('cnpj', $cnpj);
       $this->db->set('ie', $ie);
       $this->db->set('rua', $logradouro);
       $this->db->set('numero', $numero);
       $this->db->set('bairro', $bairro);
       $this->db->set('cidade', $cidade);
       $this->db->set('uf', $uf);
       $this->db->set('telefone', $telefone);
       $this->db->set('email', $email);
       $this->db->set('cep',$cep);
       $this->db->set('ibge',$ibge);
       
       $this->db->set('venda',$venda);
       $this->db->set('os',$os);
       $this->db->set('comanda',$comanda);
       $this->db->set('nfe',$nfe);
       
       $this->db->where('id', $id);
       
       
       
       return $this->db->update('emitente');
    }


    public function editLogo($id, $logo){
        
        $this->db->set('url_logo', $logo); 
        $this->db->where('id', $id);
        return $this->db->update('emitente'); 
         
    }
}