<?php
/*
*   07.11.2019
*   MenusMenu.php
*/

namespace App\Http\Menus;

use App\Services\MenuBuilder\MenuBuilder;

class Menus implements MenuInterface
{
    private MenuBuilder $menuBuilder; //menu builder

    public function __construct()
    {
        $this->menuBuilder = new MenuBuilder();
    }

    private function getGuestMenu()
    {
        $this->menuBuilder->addLink('Dashboard', '/', 'cui-speedometer');
    }

    private function getAdminMenu()
    {
        $this->menuBuilder->addLink('Dashboard', '/', 'cui-speedometer');
    }

    public function get($roles)
    {
        $roles = explode(',', $roles);
        if (empty($roles)) {
            $this->getGuestMenu();
        } elseif (in_array('user', $roles)) {
            $this->getAdminMenu();
        } else {
            $this->getGuestMenu();
        }
        return $this->menuBuilder->getResult();
    }

}
