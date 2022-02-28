<?php

namespace App\Models;

use PDO;

/**
 * Class ProductModel
 * @package
 * User: David Mašek
 * Date: 28.02.2022
 * Time: 10:23
 * PHP version: 8.1
 */
class ProductModelMySQL extends \Core\Model implements \IMySQLDriver
{


    public function findById($id)
    {
        $sql = 'SELECT * FROM produkty WHERE id = :id';
        $db = static::getDb();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        $movie = $stmt->fetch(PDO::FETCH_ASSOC);

        return $movie;
        // TODO: Implement findById() method.
    }
}