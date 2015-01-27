<?php

/**
 * Created by PhpStorm.
 * User: manperlop
 * Date: 18/11/14
 * Time: 0:51
 */
abstract class Dt extends Eloquent
{

    public static $connection = null;

    public static function getTableName()
    {
    }

    public static function selectDTFields($query)
    {
    }

    public static function getClass()
    {
        return get_called_class();
    }

    public static function getDTQuery($trash)
    {
        $conn = DB::getName();
        if(!empty(static::$connection))
            $conn = static::$connection;

        $query = null;
        if(isset($trash['delete']) && (int)$trash['delete']==1)
        {
            if(isset($trash['active']) && (int)$trash['active']==1)
            {
                $query = DB::connection($conn)->table(static::getTableName());
            }else{

                $query = DB::connection($conn)->table(static::getTableName())->whereNotNull(static::getTableName() . '.deleted_at');
            }
        }else{
            $query = DB::connection($conn)->table(static::getTableName())->whereNull(static::getTableName() . '.deleted_at');
        }
        return $query;
    }

    public static function datatable()
    {
        $filters = Input::get('filters');
        $model = static::getClass();
        $query = static::getDTquery(Input::get('trash'));
        $query = static:: filterDTQuery($filters, $query, $model);
        $query = static:: selectDTFields($query);

        $dataTable = Datatable::query($query);

        return $dataTable;
    }

    public static function filterDTQuery($filters, $query, $model)
    {
        if (is_array($filters) and count($filters) > 0) {
            foreach ($filters as $filter) {
                if($filter['field']!="trash.active" && $filter['field']!="trash.deleted"){
                    if (isset($filter['join'])) {
                        $query->join($filter['join'], $filter['join'] . '.id', '=', $model::getTableName() . '.' . $filter['join'] . '_id');
                        $query->where($filter['join'] . '.' . $filter['field'], '=', $filter['value']);
                    } else {
                        $query->where($model::getTableName() . '.' . $filter['field'], '=', $filter['value']);
                    }
                }
            }
        }
        return $query;
    }
}
