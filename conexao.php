<?php


    $connetion = "mysql:host=localhost;dbname=testeSoftware";

    try {
        $pdo = new PDO($connetion, "root", "") or die();
    } catch (PDOException $e) {
        if($e->getCode() == 2002){
                echo "Não foi possível conectar ao servidor.";
        }else if($e->getCode() == 1049){
                echo "Não foi possível conectar ao banco de dados.";
        }else if($e->getCode() == 1045){
                echo "Não foi possivel fazer a conexão.";
        }else{
                echo $e->getMessage();
        }
    }

?>