<?php
/*
** Created by lll.
** FileName: Model.php
** Author: liulinliang@hunbasha.com
** Date: 2017/11/23
** Brief
*/
namespace CC\Codebase\Model\User;

use Illuminate\Database\Eloquent\Model; //ORM基类
use CC\Core\Base\ModelBase;

class UserModel extends ModelBase
{

    public function getUser()
    {
        return ModelBase::getDb('cc_city')->table('time_st')->get();
    }

}