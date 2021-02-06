<?php
/*
*   07.11.2019
*   MenusMenu.php
*/

namespace App\Http\Menus;

use App\Services\MenuBuilder\MenuBuilder;
use Illuminate\Support\Facades\DB;
use App\Models\Menu\MenuItem;
use App\Services\MenuBuilder\RenderFromDatabaseData;

class GetSidebarMenu implements MenuInterface
{

    private $mb; //menu builder
    private $menu;

    public function __construct()
    {
        $this->mb = new MenuBuilder();
    }

    private function getMenuFromDB($menuName, $role)
    {
        $this->menu = MenuItem::join('menu_role', 'menu_items.id', '=', 'menu_role.menus_id')
            ->join('menus', 'menus.id', '=', 'menu_items.menu_id')
            ->select('menu_items.*')
            ->where('menus.name', '=', $menuName)
            ->where('menu_role.role_name', '=', $role)
            ->orderBy('menu_items.sequence', 'asc')->get();
    }

    private function getGuestMenu($menuName)
    {
        $this->getMenuFromDB($menuName, 'guest');
    }

    private function getUserMenu($menuName)
    {
        $this->getMenuFromDB($menuName, 'user');
    }

    private function getAdminMenu($menuName)
    {
        $this->getMenuFromDB($menuName, 'admin');
    }

    public function get($roles, $menuName = 'sidebar menu')
    {
        $roles = explode(',', $roles);
        if (empty($roles)) {
            $this->getGuestMenu($menuName);
        } elseif (in_array('admin', $roles)) {
            $this->getAdminMenu($menuName);
        } elseif (in_array('user', $roles)) {
            $this->getUserMenu($menuName);
        } else {
            $this->getMenuFromDB($menuName, $roles[0]);
        }
        $rfd = new RenderFromDatabaseData;
        return $rfd->render($this->menu);
    }

    public function getAll($menuId = 1)
    {
        $this->menu = MenuItem::select('menu_items.*')
            ->where('menu_items.menu_id', '=', $menuId)
            ->orderBy('menu_items.sequence', 'asc')->get();
        $rfd = new RenderFromDatabaseData;
        return $rfd->render($this->menu);
    }
}
