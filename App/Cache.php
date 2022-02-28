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


    /**
     * Get String from cache file or false if it doesnt exist
     * @return false|string
     */
    private function getCache()
    {
        if (self::checkIfCacheExist()) {
            return file_get_contents(Config::CACHE_PATH);
        } else {
            return false;
        }
    }

    /**
     * return if cache file exist
     * @return bool
     */
    private function checkIfCacheExist()
    {
        $path = Config::CACHE_PATH;
        return file_exists($path);
    }

    /**
     * Updates cache with new information
     * @param array $data - $data is new product
     */
    public function updateCache($data)
    {
        $data = self::prepareData($data);
        if (!$this->cache) {
            self::createCache($data);
            return;
        }
            $items = json_decode($this->cache, true);
            $items[] = $data;
            self::createCache($items);
    }

    /**
     * prepare $data for saving in Cache
     * @param $data
     * @return array
     */
    public function prepareData($data)
    {
        return [
            "id" => $data["id"],
            "count" => 1,
            "content" => [
                $data
            ]
        ];
    }

    /**
     * saving new Cache with $data
     * @param $data
     */
    private function createCache($data)
    {
        $fp = fopen(Config::CACHE_PATH, 'w');
        fwrite($fp, json_encode($data));
        fclose($fp);
    }

    /**
     * get new cache array with incremented count number, or return false, if $id is not found in cache
     * @param $id
     * @return array|false
     */
    public function checkCacheAndIncrementCount($id)
    {
        if ($this->cache) {
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