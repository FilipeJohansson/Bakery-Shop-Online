<?php

    function validarLogin() {
        $email = $_POST['email'];
        
        $hash = "";
        
        global $pdo;

        $query = $pdo->prepare("SELECT * FROM usuarios WHERE email='$email'");
        $data = $query->execute();

        while($login = $query->fetch(PDO::FETCH_ASSOC)) {
            $hash = $login['senha'];
        }

        if(password_verify($_POST["senha"], $hash)) {
            $_SESSION['login'] = $email;
            header("Location: index.php");
        } else {
            echo "<h5 class='redText'>Usuário ou senha incorretos.</h5>";
        }

    }
    
?>