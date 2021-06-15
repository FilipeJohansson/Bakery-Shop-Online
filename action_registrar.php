<?php

    function validarRegistro() {
        $pass_hash = password_hash($_POST["senha"], PASSWORD_DEFAULT);

        $nome = $_POST['nome'];
        $email = $_POST['email'];
        
        global $pdo;
        
        $email_check = $pdo->prepare("SELECT email FROM usuarios WHERE email='$email';");
        $email_check->execute();
        $do_email_check = $email_check->rowCount();
        
        if($do_email_check >= 1){
                echo "<h3>Email já registrado, clique <a href='login.php'>aqui</a> para entrar.</h3>";
                
        }elseif(strlen($nome) > 50){
                echo "<h3>Nome muito grande. Máximo 50 caracteres.</h3>";
                
        }elseif(strlen($email) > 50){
                echo "<h3>Email muito grande. Máximo 50 caracteres.</h3>";
                
        }elseif(strlen($senha) > 60){
                echo "<h3>Senha muito grande. Máximo 60 caracteres.</h3>";
                
        }else{
            $query = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, adm) VALUES ('$nome', '$email', '$pass_hash', '0');");
            $data = $query->execute();

            if($data) {
                $_SESSION['login'] = $email;
                header("Location: index.php");
            } else {
                echo "<h5 class='redText'>Ocorreu algo de errado.</h5>";
            }

        }
        
    }

?>