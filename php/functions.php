<?php

function create_filter_dropdown_menu($marca_list) {
    echo '<div class="barra-filtros">';
    
    echo '<div class="filter-menu-block"';
    echo '<label for="txt-nome-carro">Nome:</label>';
    echo '<input type="text" id="txt-nome-carro" class="nome-carro-input" placeholder="Nome do carro">';
    echo '</div>';

    echo '<div class="filter-menu-block"';
    echo '<label for="dropdown-marca">Marca:</label>';
    echo '<select name="marca" id="dropdown-marca" class="filter-dropdown">';
    echo '<option value="">Marca</option>';
    while ($marca = mysqli_fetch_assoc($marca_list)) { 
        ?><option value="<?=strtolower($marca['nome'])?>"><?=$marca['nome']?></option><?php
    }
    echo '</select></div>';
    
    echo '<div class="filter-menu-block"';
    echo '<label for="dropdown-ano">Ordenar por:</label>';
    echo '<select name="ano" id="dropdown-ano" class="filter-dropdown">';
    echo '<option value="">Ano</option>';
    echo '<option value="ordenar-mais-novo">Mais novo</option>';
    echo '<option value="ordenar-mais-antigo">Mais antigo</option>';
    echo '</select></div>';
    
    echo '<div class="filter-menu-block"';
    echo '<label for="dropdown-preco">Ordenar por:</label>';
    echo '<select name="preco" id="dropdown-preco" class="filter-dropdown">';
    echo '<option value="">Preço</option>';
    echo '<option value="ordenar-menor-preco">Menor preço</option>';
    echo '<option value="ordenar-maior-preco">Maior preço</option>';
    echo '</select></div>';

    echo '<button class="filter-menu-block" type="button">Filtrar</button>';
    echo '</div>';
}

function create_vehicle_block($veiculo, $marca, $foto_principal) {
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
        <img class="veiculo-img" src="<?="img/veiculos/" . $foto_principal['diretorio_fotos'] . $foto_principal['foto_principal']?>">
        <h3 class="veiculo-titulo"><?=$veiculo['nome']?></h3>
        <hr class="inner-hr">
        <div class="bloco-veiculo-descricao">
            <p class="veiculo-descricao">Marca: <?=$marca['nome']?></p>
            <p class="veiculo-descricao">Ano: <?=$ano?></p>
            <p class="veiculo-descricao">Preço: <?=$preco?></p>
        </div>
    </div>
    <?php
}
