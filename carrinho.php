<?php
    include 'conexao.php';
    include 'action_carrinho.php';
    include 'action_geral.php';
    
    if(!isset($_SESSION['login'])) {
        header("Location: login.php");
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Carrinho</title>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/index.css" rel="stylesheet">
    <meta charset="utf-8"/>
</head>
<body>
    <div class="navbar-wrapper">
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation	</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">
                        <img width="30px" class="img-responsive logo" alt="Logo" src="./Imagens/Logos/Logo.png">
                    </a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li><a href="./index.php">Produtos</a></li>
                        <li class="active"><a href="./carrinho.php">Carrinho (<?php 
                            
                                quantidadeCarrinho();
                            
                            ?>)</a></li>
                        <li><a href="./compras.php">Compras</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a>Bem-vindo, 
                            <?php
                                echo buscarNome();

                                if(verificarAdmin()) {
                                    echo " [ADMIN]";                                
                                }
                            ?>
                        </a></li>
                        <li class="active"><a href="./action_sair.php">Sair</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
    
    <br><br><br><br><br>
    
    <div class="row">
        <div class="col-md-4">
        </div>

        <div class="col-md-4">
            <div class="col-md-4">
                <h5><b>Produto</b></h5>
            </div>
            <div class="col-md-2">
                <h5><b>Quantidade</b></h5>
            </div>
            <div class="col-md-2">
                <h5><b>Preço Unit.</b></h5>
            </div>
            <div class="col-md-2">
                <h5><b>Subtotal</b></h5>
            </div>
            <div class="col-md-2">
                <h5><b>Remover</b></h5>
            </div>
        </div>

        <div class="col-md-4">
        </div>
    </div>

    <?php

        mostrarCarrinho();

    ?>
</body>
</html>