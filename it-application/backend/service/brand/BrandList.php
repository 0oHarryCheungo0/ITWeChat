<?php
namespace backend\service\brand;

use backend\models\Brand;

class BrandList
{
    public static function getList($data = [])
    {

            $receive   = $data;
            $condition = [];
            if (isset($receive['search']) && !empty($receive['search'])) {
                $search = ['like', 'brand_name', $receive['search']];
            } else {
                $search = true;
            }

            if ($receive['p_id'] != 0) {
                $condition['p_id'] = $receive['p_id'];
            }

            $query = Brand::find()
                ->where($condition)
                ->andwhere($search);

            $total = $query->count();
            $rows  = $query
                ->limit($receive['limit'])
                ->offset($receive['offset'])
                ->with('parent')
                ->asArray()
                ->all();
     

            return $data = ['total' => $total, 'rows' => $rows];
    }
}
