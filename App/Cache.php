<?php

use App\Config;

/**
 * Class Cache
 * User: David Mašek
 * Date: 28.02.2022
 * Time: 10:32
 * PHP version: 8.1
 */
class Cache
{

    /**
     * @var false|string
     */
    private $cache;

    public function __construct()
    {
        $this->cache = self::getCache();
    }

    private static function getCache()
    {
        if (self::checkIfCacheExist())
        {
            return file_get_contents(Config::CACHE_PATH);
        }else{
            return false;
        }
    }

    private static function checkIfCacheExist()
    {
        $path = Config::CACHE_PATH;
        if (file_exists($path)) {
            return true;
        }
        return false;
    }

    public function updateCache($id, $data = [])
    {
        if (!self::checkIfCacheExist()) {
            $preparedData[] = self::prepareData($data);
            self::createCache($preparedData);
            return;
        }

        if (empty($data)){

            $newData = self::checkCacheAndIncrementCount($id);
            self::createCache($newData);
        }else {

            $items = json_decode($this->cache, true);
            $preparedData = self::prepareData($data);
            $items[] = $preparedData;
            self::createCache($items);
        }
    }

    private function prepareData($data)
    {
        $dataCache = [
            "id" => $data["id"],
            "count" => 1,
            "content" => [
               $data
            ]
        ];
        return $dataCache;
    }

    private function createCache($data)
    {
        $fp = fopen(Config::CACHE_PATH, 'w');
        fwrite($fp, json_encode($data));
        fclose($fp);
    }


    public function checkCacheAndIncrementCount($id)
    {
        if (self::checkIfCacheExist()) {
            $items = json_decode($this->cache, true);
            $updatedList = [];
            $zmena = false;
            foreach ($items as $item) {
                if ($item["id"] == $id) {
                    $item["count"] += 1;
                    $updatedList[] = $item;
                    $zmena = true;
                } else {
                    $updatedList[] = $item;
                }
            }
            if ($zmena) {
                return $updatedList;
            } else {
                return false;
            }
        }
        return false;
    }


}