<?php

    $config = array(
        /**
         * mysql 数据库
         *      host 服务器
         *      user 用户名
         *      password 密码
         *      database 表名
         */
        'Mysql' => array(
            'host'      => '127.0.0.1',
            'user'      => 'root',
            'password'  => 'QY123456',
            'database'  => 'test',
        ),
        'LOG_MODE' => true, // 日志是否开启
        'LOG_PATH' => __DIR__ . '/log' // 日志路径
    );

    return $config;