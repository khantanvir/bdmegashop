<?php

namespace App\Models\Menu;

use Illuminate\Database\Eloquent\Model;

class RoleAssign extends Model
{
    public function sub_menu_item(){
        return $this->belongsTo(SubMenu::class,'sub_menu_id');
    }
}
