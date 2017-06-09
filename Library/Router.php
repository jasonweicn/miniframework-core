<?php
// +------------------------------------------------------------
// | Mini Framework
// +------------------------------------------------------------
// | Source: https://github.com/jasonweicn/MiniFramework
// +------------------------------------------------------------
// | Author: Jason.wei <jasonwei06@hotmail.com>
// +------------------------------------------------------------

namespace Mini;

class Router
{    
    /**
     * Request实例
     * @var Request
     */
    private $_request;
    
    /**
     * 控制器
     * 
     * @var string
     */
    public $_controller;
    
    /**
     * 动作
     * 
     * @var string
     */
    public $_action;
    
    /**
     * 路由方式
     * 
     * @var string
     */
    protected $_routeType;
    
    /**
     * 路径数组
     * 
     * @var string
     */
    protected $_uriArray;
    
    /**
     * 构造
     * 
     */
    public function __construct()
    {
        $this->_request = Request::getInstance();
        
        if (false === strpos($_SERVER['REQUEST_URI'], $_SERVER['SCRIPT_NAME'])) {
            //Rewrite
            $this->_routeType = 'rewrite';
            $this->_uriArray = $this->parseUrlToArray();
            
            $controller = (isset($this->_uriArray[1]) && !empty($this->_uriArray[1])) ? $this->_uriArray[1] : 'index';
            $action = (isset($this->_uriArray[2]) && !empty($this->_uriArray[2])) ? $this->_uriArray[2] : 'index';
        } else {
            //GET
            $this->_routeType = 'get';
            if (empty($_SERVER['QUERY_STRING'])) {
                $controller = 'index';
                $action = 'index';
            } else {
                parse_str($_SERVER['QUERY_STRING'], $urlParams);
                
                $controller = isset($urlParams['c']) ? $urlParams['c'] : 'index';
                $action = isset($urlParams['a']) ? $urlParams['a'] : 'index';
            }
        }
        
        if ($this->checkRoute($controller)) {
            $this->_request->setControllerName($controller);
        } else {
            throw new Exceptions('Controller "' . $controller . '" not found.', 404);
        }
        
        if ($this->checkRoute($action)) {
            $this->_request->setActionName(strtolower($action));
        } else {
            throw new Exceptions('Action "' . $action . '" does not exist.', 404);
        }
    }
    
    public function setRouteType($type)
    {
        $this->_routeType = $type;
    }
    
    public function getRouteType()
    {
        return $this->_routeType;
    }
    
    public function getUriArray()
    {
        return $this->_uriArray;
    }
    
    /**
     * 解析Url为数组
     * 
     * @return array
     */
    public function parseUrlToArray()
    {
        $requestUri = '';
        
        if (empty($_SERVER['QUERY_STRING'])) {
            $requestUri = $_SERVER['REQUEST_URI'];
        } else {
            $requestUri = str_replace('?' . $_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']);
        }
        
        $baseUrl = $this->_request->getBaseUrl();
        
        if ($requestUri != $baseUrl) {
            $requestUri = str_replace($baseUrl, '', $requestUri);
        }
        $uriArray = explode('/', $requestUri);
        return $uriArray;
    }
    
    /**
     * 检查路由参数合法性
     * 
     * @param mixed $value
     * @return int
     */
    protected function checkRoute($value)
    {
        return preg_match ("/^[a-zA-Z][a-zA-Z0-9]*$/", $value);
    }
}
