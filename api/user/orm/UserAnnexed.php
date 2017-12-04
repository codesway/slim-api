<?php
/**
 * Created by PhpStorm.
 * User: CodeWay
 * Date: 2017/11/25
 * Time: 10:03
 */

namespace CC\Api\User\Orm;

use CC\Core\Base\OrmBase;

class UserAnnexed extends OrmBase
{
    protected $connection = 'callcenter_bj';
    protected $table = 'l_line_category';

    public static function getone()
    {
        return 1;
    }
}