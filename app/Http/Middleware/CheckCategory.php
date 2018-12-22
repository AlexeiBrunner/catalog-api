<?php

namespace App\Http\Middleware;

use Closure;
use App\Category;

class CheckCategory
{

    public function handle($request,Closure $next)
    {
        if($request->parent_id && !Category::find($request->parent_id)) {
            return response()->json(['error' => 'Parent category not exist'], 204);
        }

        return $next($request);
    }
}
