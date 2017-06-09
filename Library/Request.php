<?php
// +------------------------------------------------------------
// | Mini Framework
// +------------------------------------------------------------
// | Source: https://github.com/jasonweicn/MiniFramework
// +------------------------------------------------------------
// | Author: Jason.wei <jasonwei06@hotmail.com>
// +------------------------------------------------------------

namespace Mini;

class Request
{
    /**
     * Request实例
     * 
     * @var Request
     */
    protected static $_instance;
    
    protected $_baseUrl = null;
    
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
     * 获取实例
     *
     */
    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    /**
     * 构造
     *
     */
    protected function __construct()
    {
        //reserve...
    }
    
    /**
     * 从$_SERVER['PHP_SELF']中提取基础地址
     *
     */
    public function setBaseUrl()
    {
        if ($this->_baseUrl === null) {
            $phpSelf = $_SERVER['PHP_SELF'];
            $urlArray = explode('/', $phpSelf);
            unset($urlArray[count($urlArray) - 1]);
            $this->_baseUrl = implode('/', $urlArray);
        }
        return $this->_baseUrl;
    }
    
    /**
     * 获取基础地址
     *
     */
    public function getBaseUrl()
    {
        if ($this->_baseUrl === null) {
            $this->_baseUrl = $this->setBaseUrl();
        }
        return $this->_baseUrl;
    }
    
    /**
     * 设置控制器
     * 
     * @param string $value
     */
    public function setControllerName($value)
    {
        $this->_controller = $value;
    }
    
    /**
     * 设置动作
     * 
     * @param string $value
     */
    public function setActionName($value)
    {
        $this->_action = $value;
    }
}
