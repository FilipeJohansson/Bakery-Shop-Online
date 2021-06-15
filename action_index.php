<?php

    function mostrarProdutos() {        
        global $pdo;
        
        $produtos = $pdo->prepare("SELECT * FROM produtos ORDER BY codigo");
        $produtos->execute();
        
        while($produto = $produtos->fetch(PDO::FETCH_ASSOC)) {
            $codigo = $produto['codigo'];
            $nome = $produto['nome'];
            $tipo = $produto['tipo'];
            $estoque = $produto['estoque'];
            $valor = $produto['valor'];
            $valor = number_format($valor, 2, ',', '.');
            
            if(verificarAdmin()) {
                echo "
                <div class='media bloco'>
                    <div class='media-body'>
                        <h4 class='title'>
                            ". $nome ."
                            <span class='pull-right telefone'>R$". $valor ."</span>
                        </h4>
                        <p class='pull-right summary'><b>Estoque:</b> ". $estoque ."</p>
                        <p class='summary'>". $tipo ."</p>
                        <span class='pull-right'>
                            <a href='./action_carrinho.php?acao=add&cod=". $codigo ."'>Comprar</a>
                        </span>
                        <a href='./action_produto.php?acao=remover&cod=". $codigo ."'>Remover</a>
                           |
                        <a href='./editarProduto.php?cod=". $codigo ."'>Editar</a>

                    </div>
                </div>";
                
            } else {
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
    
    function mostrarOptionsAdm() {
        if(verificarAdmin()) {
            echo "
            <div class='row'>
                <div class='col-md-4'>
                </div>
                <div class='col-md-4 bloco'>
                    <a href='./produto.php'>
                        <center>Adicionar Produto</center>
                    </a>
                </div>
            </div>";
                
        }
        
    }

?>
