<?php

/** Creates a filter dropdown menu and returns it */
function createFilterDropdownMenu($brandList) {
    $content = '';
    
    $content .=
    '<div class="barra-filtros">
        <div class="filter-menu-block"
            <label for="txt-nome-carro">Nome:</label>
            <input type="text" id="txt-nome-carro" class="nome-carro-input" placeholder="Nome do carro">
        </div>

        <div class="filter-menu-block"
            <label for="dropdown-marca">Marca:</label>
            <select name="marca" id="dropdown-marca" class="filter-dropdown">
                <option value="">Marca</option>';

    while ($brand = mysqli_fetch_assoc($brandList)) { 
        $content .= '<option value="' . strtolower($brand['nome']) . '">' . $brand['nome'] . '</option>';
    }

    $content .=
    '      </select>
        </div>
    
        <div class="filter-menu-block"
            <label for="dropdown-ano">Ordenar por:</label>
            <select name="ano" id="dropdown-ano" class="filter-dropdown">
                <option value="">Ano</option>
                <option value="ordenar-mais-novo">Mais novo</option>
                <option value="ordenar-mais-antigo">Mais antigo</option>
            </select>
        </div>
        
        <div class="filter-menu-block"
            <label for="dropdown-preco">Ordenar por:</label>
            <select name="preco" id="dropdown-preco" class="filter-dropdown">
                <option value="">Preço</option>
                <option value="ordenar-menor-preco">Menor preço</option>
                <option value="ordenar-maior-preco">Maior preço</option>
            </select>
        </div>

        <button class="filter-menu-block" type="button">Filtrar</button>
    </div>';

    return $content;
}

/** Creates a vehicle block and returns it */
function createVehicleBlock($vehicle, $brand, $mainPhoto) {
    if ($vehicle['ano'] == NULL) {
        $year = "N/D";
    } else {
        $year = $vehicle['ano'];
    }
    
    if ($vehicle['preco'] == 0) {
        $price = "a negociar";
    } else {
        $price = "R$ " . number_format($vehicle['preco'], 2, ',', '.');
    }

    ?>
    <a class="bloco-veiculo" href="#">
        <img class="veiculo-img" src="<?="img/veiculos/" . $mainPhoto['diretorio_fotos'] . $mainPhoto['foto_principal']?>">
        <h3 class="veiculo-titulo"><?=$vehicle['nome']?></h3>
        <hr class="inner-hr">
        <div class="bloco-veiculo-descricao">
            <p class="veiculo-descricao">Marca: <?=$brand['nome']?></p>
            <p class="veiculo-descricao">Ano: <?=$year?></p>
            <p class="veiculo-descricao">Preço: <?=$price?></p>
        </div>
    </a>
    <?php
}

/** Creates a vehicle grid ans returns it */
function createVehicleGrid($db, $vehicleList) {
    $content = '';

    $content .= '<div class="grade-ofertas">';

    $infoList = [];
    while ($vehicle = mysqli_fetch_assoc($vehicleList)) { // Generating blocks for the vehicle grid
        $id = $vehicle['id'];

        // SQL preparation-injection-execution -> brand
        try {
            $brand = $db->prepareSelectQuery(
                "vehicles AS v", [
                    "valuesSelected" => "m.nome",
                    "innerJoin"      => ["models AS l ON l.id = v.id_linha", "brands AS m ON m.id = l.id_marca WHERE v.id = ?"]
                ]
            );
            $brand = $db->sqlInjection($brand, 'i', $id);
            $brand = $db->sqlExecuteSelection($brand);
        } catch (SQLPreparationException $e) {
            echo '<script>console.log("' . $e->getMessage() . '")</script>';
        } catch (SQLInjectionException $e) {
            echo '<script>console.log("' . $e->getMessage() . '")</script>';
        } catch (SQLExecutionException $e) {
            echo '<script>console.log("' . $e->getMessage() . '")</script>';
        }

        // SQL preparation-injection-execution -> mainPhoto
        try {
            $mainPhoto = $db->prepareSelectQuery(
                "vehicles AS v", [
                    "valuesSelected" => ["fv.diretorio_fotos", "fv.foto_principal"],
                    "innerJoin"      => "vehicle_photos AS fv ON fv.id = ?"
                ]
            );
            $mainPhoto = $db->sqlInjection($mainPhoto, 'i', $id);
            $mainPhoto = $db->sqlExecuteSelection($mainPhoto);
        } catch (SQLPreparationException $e) {
            echo '<script>console.log("' . $e->getMessage() . '")</script>';
        } catch (SQLInjectionException $e) {
            echo '<script>console.log("' . $e->getMessage() . '")</script>';
        } catch (SQLExecutionException $e) {
            echo '<script>console.log("' . $e->getMessage() . '")</script>';
        }
        
        array_push($infoList, ["vehicle" => $vehicle, "brand" => $brand, "mainPhoto" => $mainPhoto]);
    }

    echo '<script>console.log("Chegou no createVehicleBlock")</script>';
    foreach ($infoList as $info) {
        createVehicleBlock($info["vehicle"], $info["brand"], $info["mainPhoto"]);
    }
    echo '<script>console.log("Saiu do createVehicleBlock")</script>';

    $content .= "</div>";
    return $content;
}
