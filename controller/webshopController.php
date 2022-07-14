<?php

require_once __DIR__ . '/../model/webshopservice.class.php';
require_once __DIR__ . '/../model/freedivingservice.class.php';

class WebshopController {
    public function index() {

        $user = FreeDivingService::getUserByName($_SESSION['username']);

        $allProductsInfo = WebshopService::getAllProductsInfo($user); 
        $myProductsInfo = WebshopService::getMyProductsInfo($user); 
        $boughtProductsInfo = WebshopService::getBoughtProductsInfo($user); 
        
        $title = '';

        require_once __DIR__ . '/../view/webshop_index.php';
    }
};

?>