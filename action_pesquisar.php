<?php

    function mostrarPesquisa() {
        $op = null;
        
        if(isset($_GET['op'])) {
            if($_GET['op'] == 'maisvendidos') {
                global $pdo;

                $query = $pdo->prepare("SELECT codProd, SUM(quantidade) FROM vendas GROUP BY codProd ORDER BY SUM(quantidade) DESC LIMIT 4;");
                $query->execute();
                        
                while($_produto = $query->fetch(PDO::FETCH_ASSOC)) {
                    $prod = $_produto['codProd'];
                    $_query = $pdo->prepare("SELECT * FROM produtos WHERE codigo='$prod';");
                    
                    if($_query->execute()) {
                        while($produto = $_query->fetch(PDO::FETCH_ASSOC)) {
                            $codigo = $produto['codigo'];
                            $nome = $produto['nome'];
                            $tipo = $produto['tipo'];
                            $estoque = $produto['estoque'];
                            $valor = $produto['valor'];

                            echo "
                                <div class='media bloco'>
                                    <div class='media-body'>
                                        <h4 class='title'>
                                            ". $nome ."
                                            <span class='pull-right telefone'>R$". $valor ."</span>
                                        </h4>
                                        <span class='pull-right'>
                                            <a href='./action_carrinho.php?acao=add&cod=". $codigo ."'>Comprar</a>
                                        </span>
                                        <p class='summary'>". $tipo ."</p>

                                    </div>
                                </div>";
                        }

                    }

                }
                
            }
            
            if($_GET['op'] == 'menosvendidos') {
                global $pdo;

                $query = $pdo->prepare("SELECT codProd, SUM(quantidade) FROM vendas GROUP BY codProd ORDER BY SUM(quantidade) ASC LIMIT 4;");
                $query->execute();
                        
                while($_produto = $query->fetch(PDO::FETCH_ASSOC)) {
                    $prod = $_produto['codProd'];
                    $_query = $pdo->prepare("SELECT * FROM produtos WHERE codigo='$prod';");
                    
                    if($_query->execute()) {
                        while($produto = $_query->fetch(PDO::FETCH_ASSOC)) {
                            $codigo = $produto['codigo'];
                            $nome = $produto['nome'];
                            $tipo = $produto['tipo'];
                            $estoque = $produto['estoque'];
                            $valor = $produto['valor'];

                            echo "
                                <div class='media bloco'>
                                    <div class='media-body'>
                                        <h4 class='title'>
                                            ". $nome ."
                                            <span class='pull-right telefone'>R$". $valor ."</span>
                                        </h4>
                                        <span class='pull-right'>
                                            <a href='./action_carrinho.php?acao=add&cod=". $codigo ."'>Comprar</a>
                                        </span>
                                        <p class='summary'>". $tipo ."</p>

                                    </div>
                                </div>";
                        }

                    }

                }
                
            }
            
        } else {
            $pesquisa = $_POST['pesquisa'];
        
            global $pdo;

            $query = $pdo->prepare("SELECT * FROM produtos WHERE nome LIKE '%$pesquisa%' OR tipo LIKE '%$pesquisa%' OR estoque='$pesquisa';");

            if($query->execute()) {
                while($produto = $query->fetch(PDO::FETCH_ASSOC)) {
                    $codigo = $produto['codigo'];
                    $nome = $produto['nome'];
                    $tipo = $produto['tipo'];
                    $estoque = $produto['estoque'];
                    $valor = $produto['valor'];

                echo "
                    <div class='media bloco'>
                        <div class='media-body'>
                            <h4 class='title'>
                                ". $nome ."
                                <span class='pull-right telefone'>R$". $valor ."</span>
                            </h4>
                            <span class='pull-right'>
                                <a href='./action_carrinho.php?acao=add&cod=". $codigo ."'>Comprar</a>
                            </span>
                            <p class='summary'>". $tipo ."</p>

                        </div>
                    </div>";
                }

            }
            
        }
        
    }

?>