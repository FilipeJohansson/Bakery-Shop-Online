<?php
    include 'conexao.php';
    include 'action_registrar.php';

    session_start();

    if(isset($_SESSION['login'])) {
        header("Location: index.php");
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Registrar</title>
    <link href="css/login.css" rel="stylesheet">
    <link href="css/bootstrap.css" rel="stylesheet">
    <meta charset="utf-8"/>
</head>
<body>
    <div class="login-box">
        <img src="./imagens/Logos/logo.png" class="img-fluid">
        <?php
            if(isset($_POST['registrar'])) {
                validarRegistro();
            }
        ?>
            <form method="POST">
                <input class="form-control" type="text" name="nome" placeholder="Nome" required autofocus>
                <input class="form-control" type="email" name="email" placeholder="Email" required>
                <input class="form-control" type="password" name="senha" placeholder="Senha" required>
                <button class="btn btn-md btn-primary" type="submit" name="registrar">Criar conta</button>
            </form>
            <br>
            <h5>Já tem uma conta? </h5><a href="./login.php">Entrar na conta.</a>
    </div>	
</body>
</html>