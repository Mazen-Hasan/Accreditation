<?php
namespace App\Http\Traits;

trait ConditionTrait {

    public static function getConditionPart($columnName,$condition,$token){
        $conditionPart = "";
        switch ($condition) {
            case "1":
                $conditionPart = $columnName ." Like " . "'%" . $token . "%'";
                break;
            case "5":
                $conditionPart = $columnName . " Like " . "'" . $token . "%'";
                break;
            case "6":
                $conditionPart = $columnName . " Like " . "'%" . $token . "'";
                break;
            case "3":
                $conditionPart = $columnName . " = " . "'" . $token . "'";
                break;
            case "4":
                $conditionPart = $columnName . " <> " . "'" . $token . "'";
                break;
            case "2":
                $conditionPart = $columnName . " Not Like " . "'%" . $token . "%'";
                break;
        }
        return $conditionPart;
    }

}
