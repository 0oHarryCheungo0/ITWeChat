<?php

namespace backend\service\adminuser;

use backend\models\AdminUser;

class UserList
{
    public static function getList($limit, $offset, $search = '')
    {
        $rows = [];
        if (!empty($search)) {
            $query = AdminUser::find()->where(['>', 'id', 1])->andWhere(['like','username',$search]);
            $count = $query->count();
            $list  = $query->offset($offset)
                ->limit($limit)
                ->with('brandOne')
                ->asArray()
                ->all();
        } else {
            $query = AdminUser::find()->where(['>', 'id', 1]);
            $count = $query->count();
            $list  = $query->offset($offset)
                ->limit($limit)
                ->with('brandOne')
                ->asArray()
                ->all();
        }

        return ['total' => $count, 'rows' => $list];
    }
}
