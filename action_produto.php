<?php

    session_start();
    include 'conexao.php';
    include 'action_geral.php';

    if(verificarAdmin()) {
        function validarProduto() {
            $nome = $_POST['prodNome'];
            $tipo = $_POST['prodTipo'];
            $valor = number_format($_POST['prodValor'], 2, '.', ',');
            $estoque = $_POST['prodEstoque'];
            
            global $pdo;
        
            $query = $pdo->prepare("SELECT * FROM produtos WHERE nome='$nome'");
            $query->execute();
            $_query = $query->rowCount();
        
            if($_query >= 1) {
                echo "<h5>Produto já cadastrado no banco. Cadastre um produto com o nome diferente.</h5>";
                
            } else {
                $produtos = $pdo->prepare("INSERT INTO produtos (nome, tipo, valor, estoque) VALUES ('$nome', '$tipo', '$valor', '$estoque');");
            
                if($produtos->execute()) {
                    header("Location: index.php");

                }
                
            }
            
        }
        
        function mostrarProduto($cod) {
            global $pdo;
        
            $produtos = $pdo->prepare("SELECT * FROM produtos WHERE codigo='$cod'");
            $produtos->execute();

            while($produto = $produtos->fetch(PDO::FETCH_ASSOC)) {
                $nome = $produto['nome'];
                $tipo = $produto['tipo'];
                $estoque = $produto['estoque'];
                $valor = $produto['valor'];
                
                echo "
                <form method='POST' action='action_produto.php?acao=editar&cod=" . $cod . "'>
                    <h5><b>Editando: </b>" . $nome . "</h5>
                    <input class='form-control' type='text' name='prodTipo' placeholder='Tipo do produto' value='" . $tipo . "' required autofocus>
                    <input class='form-control' type='number' min='1' step='any' name='prodValor' placeholder='Valor do produto' value='" . $valor . "' required>
                    <input class='form-control' type='number' min='1' name='prodEstoque' placeholder='Quantidade em estoque' value='" . $estoque . "' required>
                    <div class='col-md-6'>
                        <button class='btn btn-md btn-primary' type='submit' name='editar'>Editar Produto</button>
                    </div>
                    <div class='col-md-6'>
                        <a href='./index.php' class='btn btn-md'>Cancelar</a>
                    </div>                 
                </form>";
            }
            
        }
        
        if(isset($_GET['acao'])) {
            if($_GET['acao'] == 'remover') {
                $cod = intval($_GET['cod']);
                
                global $pdo;
        
                $produtos = $pdo->prepare("DELETE FROM produtos WHERE codigo='$cod';");

                if($produtos->execute()) {
                    header("Location: index.php");

                }
                
            }
            
            if($_GET['acao'] == 'editar') {
                $cod = intval($_GET['cod']);
                
                $tipo = $_POST['prodTipo'];
                $valor = number_format($_POST['prodValor'], 2, '.', ',');
                $estoque = $_POST['prodEstoque'];

                global $pdo;

                $produtos = $pdo->prepare("UPDATE produtos SET tipo='$tipo', valor='$valor', estoque='$estoque' WHERE codigo='$cod';");

                if($produtos->execute()) {
                    header("Location: index.php");

                }
                
            }            

        }
    } else {
        header("Location: index.php");
        
    }    

?>