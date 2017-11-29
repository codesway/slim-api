<?php
/**
 * Created by PhpStorm.
 * User: CodeWay
 * Date: 2017/11/29
 * Time: 15:58
 */

namespace CC\Api\Error;

use CC\Core\Base\ApiBase;
class ErrorApi extends ApiBase
{

    public function notfoundExecute()
    {
        return [
            'not found'
        ];
    }
}
