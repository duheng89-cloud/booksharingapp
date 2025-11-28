<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * 基础控制器类
 * 
 * 所有控制器都应该继承这个类
 * 这个类可以包含所有控制器共用的方法和属性
 */
abstract class Controller extends BaseController
{
    // 授权请求 trait - 提供 authorize() 方法
    use AuthorizesRequests;
    
    // 验证请求 trait - 提供 validate() 方法
    use ValidatesRequests;
}
