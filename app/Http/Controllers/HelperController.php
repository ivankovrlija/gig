<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

class HelperController extends Controller
{
    public static function structureResponse($arrayResults, int $page, int $limit, string $pageName){
        $items = $arrayResults->paginate($limit, ['*'], $pageName, $page);

        $results = [
            "result" => $items,
            "count" => $items->total()
        ];

        return $results;
    }

    public static function buildQueryWithFilters(array $columns, array $inputArray, $query){
        foreach($columns as $column){
            if(array_key_exists($column, $inputArray)){
                if($column == 'id' || $column == 'created_at' || $column == 'updated_at'){
                    $query->where($column, '=', $inputArray[$column]);
                }else{
                    $query->where($column,'LIKE',"%{$inputArray[$column]}%");
                }
            }
        }

        return $query;
    }

    public static function getSortResults(array $columns, string $tableName, string $sort, string $direction = 'asc'){
        // $results = \DB::table($tableName)->orderBy($sort, $direction)->get();
        $results = \DB::table($tableName)->orderBy($sort, $direction);

        return $results;
    }
}
