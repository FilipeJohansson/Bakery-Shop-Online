<?php

    session_start();
    
    if(!isset($_SESSION['carrinho'])){ 
        $_SESSION['carrinho'] = array();
        $_SESSION['totalCarrinho'] = 0;
    }
    
    if(isset($_GET['acao'])) {
        if($_GET['acao'] == 'add') {
            $cod = intval($_GET['cod']);
            
            if(!isset($_SESSION['carrinho'][$cod])) {
                $_SESSION['carrinho'][$cod] = 1;
                
            } else {
                $_SESSION['carrinho'][$cod] += 1; 
                
            }
            
            header("Location: carrinho.php");
        }

        if($_GET['acao'] == 'del') {
            $cod = intval($_GET['cod']); 
            
            var_dump($_SESSION['carrinho']);
            
            if(isset($_SESSION['carrinho'][$cod])) {
                unset($_SESSION['carrinho'][$cod]); 
                
            }
            
            header("Location: carrinho.php");
            
        }

        if($_GET['acao'] == 'up') {
            if(is_array($_POST['prod'])) {
                foreach($_POST['prod'] as $cod => $qtd) {
                    $cod  = intval($cod);
                    $qtd = intval($qtd);
                    
                    if(!empty($qtd) || $qtd <> 0) {
                        $_SESSION['carrinho'][$cod] = $qtd;
                        
                    } else {
                        unset($_SESSION['carrinho'][$cod]);
                        
                    }
                }
            }
            
            header("Location: carrinho.php");
            
        }
        
        if($_GET['acao'] == 'comprar') {
            
        }

    }
    
    function mostrarCarrinho() {
        if(count($_SESSION['carrinho']) == 0) {
            echo '
                    <div class="row">
                        <div class="col-md-4">
                        </div>

                        <div class="col-md-4 media bloco">
                            <div class="col-md-12">
                                <center><h5>Não há produtos no carrinho</h5></center>
                            </div>
                        </div>

                        <div class="col-md-4">
                        </div>
                    </div>';
        } else {
            include 'conexao.php';
            $total = 0;
            
            foreach($_SESSION['carrinho'] as $cod => $qtd) {
                global $pdo;

                $produtos = $pdo->prepare("SELECT * FROM produtos WHERE codigo= '$cod'");
                $produtos->execute();
                $produto = $produtos->fetch(PDO::FETCH_ASSOC);

                $nome  = $produto['nome'];
                $preco = number_format($produto['valor'], 2, ',', '.');
                $sub   = number_format($produto['valor'] * $qtd, 2, ',', '.');
                $total += $produto['valor'] * $qtd;
                
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
                                    <input type="number" min="1" style="width:40px;" name="prod['.$cod.']" value="'.$qtd.'" />
                                </div>
                                <div class="col-md-2">
                                    <h5>R$ '.$preco.'</h5>
                                </div>
                                <div class="col-md-2">
                                    <h5>R$ '.$sub.'</h5>
                                </div>
                                <div class="col-md-2">
                                    <h5><a href="action_carrinho.php?acao=del&cod='.$cod.'">Remover</a></h5>
                                </div>
                            </div>

                            <div class="col-md-4">
                            </div>
                        </div>';
            }
                
            $_total = number_format($total, 2, ',', '.');
            $_SESSION['totalCarrinho'] = $_total; 

            echo '
                <div class="row">
                    <div class="col-md-4">
                    </div>

                    <div class="col-md-4">
                        <div class="col-md-4">
                            <input type="submit" value="Atualizar Carrinho" />
                            </form>
                        </div>
                        <div class="col-md-2">
                        </div>
                        <div class="col-md-2">
                            <h5><b>Total: </b></h5>
                        </div>
                        <div class="col-md-2">
                            <h5>R$ '.$_SESSION['totalCarrinho'].'</h5>
                        </div>
                        <div class="col-md-2">
                            <a href="finalizarCarrinho.php"><input class="btn btn-default" style="width:100px;" value="Comprar" /></a>
                        </div>
                    </div>

                    <div class="col-md-4">
                    </div>';
        }
        
    }
    
    function finalizarCarrinho() {
        $isAvailable = TRUE;
        
        if(count($_SESSION['carrinho']) == 0) {
            echo '
                <div class="row">
                    <div class="col-md-4">
                    </div>

                    <div class="col-md-4 media bloco">
                        <div class="col-md-12">
                            <center><h5>Não há produtos no carrinho</h5></center>
                        </div>
                    </div>

                    <div class="col-md-4">
                    </div>
                </div>';
        } else {
            include 'conexao.php';
            $total = $_SESSION['totalCarrinho'];
            
            $salt = microtime();
            $idCompra = md5(sha1(date(DATE_ATOM, mktime(0, 0, 0, 7, 1, 2000)) . buscarNome() . $salt));
            
            $email = $_SESSION['login'];
            $query = $pdo->prepare("SELECT ID FROM usuarios WHERE email='$email'");
            $query->execute();
            
            $idUser = null;
            
            $idUser = $query->fetch(PDO::FETCH_ASSOC);
            $idUser = $idUser['ID'];

            foreach($_SESSION['carrinho'] as $cod => $qtd) {
                global $pdo;
                
                $produtos = $pdo->prepare("SELECT * FROM produtos WHERE codigo= '$cod'");
                $produtos->execute();
                $produto = $produtos->fetch(PDO::FETCH_ASSOC);

                $nome  = $produto['nome'];
                $preco = number_format($produto['valor'], 2, ',', '.');
                $sub   = number_format($produto['valor'] * $qtd, 2, ',', '.');
                $total += $produto['valor'] * $qtd;
                $valor = $produto['valor'];  

                $produtos = $pdo->prepare("SELECT estoque FROM produtos WHERE codigo= '$cod'");
                $produtos->execute();
                $produto = $produtos->fetch(PDO::FETCH_ASSOC);

                $estoque = $produto['estoque'];
                $estoque -= $qtd;

                $produtos = $pdo->prepare("UPDATE produtos SET estoque='$estoque' WHERE codigo= '$cod'");
                $produtos->execute();
                
                $vendas = $pdo->prepare("INSERT into vendas (idCompra, idUser, codProd, nomeProd, quantidade, valorProd, ativo) VALUES ('$idCompra', '$idUser', '$cod', '$nome', '$qtd', '$valor', '1');");
                $vendas->execute();

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
                                <h5>'.$qtd.'</h5>
                            </div>
                            <div class="col-md-2">
                                <h5>R$ '.$preco.'</h5>
                            </div>
                            <div class="col-md-2">
                                <h5>R$ '.$sub.'</h5>
                            </div>
                            <div class="col-md-2">
                            </div>
                        </div>

                        <div class="col-md-4">
                        </div>
                    </div>';                

            }
            
            echo '
                <div class="row">
                    <div class="col-md-4">
                    </div>

                    <div class="col-md-4">
                        <div class="col-md-4">
                        </div>
                        <div class="col-md-2">
                        </div>
                        <div class="col-md-2">
                            <h5><b>Total: </b></h5>
                        </div>
                        <div class="col-md-2">
                            <h5>R$ '.$_SESSION['totalCarrinho'].'</h5>
                        </div>
                        <div class="col-md-2">
                        </div>
                    </div>

                    <div class="col-md-4">
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <center>
                            <h4>Compra realizada com sucesso</h4>
                        </center>
                    </div>
                </div>';
                
                $_SESSION['carrinho'] = null;
                $_SESSION['totalCarrinho'] = null;
        
        }
        
    }

?>