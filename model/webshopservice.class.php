<?php

require_once __DIR__ . '/../app/database/db.class.php';
require_once __DIR__ . '/product.class.php';
require_once __DIR__ . '/user.class.php';
require_once __DIR__ . '/sale.class.php';
//moze se dodati ispis ovisno o tome jel operacija obavljena uspjesno ili neuspjesno

class WebshopService {
    public static function getAllProducts()
    {
        $products = [];

        $db = DB::getConnection();
        $st = $db->prepare('SELECT * FROM products');
        $st->execute([]);

        while ($row = $st->fetch())
        {
            $products[] = new Product($row['id'], $row['id_user'], $row['name'], $row['description'], $row['price']);
        }
        return $products;
    }

    public static function getMyProducts($user)
    {
        $products = [];

        $db = DB::getConnection();
        $st = $db->prepare('SELECT * FROM products WHERE id_user=:id_user');
        $st->execute(['id_user' => $user->id]);

        while ($row = $st->fetch())
        {
            $products[] = new Product($row['id'], $row['id_user'], $row['name'], $row['description'], $row['price']);
        }
        return $products;
    }

    public static function getBoughtProducts($user)
    {
        $products = [];

        $db = DB::getConnection();
        $st = $db->prepare('SELECT * FROM sales WHERE id_user=:id_user');
        $st->execute(['id_user' => $user->id]);

        $st2 = $db->prepare('SELECT * FROM products WHERE id=:id');

        while ($row = $st->fetch ())
        {
            $id_product = $row['id_product'];

            $st2->execute(['id' => $id_product]);
            $row2 = $st2->fetch ();
            $products[] = new Product($row2['id'], $row2['id_user'], $row2['name'], $row2['description'], $row2['price']);
        }

        return $products;
    }

    public static function getAllProductsInfo($user)
    {
        $products = [];

        $db = DB::getConnection();
        $st = $db->prepare('SELECT * FROM products');
        $st->execute([]);

        while ($row = $st->fetch())
        {
            $owner = (FreeDivingService::getUserByID($row['id_user']))->username;
            $products[] = [$row['name'], $row['description'], $row['price'], $owner, $row['id'], WebShopService::canIBuyIt($user->id, $row['id']), $row['number_available']];
        }
        return $products;
    }

    public static function getMyProductsInfo($user)
    {
        $products = [];

        $db = DB::getConnection();
        $st = $db->prepare('SELECT * FROM products WHERE id_user=:id_user');
        $st->execute(['id_user' => $user->id]);

        while ($row = $st->fetch())
        {
            $products[] = [$row['name'], $row['description'], $row['price'], $row['number_available'], $row['id']];
        }
        return $products;
    }

    public static function canICommentIt($my_id, $id_product)
    {
        $db = DB::getConnection();
        $st = $db->prepare('SELECT * FROM sales WHERE id_user=:id_user and id_product=:id_product');

        $st->execute(['id_user' => $my_id, 'id_product' => $id_product]);

        $row = $st->fetch();

        if($row['comment'] === null)
            return [true, null];
        return [false, $row['comment']];
    }

    public static function getBoughtProductsInfo($user)
    {
        $products = [];

        $db = DB::getConnection();
        $st = $db->prepare('SELECT * FROM sales WHERE id_user=:id_user');
        $st->execute(['id_user' => $user->id]);

        $st2 = $db->prepare('SELECT * FROM products WHERE id=:id_product');
        
        $st3 = $db->prepare('SELECT * FROM sales WHERE id=:id_product');

        while ($row = $st->fetch())
        {
            $st2->execute(['id_product' => $row['id_product']]);
            $row2 = $st2->fetch();

            $products[] = [$row2['name'], $row2['description'], $row2['price'], $row2['number_available'], $row['id_product'], WebshopService::canICommentIt($user->id, $row['id_product'])];
        }
        return $products;
    }

    public static function getProductQuantityByID($id_product)
    {
        $db = DB::getConnection();
        $st = $db->prepare('SELECT * FROM products WHERE id=:id_product');
        $st->execute(['id_product' => $id_product]);

        if($row = $st->fetch())
            return $row['number_available'];
        else return 0;
    }

    public static function getCommentsAndRatingsByProductID($id_product)
    {
        $db = DB::getConnection();
        $st = $db->prepare('SELECT * FROM sales WHERE id_product=:id_product');
        $st->execute(['id_product' => $id_product]);

        $comment_and_ratings = [];

        while($row = $st->fetch())
            if($row['comment'] !== null)
                $comment_and_ratings[] = [$row['comment'], $row['rating']];

        return $comment_and_ratings;
    }

    public static function getRatingByProductID($id_product)
    {
        $total_amount = 0;
        $total_number = 0;
        $db = DB::getConnection();
        $st = $db->prepare('SELECT * FROM sales WHERE id_product=:id_product');
        $st->execute(['id_product' => $id_product]);

        $ratings = [];

        while($row = $st->fetch())
            if($row['rating'] !== null)
            {
                $total_amount = $total_amount + (int)$row['rating'];
                ++$total_number;
            }
        
        if($total_number === 0)
            return 0;
        return round($total_amount / $total_number, 2);
    }

    public static function buyProduct($id_user, $id_product)
    {
        $db = DB::getConnection();
        $st = $db->prepare('SELECT * FROM products WHERE id=:id_product');
        $st->execute(['id_product' => $id_product]);
        $row = $st->fetch();

        if($row['number_available'] > 0)
        {
            $st = $db->prepare('INSERT INTO sales(id_product, id_user) VALUES (:id_product, :id_user)');
            $st->execute(['id_product' => $id_product, 'id_user' => $id_user]);
        }

        $st = $db->prepare('UPDATE products SET number_available = number_available - 1 WHERE id=:id_product');
        return $st->execute(['id_product' => $id_product]);
    }

    public static function canIBuyIt($my_id, $id_product)
    {
        $db = DB::getConnection();
        $st = $db->prepare('SELECT * FROM sales WHERE id_user=:id_user and id_product=:id_product');

        $st->execute(['id_user' => $my_id, 'id_product' => $id_product]);

        if($st->fetch()) return false;

        $st = $db->prepare('SELECT * FROM products WHERE id_user=:id_user and id=:id_product');

        $st->execute(['id_user' => $my_id, 'id_product' => $id_product]);

        if($row = $st->fetch()) return false;

        $st = $db->prepare('SELECT * FROM products WHERE id=:id_product');
        $st->execute(['id_product' => $id_product]);

        if(!($row = $st->fetch())) return false;

        if($row['number_available'] === '0') return false;

        return true;
    }

    public static function addProduct($id_user, $name, $description, $price, $number_available)
    {
        $db = DB::getConnection();
        $st = $db->prepare('INSERT INTO products(id_user, name, description, price, number_available) VALUES (:id_user, :name, :description, :price, :number_available)');
        return $st->execute(['id_user' => $id_user, 'name' => $name, 'description' => $description, 'price' => $price, 'number_available' => $number_available]);
    }

    public static function addCommentAndRating($id_user, $id_product, $comment, $rating)
    {
        $db = DB::getConnection ();
        $st = $db->prepare ('UPDATE sales SET rating=:rating, comment=:comment WHERE id_user=:id_user and id_product=:id_product');
        return $st->execute (['rating' => $rating, 'comment' => $comment, 'id_user' => $id_user, 'id_product' => $id_product]);
    }

    public static function getProductsIBought ($id_user) {
        $products = [];

        $db = DB::getConnection ();
        $st = $db->prepare ('SELECT * FROM sales WHERE id_user=:id_user');
        $st->execute (['id_user' => $id_user]);

        $st2 = $db->prepare ('SELECT * FROM products WHERE id=:id');

        while ($row = $st->fetch ()) {
            $sale = new Sale (
                $row['id'], $row['id_product'], $row['id_user'], $row['rating'], $row['comment']
            );

            $st2->execute (['id' => $sale->id_product]);
            $row2 = $st2->fetch ();
            $product = new Product (
                $row2['id'], $row2['id_user'], $row2['name'], $row2['description'], $row2['price']
            );

            $products[] = $product;
        }

        return $products;
    }
    //posebno obratite paznju na ovaj nacin slanja, kao i prije da demonstriramo vise toga
    public static function getIDByName ($username) {
        $id = '';
        $db = DB::getConnection ();
        $st = $db->prepare ('SELECT id FROM users WHERE username=:username');
        $st->execute (['username' => $username]);

        if ($row = $st->fetch ())
            $id = $row['id'];

        return $id;
    }

    public static function getNameByID ($id) {
        $username = '';
        $db = DB::getConnection ();
        $st = $db->prepare ('SELECT * FROM users WHERE id=:id');
        $st->execute (['id' => $id]);

        if ($row = $st->fetch ())
            $username = $row['username'];

        return $username;
    }

    public static function getProductByID ($id_product) {

        $db = DB::getConnection ();
        $st = $db->prepare ('SELECT * FROM products WHERE id=:id');
        $st->execute (['id' => $id_product]);

        $row = $st->fetch ();

        $product = new Product (
            $row['id'], $row['id_user'], $row['name'], $row['description'], $row['price']
        );

        return $product;
    }

    public static function getProductData ($id_product) {
        $sales = [];

        $db = DB::getConnection ();
        $st = $db->prepare ('SELECT * FROM sales WHERE id_product=:id_product');
        $st->execute (['id_product' => $id_product]);

        while ($row = $st->fetch ()) {
            $sale = new Sale (
                $row['id'], $row['id_product'], $row['id_user'], $row['rating'], $row['comment']
            );
            $sales[] = $sale;
        }

        return $sales;
    }


    public static function getProductsByName ($name) {
        $products = [];

        $db = DB::getConnection ();
        $st = $db->prepare ('SELECT * FROM products WHERE name=:name');
        $st->execute (['name' => $name]);

        while ($row = $st->fetch ()) {
            $product = new Product (
                $row['id'], $row['id_user'], $row['name'], $row['description'], $row['price']
            );

            $products[] = $product;
        }

        return $products;
    }

    public static function addComment ($id_sale, $rating, $comment) {
        $products = [];

        $db = DB::getConnection ();
        $st = $db->prepare ('UPDATE sales SET rating=:rating, comment=:comment WHERE id=:id');
        $st->execute (['rating' => $rating, 'comment' => $comment, 'id' => $id_sale]);

        $st2 = $db->prepare ('SELECT * FROM sales WHERE id=:id');
        $st2->execute (['id' => $id_sale]);

        $row2 = $st2->fetch ();
        
        return $row2['id_user'];
    }

    public static function howManySold ($id_product) {
        $number_sold = 0;

        $db = DB::getConnection ();
        $st = $db->prepare ('SELECT * FROM sales WHERE id_product=:id_product');

        $st->execute (['id_product' => $id_product]);

        while ($row = $st->fetch ())
            ++$number_sold;
        
        return $number_sold;
    }

    public static function rating ($id_product) {
        $total_rating = 0;
        $num = 0;

        $db = DB::getConnection ();
        $st = $db->prepare ('SELECT * FROM sales WHERE id_product=:id_product');

        $st->execute (['id_product' => $id_product]);

        while ($row = $st->fetch ())
            if ($row['rating'] !== NULL && $row['comment'] !== NULL) {
                $total_rating += $row['rating'];
                ++$num;
            }
        
        if ($num === 0)
            return -1;
        else return (float) $total_rating / $num;
        
    }

    public static function addSale ($id_product, $id_user) {

        $db = DB::getConnection ();

        $st = $db->prepare ('INSERT INTO sales(id_product, id_user, rating, comment) VALUES (:id_product, :id_user, :rating, :comment)');

        $st->execute (['id_product' => $id_product, 'id_user' => $id_user, 'rating' => NULL, 'comment' => NULL]);
    }
    
};





?>