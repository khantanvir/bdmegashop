<?php

namespace App\Http\Middleware;

use App\Models\Menu\RoleAssign;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MenuItems
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $menu_items = [];
        if(Auth::check()){
            $get_roles = RoleAssign::with('sub_menu_item')->where('user_id',Auth::user()->id)->get();
            if($get_roles){
                foreach ($get_roles as $key => $value) {
                    $menu_items[] = $value->sub_menu_item->process;
                }
            }
        }
        view()->share('menu_items', $menu_items);
        return $next($request);
    }
}
