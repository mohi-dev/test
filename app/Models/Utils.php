<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Utils extends Model
{
    use HasFactory;

    /**
     * @method static int DatabaseError() database error
     */

    public static function successResponse($result)
    {
        return response()->json([
            'status' => true,
            'code' => 200,
            'result' => $result,
        ]);
    }

    public static function __callStatic($name, $arguments)
    {
        if ($name == 'DatabaseError') {
            return self::errorResponse(500, 'sql error');
        }
    }

    public static function errorResponse($error_code, $result)
    {
        return response()->json([
            'status' => false,
            'code' => $error_code,
            'result' => $result
        ]);
    }
}
