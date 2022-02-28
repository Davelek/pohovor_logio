<?php

namespace App\Models;

use Core\Model;
use IElasticSearchDriver;

/**
 * Class ProductModelElasticSearch
 * @package
 * User: David Mašek
 * Date: 28.02.2022
 * Time: 10:30
 * PHP version: 8.1
 */
class ProductModelElasticSearch extends Model implements IElasticSearchDriver
{


    public function findById($id)
    {
        // TODO: Implement findById() method.
    }
}