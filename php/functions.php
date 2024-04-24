<?php

function create_vehicle_block($veiculo) {
    if ($veiculo['ano'] == NULL) {
        $ano = "N/D";
    } else {
        $ano = $veiculo['ano'];
    }
    
    if ($veiculo['preco'] == 0) {
        $preco = "a negociar";
    } else {
        $preco = "R$ " . number_format($veiculo['preco'], 2, ',', '.');
    }

    ?>
    <div class="bloco-veiculo">
        <img class="veiculo-img" src="<?=$veiculo['caminho_foto']?>">
        <h3 class="veiculo-titulo"><?=$veiculo['nome']?></h3>
        <hr class="inner-hr">
        <div class="bloco-veiculo-descricao">
            <p class="veiculo-descricao">Marca: <?=$veiculo['marca']?></p>
            <p class="veiculo-descricao">Ano: <?=$ano?></p>
            <p class="veiculo-descricao">Preço: <?=$preco?></p>
        </div>
    </div>
    <?php
}