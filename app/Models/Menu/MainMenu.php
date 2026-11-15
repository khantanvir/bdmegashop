<?php

namespace App\Models\Menu;

use Illuminate\Database\Eloquent\Model;

class MainMenu extends Model
{
    public function sub_menu_list(){
        return $this->hasMany(SubMenu::class,'main_menu_id');
    }
}
