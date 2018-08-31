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

class Log
{

    const EMERG     = 'EMERG';
    const ALERT     = 'ALERT';
    const CRIT      = 'CRIT';
    const ERROR     = 'ERROR';
    const WARNING   = 'WARNING';
    const NOTICE    = 'NOTICE';
    const INFO      = 'INFO';
    const DEBUG     = 'DEBUG';
    const SQL       = 'SQL';
    
    /**
     * Log Instance
     *
     * @var Params
     */
    protected static $_instance;
    
    /**
     * 日志数组
     * 
     * @var array
     */
    protected static $_logs = array();

    /**
     * 获取实例
     */
    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
            register_shutdown_function(array(Log::getInstance(), 'write'));
        }
        return self::$_instance;
    }
    
    /**
     * 记录日志
     * 
     * @param string $message
     * @param string $level
     * @param array $position array('file'=>__FILE__,'line'=>__LINE__)
     */
    public static function record($message, $level = self::ERROR, $position = null)
    {
        if (self::checkLevel($level) === true) {
            self::$_logs[] = array(
                'time' => date('Y-m-d H:i:s'),
                'level' => $level,
                'body' => $message,
                'file' => isset($position['file']) ? $position['file'] : null,
                'line' => isset($position['line']) ? $position['line'] : null
            );
        }
    }
    
    public function write()
    {
        $c = count(self::$_logs);
        $t = isset(self::$_logs[$c - 1]['time']) ? strtotime(self::$_logs[$c - 1]['time']) : time();
        
        $logPath = APP_PATH . DS . 'Log';
        if (! file_exists($logPath) && ! is_dir($logPath)) {
            @mkdir($logPath, 0700);
        }
        $logFile = $logPath . DS . date('Y-m-d', $t) . '.log';
        
        foreach (self::$_logs as $log) {
            $result = file_put_contents(
                $logFile,
                "{$log['time']} - [{$log['level']}: {$log['body']}] - [F: {$log['file']}][L: {$log['line']}]\r\n",
                FILE_APPEND | LOCK_EX
                );
            if ($result === false) {
                throw new Exception('Log write fail.');
            }
        }
    }
    
    public static function checkLevel($level)
    {
        return in_array($level, explode(',', LOG_LEVEL));
    }
}
