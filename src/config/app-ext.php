<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/7/26
 * Time: 9:40 AM
 */

return [
    /**
     * 是否使用扩展的异常处理
     */
    'handle_exception' => true,

    /**
     * 是否检查数据模型软删除启用情况
     */
    'check_model_soft_delete' => false,

    /**
     * 文件路径
     */
    'file_path' => [
        /**
         * 相对于app目录下，数据model存放的目录路径
         */
        'models' => 'Models',

        /**
         * 相对于app目录下，数据仓库存放的目录路径
         */
        'repositories' => 'Repositories',

        /**
         * 相对于app目录下，业务逻辑处理服务存放的目录路径
         */
        'services' => 'Services',
    ],

    /**
     * 组件配置
     */
    'component' => [
        /**
         * 组件名前缀
         */
        'name_prefix' => '',

        /**
         * 扫描目录，相对于app
         */
        'scan_path' => ['Repositories', 'Services']
    ]
];