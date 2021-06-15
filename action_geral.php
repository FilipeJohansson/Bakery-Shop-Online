<?php

    function buscarNome() {
        $email = $_SESSION['login'];
        
        global $pdo;

        $query = $pdo->prepare("SELECT nome FROM usuarios WHERE email='$email'");
        $query->execute();

        while($login = $query->fetch(PDO::FETCH_ASSOC)) {
            return $login['nome'];
        }
        
    }
    
    function verificarAdmin() {
        $nome = buscarNome();
        $adm;
        
        global $pdo;

        $query = $pdo->prepare("SELECT adm FROM usuarios WHERE nome='$nome'");
        $query->execute();
        
        while($login = $query->fetch(PDO::FETCH_ASSOC)) {
            $adm = $login['adm'];
        }
        
        if($adm == 1) {
            return TRUE;
            
        } else {
            return FALSE;
            
        }
        
    }
    
    function quantidadeCarrinho() {
        $contagem = 0;
        
        if($_SESSION['carrinho'] != null || $_SESSION['carrinho'] != 0) {
            foreach($_SESSION['carrinho'] as $cod => $qtd){
                $contagem += $qtd;

            }
        }        
        
        echo $contagem;
        
    }

?>
