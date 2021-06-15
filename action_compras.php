<?php

    include 'conexao.php';
    include 'action_geral.php';

    session_start();
    
    $op = null;
    
    if(isset($_GET['op']) != null) {
        if($_GET['op'] == 'cancelar') {
            if(!verificarAdmin()) {
                header("Location: compras.php");
                
            } else {
                $idCompra = $_GET['id'];
                
                $compras = $pdo->prepare("SELECT * FROM vendas WHERE idCompra='$idCompra'");
                $compras->execute();
                
                while($_compras = $compras->fetch(PDO::FETCH_ASSOC)) {
                    $quantidade = $_compras['quantidade'];
                    $codProd = $_compras['codProd'];
                    
                    $produtos = $pdo->prepare("SELECT * FROM produtos WHERE codigo='$codProd';");
                    $produtos->execute();
                    $_produto = $produtos->fetch(PDO::FETCH_ASSOC);
                    
                    $estoque = $_produto['estoque'];
                    $estoque += $quantidade;
                    
                    $query = $pdo->prepare("UPDATE produtos SET estoque='$estoque' WHERE codigo='$codProd'");
                    $query->execute();
                    
                }
                
                $compras = $pdo->prepare("UPDATE vendas SET ativo='0' WHERE idCompra='$idCompra'");
                $compras->execute();

                header("Location: compras.php");
                
            }
            
        }
        
        if($_GET['op'] == 'excluir') {
            if(!verificarAdmin()) {
                header("Location: compras.php");
                
            } else {
                $idCompra = $_GET['id'];
            
                $compras = $pdo->prepare("DELETE FROM vendas WHERE idCompra='$idCompra'");
                $compras->execute();

                header("Location: compras.php");
                
            }
            
        }
    }
    
    function mostrarCompras() {        
        global $pdo;
        
        $email = $_SESSION['login'];
        
        $query = $pdo->prepare("SELECT ID FROM usuarios WHERE email='$email';");
        $query->execute();
        
        $idUser = $query->fetch(PDO::FETCH_ASSOC);
        $idUser = $idUser['ID'];
        
        if(verificarAdmin()) {
            $query = $pdo->prepare("SELECT * FROM vendas GROUP BY idCompra;");
            $query->execute();
            
        } else {
            $query = $pdo->prepare("SELECT * FROM vendas WHERE idUser='$idUser' GROUP BY idCompra;");
            $query->execute();
            
        }

        $total = 0;
        
        while($compra = $query->fetch(PDO::FETCH_ASSOC)) {
            global $pdo;

            $idCompra = $compra['idCompra'];
            $ativo = $compra['ativo'];
            
            $compras = $pdo->prepare("SELECT * FROM vendas WHERE idCompra='$idCompra'");
            $compras->execute();
            
            echo '<div class="row">
                    <div class="col-md-4">
                    </div>

                    <div class="col-md-4">
                        <div class="col-md-8">
                            <h5><b>ID Compra: </b>'.$idCompra.'</h5>
                        </div>';
            
            if(verificarAdmin()) {                
                if($ativo == 1) {
                    echo '
                        <div class="col-md-2">
                            <h5><a href=action_compras.php?op=cancelar&id='.$idCompra.'>Cancelar venda</a></h5>
                        </div>';
                    
                }
                
                echo '
                    <div class="col-md-2">
                            <h5><a href=action_compras.php?op=excluir&id='.$idCompra.'>Excluir venda</a></h5>
                        </div>
                    </div>';
                
            } else {
                echo '
                </div>';
                
            }
            
            echo '
                    <div class="col-md-4">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                    </div>

                    <div class="col-md-4">
                        <div class="col-md-4">
                            <h5><b>Produtos</b></h5>
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
                            <h5><b>Venda Ativa?</b></h5>
                        </div>
                    </div>

                    <div class="col-md-4">
                    </div>
                </div>';
            
            while($_compras = $compras->fetch(PDO::FETCH_ASSOC)) {
                $codProd = $_compras['codProd'];
                $quantidade = $_compras['quantidade'];
                $valorProd = number_format($_compras['valorProd'], 2, ',', '.');
                $sub   = number_format($_compras['valorProd'] * $quantidade, 2, ',', '.');
                $total += $_compras['valorProd'] * $quantidade;
                $ativo = $_compras['ativo'];
                $nome  = $_compras['nomeProd'];
                
                echo '
                <form action="action_carrinho.php?acao=up" method="post">
                    <div class="row">
                        <div class="col-md-4">
                        </div>

                        <div class="col-md-4 media bloco">
                            <div class="col-md-4">
                                <h5>'.$nome.'</h5>
                            </div>
                            <div class="col-md-2">
                                <h5>'.$quantidade.'</h5>
                            </div>
                            <div class="col-md-2">
                                <h5>R$ '.$valorProd.'</h5>
                            </div>
                            <div class="col-md-2">
                                <h5>R$ '.$sub.'</h5>
                            </div>';
                
                if($ativo == 1) {
                   echo '<div class="col-md-2">
                                <h5>Sim</h5>
                            </div>
                        </div>'; 
                } else {
                    echo '<div class="col-md-2">
                                <h5>Não</h5>
                            </div>
                        </div>'; 
                    
                }
                            
                echo '
                        <div class="col-md-4">
                        </div>
                    </div>';
                
            }
            
        }
                
    }
    
?>