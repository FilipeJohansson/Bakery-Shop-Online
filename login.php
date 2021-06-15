<?php
    include 'conexao.php';
    include 'action_login.php';

    session_start();

    if(isset($_SESSION['login'])) {
        header("Location: index.php");
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link href="css/login.css" rel="stylesheet">
    <link href="css/bootstrap.css" rel="stylesheet">
    <meta charset="utf-8"/>
</head>
<body>
    <div class="login-box">
        <img src="./imagens/Logos/logo.png" class="img-fluid">
        <?php
            if(isset($_POST['entrar'])) {
              validarLogin();
            }
        ?>
        <form method="POST">
            <input class="form-control" type="email" name="email" placeholder="Email" required autofocus>
            <input class="form-control" type="password" name="senha" placeholder="Senha" required>
            <button class="btn btn-md btn-primary" type="submit" name="entrar">Entrar</button>
        </form>
        <br>
        <h5>Ainda não tem uma conta? </h5><a href="./registrar.php">Criar conta.</a>
    </div>	
</body>
</html>