<?php

namespace App\Models\Menu;

use Illuminate\Database\Eloquent\Model;

class SubMenu extends Model
{
    public $table = 'sub_menus';
    public function main_menu(){
        return $this->belongsTo(MainMenu::class,'main_menu_id');
    }
}
