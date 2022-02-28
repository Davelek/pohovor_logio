<?php

namespace App\Controllers;


use App\Config;
use App\Models\ProductModelElasticSearch;
use App\Models\ProductModelMySQL;
use Cache;

/**
 * Class ProductController
 * @package
 * User: David Mašek
 * Date: 28.02.2022
 * Time: 10:25
 * PHP version: 8.1
 */
class ProductController
{
    /**
     * @var ProductModelMySQL
     */
    private $product;
    /**
     * @var Cache
     */
    private $cache;

    /**
     * set driver and cache
     */
    public function before()
    {
        if (Config::DRIVER === "MYSQL") {
            $this->product = new ProductModelMySQL();
        } else if (Config::DRIVER === "ELASTICSEARCH") {
            $this->product = new ProductModelElasticSearch();
        }
        $this->cache = new Cache();
    }

    public function detail($id)
    {
        header('Content-type: application/json');
        if ($data = $this->cache->checkCacheAndIncrementCount($id)) {
            foreach ($data as $item) {
                if ($item["id"] == $id) {
                    $json = json_encode($item["content"], JSON_PRETTY_PRINT);
                    $this->cache->updateCache($data, true);
                    return $json;
                }
            }
        } else {
            $result = $this->product->findById($id);
            if ($result === false) {
                return json_encode(["message" => "product not found"]);
            }
            $this->cache->updateCache($result);
            return json_encode($result, JSON_PRETTY_PRINT);
        }
        return json_encode(["message" => "Something went wrong"]);
    }
}