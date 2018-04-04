<?php

class Os extends CI_Controller {

    /**
     * author: Ramon Silva 
     * email: silva018-mg@yahoo.com.br
     * 
     */
    function __construct() {
        parent::__construct();

        if ((!$this->session->userdata('session_id')) || (!$this->session->userdata('logado'))) {
            redirect('mapos/login');
        }

        $this->load->helper(array('form', 'codegen_helper'));
        $this->load->model('os_model', '', TRUE);
        $this->data['menuOs'] = 'OS';
    }

    function index() {
        $this->gerenciar();
    }

    function gerenciar() {

        $this->load->library('pagination');


        $config['base_url'] = base_url() . 'index.php/os/gerenciar/';
        $config['total_rows'] = $this->os_model->count('os');
        $config['per_page'] = 10;
        $config['next_link'] = 'Próxima';
        $config['prev_link'] = 'Anterior';
        $config['full_tag_open'] = '<div class="pagination alternate"><ul>';
        $config['full_tag_close'] = '</ul></div>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li><a style="color: #2D335B"><b>';
        $config['cur_tag_close'] = '</b></a></li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['first_link'] = 'Primeira';
        $config['last_link'] = 'Última';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $this->pagination->initialize($config);

        $this->data['results'] = $this->os_model->get('os', 'os.idOs,os.dataInicial,os.dataFinal,os.garantia,os.descricaoProduto,os.defeito,os.status,os.observacoes,os.laudoTecnico,os.iddetalhe', '', $config['per_page'], $this->uri->segment(3));
        //$this->data['results'] = $this->os_model->get('os', '*', '', $config['per_page'], $this->uri->segment(3));
        $this->data['view'] = 'os/os';
        $this->load->view('tema/topo', $this->data);
    }

    function adicionar() {

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';

        if ($this->form_validation->run('os') == false) {
            $this->data['custom_error'] = (validation_errors() ? true : false);
        } else {

            $dataInicial = $this->input->post('dataInicial');
            $dataFinal = $this->input->post('dataFinal');

            try {

                $dataInicial = explode('/', $dataInicial);
                $dataInicial = $dataInicial[2] . '-' . $dataInicial[1] . '-' . $dataInicial[0];

                if ($dataFinal) {
                    $dataFinal = explode('/', $dataFinal);
                    $dataFinal = $dataFinal[2] . '-' . $dataFinal[1] . '-' . $dataFinal[0];
                } else {
                    $dataFinal = date('Y/m/d');
                }
            } catch (Exception $e) {
                $dataInicial = date('Y/m/d');
                $dataFinal = date('Y/m/d');
            }

            //Author: Marcos Brás--------------------- 
            $this->db->select('idusumestre');
            $this->db->from('os');
            $this->db->where('idusumestre', $this->session->userdata('idusumestre'));
            $totreg = $this->db->count_all_results() + 1;
            /* Caso começar a ocorrer duplicidade de iddetalhe terei que 
              1 - Gravar primeiro todos os dados
              2 - Puxar a última id salvo
              3 - Realizar o count_all
              4 - Por último gravar iddetalhe com total de registro
             */
            //----------------------------------------


            $data = array(
                'dataInicial' => $dataInicial,
                'clientes_id' => $this->input->post('clientes_id'), //set_value('idCliente'),
                'usuarios_id' => $this->input->post('usuarios_id'), //set_value('idUsuario'),
                'dataFinal' => $dataFinal,
                'garantia' => set_value('garantia'),
                'descricaoProduto' => set_value('descricaoProduto'),
                'defeito' => set_value('defeito'),
                'status' => set_value('status'),
                'observacoes' => set_value('observacoes'),
                'laudoTecnico' => set_value('laudoTecnico'),
                'faturado' => 0,
                'idusumestre' => $this->session->userdata('idusumestre'),
                'iddetalhe' => $totreg
                
            );

            if (is_numeric($id = $this->os_model->add('os', $data, true))) {
                $this->session->set_flashdata('success', 'OS adicionada com sucesso, você pode adicionar produtos ou serviços a essa OS nas abas de "Produtos" e "Serviços"!');
                redirect('os/editar/' . $id);
            } else {

                $this->data['custom_error'] = '<div class="form_error"><p>An Error Occured.</p></div>';
            }
        }

        $this->data['view'] = 'os/adicionarOs';
        $this->load->view('tema/topo', $this->data);
    }

    public function adicionarAjax() {
        $this->load->library('form_validation');

        if ($this->form_validation->run('os') == false) {
            $json = array("result" => false);
            echo json_encode($json);
        } else {

            //Author: Marcos Brás--------------------- 
            $this->db->select('idusumestre');
            $this->db->from('os');
            $this->db->where('idusumestre', $this->session->userdata('idusumestre'));
            $totreg = $this->db->count_all_results() + 1;
            /* Caso começar a ocorrer duplicidade de iddetalhe terei que 
              1 - Gravar primeiro todos os dados
              2 - Puxar a última id salvo
              3 - Realizar o count_all
              4 - Por último gravar iddetalhe com total de registro
             */
            //----------------------------------------

            $data = array(
                'dataInicial' => set_value('dataInicial'),
                'clientes_id' => $this->input->post('clientes_id'), //set_value('idCliente'),
                'usuarios_id' => $this->input->post('usuarios_id'), //set_value('idUsuario'),
                'dataFinal' => set_value('dataFinal'),
                'garantia' => set_value('garantia'),
                'descricaoProduto' => set_value('descricaoProduto'),
                'defeito' => set_value('defeito'),
                'status' => set_value('status'),
                'observacoes' => set_value('observacoes'),
                'laudoTecnico' => set_value('laudoTecnico'),
                'idusumestre' => $this->session->userdata('idusumestre'),
                'iddetalhe' => $totreg
            );

            if (is_numeric($id = $this->os_model->add('os', $data, true))) {
                $json = array("result" => true, "id" => $id);
                echo json_encode($json);
            } else {
                $json = array("result" => false);
                echo json_encode($json);
            }
        }
    }

    function editar() {

        if (!$this->uri->segment(3) || !is_numeric($this->uri->segment(3))) {
            $this->session->set_flashdata('error', 'Item não pode ser encontrado, parâmetro não foi passado corretamente.');
            redirect('mapos');
        }

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';

        if ($this->form_validation->run('os') == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {

            $dataInicial = $this->input->post('dataInicial');
            $dataFinal = $this->input->post('dataFinal');

            try {

                $dataInicial = explode('/', $dataInicial);
                $dataInicial = $dataInicial[2] . '-' . $dataInicial[1] . '-' . $dataInicial[0];

                $dataFinal = explode('/', $dataFinal);
                $dataFinal = $dataFinal[2] . '-' . $dataFinal[1] . '-' . $dataFinal[0];
            } catch (Exception $e) {
                $dataInicial = date('Y/m/d');
            }

            $data = array(
                'dataInicial' => $dataInicial,
                'dataFinal' => $dataFinal,
                'garantia' => $this->input->post('garantia'),
                'descricaoProduto' => $this->input->post('descricaoProduto'),
                'defeito' => $this->input->post('defeito'),
                'status' => $this->input->post('status'),
                'observacoes' => $this->input->post('observacoes'),
                'laudoTecnico' => $this->input->post('laudoTecnico'),
                'usuarios_id' => $this->input->post('usuarios_id'),
                'clientes_id' => $this->input->post('clientes_id'),
                'idusumestre' => $this->session->userdata('idusumestre')
            );

            if ($this->os_model->edit('os', $data, 'idOs', $this->input->post('idOs')) == TRUE) {
                $this->session->set_flashdata('success', 'Os editada com sucesso!');
                redirect(base_url() . 'index.php/os/editar/' . $this->input->post('idOs'));
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um erro</p></div>';
            }
        }

        $this->data['result'] = $this->os_model->getById($this->uri->segment(3));
        $this->data['produtos'] = $this->os_model->getProdutos($this->uri->segment(3));
        $this->data['servicos'] = $this->os_model->getServicos($this->uri->segment(3));
        $this->data['anexos'] = $this->os_model->getAnexos($this->uri->segment(3));
        $this->data['view'] = 'os/editarOs';
        $this->load->view('tema/topo', $this->data);
    }

    public function visualizar() {

        if (!$this->uri->segment(3) || !is_numeric($this->uri->segment(3))) {
            $this->session->set_flashdata('error', 'Item não pode ser encontrado, parâmetro não foi passado corretamente.');
            redirect('mapos');
        }

        $this->data['custom_error'] = '';
        $this->load->model('mapos_model');
        $this->data['result'] = $this->os_model->getById($this->uri->segment(3));
        $this->data['produtos'] = $this->os_model->getProdutos($this->uri->segment(3));
        $this->data['servicos'] = $this->os_model->getServicos($this->uri->segment(3));
        $this->data['emitente'] = $this->mapos_model->getEmitente();

        $this->data['view'] = 'os/visualizarOs';
        $this->load->view('tema/topo', $this->data);
    }

    function excluir() {

        $id = $this->input->post('id');
        if ($id == null) {

            $this->session->set_flashdata('error', 'Erro ao tentar excluir OS.');
            redirect(base_url() . 'index.php/os/gerenciar/');
        }

        $this->db->where('os_id', $id);
        $this->db->delete('servicos_os');

        $this->db->where('os_id', $id);
        $this->db->delete('produtos_os');

        $this->db->where('os_id', $id);
        $this->db->delete('anexos');

        $this->os_model->delete('os', 'idOs', $id);


        $this->session->set_flashdata('success', 'OS excluída com sucesso!');
        redirect(base_url() . 'index.php/os/gerenciar/');
    }

    public function autoCompleteProduto() {

        if (isset($_GET['term'])) {
            $q = strtolower($_GET['term']);
            $this->os_model->autoCompleteProduto($q);
        }
    }

    public function autoCompleteCliente() {

        if (isset($_GET['term'])) {
            $q = strtolower($_GET['term']);
            $this->os_model->autoCompleteCliente($q);
        }
    }

    public function autoCompleteUsuario() {

        if (isset($_GET['term'])) {
            $q = strtolower($_GET['term']);
            $this->os_model->autoCompleteUsuario($q);
        }
    }

    public function autoCompleteServico() {

        if (isset($_GET['term'])) {
            $q = strtolower($_GET['term']);
            $this->os_model->autoCompleteServico($q);
        }
    }

    public function adicionarProduto() {


        $preco = $this->input->post('preco');
        $preco = str_replace(',', '.', $preco);
        
        $quantidade = $this->input->post('quantidade');
        $quantidade = str_replace(',', '.', $quantidade);
        
        $subtotal = $preco * $quantidade;
        $produto = $this->input->post('idProduto');
        
        
        
        

        //Author: Marcos Brás--------------------- 
        $this->db->select('idusumestre');
        $this->db->from('produtos_os');
        $this->db->where('idusumestre', $this->session->userdata('idusumestre'));
        $this->db->where('os_id', $this->input->post('idOsProduto'));
        $totreg = $this->db->count_all_results() + 1;
        /* Caso começar a ocorrer duplicidade de iddetalhe terei que 
          1 - Gravar primeiro todos os dados
          2 - Puxar a última id salvo
          3 - Realizar o count_all
          4 - Por último gravar iddetalhe com total de registro
         */
        //----------------------------------------

        $data = array(
            'unidade'=>$this->input->post('unidade'),
            'vlrunitario'=>$preco,
            'quantidade' => $quantidade,
            'subTotal' => $subtotal,
            'produtos_id' => $produto,
            'os_id' => $this->input->post('idOsProduto'),
            'idusumestre' => $this->session->userdata('idusumestre'),
            'iddetalhe' => $totreg
        );

        if ($this->os_model->add('produtos_os', $data) == true) {
            $sql = "UPDATE produtos set estoque = estoque - ? WHERE idProdutos = ?";
            $this->db->query($sql, array($quantidade, $produto));

            echo json_encode(array('result' => true));
        } else {
            echo json_encode(array('result' => false));
        }
    }

    function excluirProduto() {

        $ID = $this->input->post('idProduto');
        if ($this->os_model->delete('produtos_os', 'idProdutos_os', $ID) == true) {

            $quantidade = $this->input->post('quantidade');
            $produto = $this->input->post('produto');


            $sql = "UPDATE produtos set estoque = estoque + ? WHERE idProdutos = ?";

            $this->db->query($sql, array($quantidade, $produto));

            echo json_encode(array('result' => true));
        } else {
            echo json_encode(array('result' => false));
        }
    }

    public function adicionarServico() {
        $quantidade = $this->input->post('quantidadeServico');
        $quantidade = str_replace(',', '.', $quantidade);
        
        $preco= $this->input->post('precoServico');
        $preco = str_replace(',', '.', $preco);
        
        $subtotal = $quantidade*$preco;
        
        //Author: Marcos Brás--------------------- 
        $this->db->select('idusumestre');
        $this->db->from('servicos_os');
        $this->db->where('idusumestre', $this->session->userdata('idusumestre'));
        $this->db->where('os_id', $this->input->post('idOsServico'));
        $totreg = $this->db->count_all_results() + 1;
        /* Caso começar a ocorrer duplicidade de iddetalhe terei que 
          1 - Gravar primeiro todos os dados
          2 - Puxar a última id salvo
          3 - Realizar o count_all
          4 - Por último gravar iddetalhe com total de registro
         */
        //----------------------------------------

        $data = array(
            'servicos_id' => $this->input->post('idServico'),
            'os_id' => $this->input->post('idOsServico'),
            'idusumestre' => $this->session->userdata('idusumestre'),
            'iddetalhe' => $totreg,
            'vlrunitario'=>$preco,
            'quantidade'=>$quantidade,
            'subtotal'=>$subtotal
            
        );

        if ($this->os_model->add('servicos_os', $data) == true) {

            echo json_encode(array('result' => true));
        } else {
            echo json_encode(array('result' => false));
        }
    }

    function excluirServico() {
        $ID = $this->input->post('idServico');
        if ($this->os_model->delete('servicos_os', 'idServicos_os', $ID) == true) {

            echo json_encode(array('result' => true));
        } else {
            echo json_encode(array('result' => false));
        }
    }

    public function anexar() {

        //inicio anexar --------
        $idusumestre = $this->session->userdata('idusumestre');


        if (!is_dir('./assets/anexos/' . $idusumestre)) {

            mkdir('./assets/anexos/' . $idusumestre, 0777, TRUE);
        }

        $this->load->library('upload');
        $this->load->library('image_lib');
        $upload_conf = array(
            'upload_path' => realpath('./assets/anexos/' . $idusumestre),
            'allowed_types' => 'jpg|png|gif|jpeg|JPG|PNG|GIF|JPEG|pdf|PDF|cdr|CDR|docx|DOCX|txt', // formatos permitidos para anexos de os
            'max_size' => 0,
        );

        $this->upload->initialize($upload_conf);

        foreach ($_FILES['userfile'] as $key => $val) {
            $i = 1;
            foreach ($val as $v) {
                $field_name = "file_" . $i;
                $_FILES[$field_name][$key] = $v;
                $i++;
            }
        }
        unset($_FILES['userfile']);

        $error = array();
        $success = array();

        foreach ($_FILES as $field_name => $file) {
            if (!$this->upload->do_upload($field_name)) {

                $error['upload'][] = $this->upload->display_errors();
            } else {
                $upload_data = $this->upload->data();

                $success[] = $upload_data;
                $this->load->model('Os_model');
                $this->Os_model->anexar($this->input->post('idOsServico'), $upload_data['file_name'], base_url() . 'assets/anexos/' . $idusumestre . '/', 'thumb_' . $upload_data['file_name'], realpath('./assets/anexos/' . $idusumestre));
            }
        }
        if (count($error) > 0) {
            echo json_encode(array('result' => false, 'mensagem' => 'Nenhum arquivo foi anexado.'));
        } else {
            echo json_encode(array('result' => true, 'mensagem' => 'Arquivo(s) anexado(s) com sucesso .'));
        }
    }

    public function excluirAnexo($id = null) {
        if ($id == null || !is_numeric($id)) {
            echo json_encode(array('result' => false, 'mensagem' => 'Erro ao tentar excluir anexo.'));
        } else {

            $idusumestre = $this->session->userdata('idusumestre');

            $this->db->where('idAnexos', $id);
            $file = $this->db->get('anexos', 1)->row();


            if (file_exists($file->path . '/' . $file->anexo)) {

                unlink($file->path . '/' . $file->anexo);
            }



            if ($file->thumb != null) {

                if (file_exists($file->path . '/thumbs/' . $file->thumb)) {

                    unlink($file->path . '/thumbs/' . $file->thumb);
                }
            }

            $os_id = $file->os_id;

            if ($this->os_model->delete('anexos', 'idAnexos', $id) == true) {

                //echo json_encode(array('result'=> true, 'mensagem' => 'Anexo excluído com sucesso.'));

                echo '<script>alert("Anexo excluido com sucesso!");   location.href="' . base_url() . 'index.php/os/editar/' . $os_id . '";</script>';
            } else {

                //echo json_encode(array('result'=> false, 'mensagem' => 'Erro ao tentar excluir anexo.'));

                echo '<script>alert("Erro ao excluir OS!");   location.href="' . base_url() . 'index.php/os/editar/' . $os_id . '";</script>';
            }
        }
    }

    public function downloadanexo($id = null) {

        if ($id != null && is_numeric($id)) {

            $this->db->where('idAnexos', $id);
            $file = $this->db->get('anexos', 1)->row();

            $this->load->library('zip');

            $path = $file->path;

            $this->zip->read_file($path . '/' . $file->anexo);

            $this->zip->download('file' . date('d-m-Y-H.i.s') . '.zip');
        }
    }

    public function faturar() {

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';


        if ($this->form_validation->run('receita') == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {


            $vencimento = $this->input->post('vencimento');
            $recebimento = $this->input->post('recebimento');

            try {

                $vencimento = explode('/', $vencimento);
                $vencimento = $vencimento[2] . '-' . $vencimento[1] . '-' . $vencimento[0];

                if ($recebimento != null) {
                    $recebimento = explode('/', $recebimento);
                    $recebimento = $recebimento[2] . '-' . $recebimento[1] . '-' . $recebimento[0];
                }
            } catch (Exception $e) {
                $vencimento = date('Y/m/d');
            }

            //Author: Marcos Brás--------------------- 
            $this->db->select('idusumestre');
            $this->db->from('lancamentos');
            $this->db->where('idusumestre', $this->session->userdata('idusumestre'));
            $totreg = $this->db->count_all_results()+1;
            /* Caso começar a ocorrer duplicidade de iddetalhe terei que 
               1 - Gravar primeiro todos os dados
               2 - Puxar a última id salvo
               3 - Realizar o count_all
               4 - Por último gravar iddetalhe com total de registro
             */ 
            //----------------------------------------

            $data = array(
                'descricao' => set_value('descricao'),
                'valor' => $this->input->post('valor'),
                'clientes_id' => $this->input->post('clientes_id'),
                'data_vencimento' => $vencimento,
                'data_pagamento' => $recebimento,
                'baixado' => $this->input->post('recebido'),
                'cliente_fornecedor' => set_value('cliente'),
                'forma_pgto' => $this->input->post('formaPgto'),
                'tipo' => $this->input->post('tipo'),
                'idusumestre' => $this->session->userdata('idusumestre'),
                'iddetalhe'=>$totreg
            );

            if ($this->os_model->add('lancamentos', $data) == TRUE) {

                $os = $this->input->post('os_id');

                $this->db->set('faturado', 1);
                $this->db->set('valorTotal', $this->input->post('valor'));
                $this->db->where('idOs', $os);
                $this->db->update('os');

                $this->session->set_flashdata('success', 'OS faturada com sucesso!');
                $json = array('result' => true);
                echo json_encode($json);
                die();
            } else {
                $this->session->set_flashdata('error', 'Ocorreu um erro ao tentar faturar OS.');
                $json = array('result' => false);
                echo json_encode($json);
                die();
            }
        }

        $this->session->set_flashdata('error', 'Ocorreu um erro ao tentar faturar OS.');
        $json = array('result' => false);
        echo json_encode($json);
    }

}
