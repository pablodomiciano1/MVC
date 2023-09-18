<?php

namespace App\Model\Entity;

class Organization{

    /**
     * ID da Organização
     *
     * @var integer
     */
    public $id = 1;

    /**
     * Nome da Organização
     *
     * @var string
     */
    public $name = 'GPS FOODs';

    /**
     * Site da Empresa
     *
     * @var string
     */
    public $site = 'https://www.gpsfoods.com.br';

    /**
     * Descipção da organização
     *
     * @var string
     */
    public $description = 'Somos um site de avaliação de restaurantes da cidade de Guararapes,
     aqui você pode deixar sua opinião sobre seu restaurante favorito ou sobre algum restaurante que acabou de visitar.';
}


