<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * Conexão via mysqli
 */


function DBClose($mysqli) {
    $mysqli->close();
}

function DBConnect() {

   
   
   //marcosbras.com
   $servidor = 'localhost';
   $usuario = 'marcosbr_admin';
   $senha = 'NnyiTUhdF%#y';
   $banco = 'marcosbr_dberp';
   
   //executar no local
   //$servidor = '127.0.0.1';
   //$usuario = 'root';
   //$senha = 'UTSETaoiom';
   //$banco = 'sistema';
   
   //OpenShift
   //$servidor = $_ENV['OPENSHIFT_MYSQL_DB_HOST'];
   //$usuario = $_ENV['OPENSHIFT_MYSQL_DB_USERNAME'];
   //$senha = $_ENV['OPENSHIFT_MYSQL_DB_PASSWORD'];
   //$banco = $_ENV['OPENSHIFT_APP_NAME'];

       $mysqli = new mysqli($servidor, 
                             $usuario, 
                             $senha, 
                             $banco);

        /* verifica conexao */
        if (mysqli_connect_errno()) {
            printf("Conexão falhou %s\n", mysqli_connect_error());
            exit();
        }

         $mysqli->set_charset(DB_CHARSET);

    return $mysqli;
}



