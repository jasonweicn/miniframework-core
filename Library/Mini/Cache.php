<?php
// +---------------------------------------------------------------------------
// | Mini Framework
// +---------------------------------------------------------------------------
// | Copyright (c) 2015-2018 http://www.sunbloger.com
// +---------------------------------------------------------------------------
// | Licensed under the Apache License, Version 2.0 (the "License");
// | you may not use this file except in compliance with the License.
// | You may obtain a copy of the License at
// |
// | http://www.apache.org/licenses/LICENSE-2.0
// |
// | Unless required by applicable law or agreed to in writing, software
// | distributed under the License is distributed on an "AS IS" BASIS,
// | WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// | See the License for the specific language governing permissions and
// | limitations under the License.
// +---------------------------------------------------------------------------
// | Source: https://github.com/jasonweicn/MiniFramework
// +---------------------------------------------------------------------------
// | Author: Jason Wei <jasonwei06@hotmail.com>
// +---------------------------------------------------------------------------
// | Website: http://www.sunbloger.com/miniframework
// +---------------------------------------------------------------------------
namespace Mini;

class Cache
{

    /**
     * 工厂模式获取缓存实例
     *
     * @param string $adapter            
     * @param array $params            
     */
    public static function factory($adapter = 'Memcache', $params = array())
    {
        if (! is_string($adapter) || empty($adapter)) {
            throw new Exception('Adapter name must be specified in a string.');
        }
        
        if (in_array($adapter, array(
            'Memcache',
            'Memcached',
            'Redis'
        ))) {
            
            if (! class_exists($adapter)) {
                throw new Exception('Adapter ' . $adapter . ' not found');
            }
            
            if (! is_array($params)) {
                throw new Exception('Adapter params invalid.');
            }
            
            if (! isset($params['host'])) {
                throw new Exception('Cache(' . $adapter . ') host is not defined.');
            } elseif (! isset($params['port'])) {
                throw new Exception('Cache(' . $adapter . ') port is not defined.');
            }
        }
        
        $adapterName = '\\Mini\\Cache\\Cache_' . ucwords($adapter);
        
        if (! class_exists($adapterName)) {
            throw new Exception('Adapter "' . $adapterName . '" not found.');
        }
        
        $cacheAdapter = new $adapterName($params);
        
        if (! $cacheAdapter instanceof \Mini\Cache\Cache_Abstract) {
            throw new Exception('Adapter class "' . $adapterName . '" does not extend Cache_Abstract.');
        }
        
        return $cacheAdapter;
    }
}
