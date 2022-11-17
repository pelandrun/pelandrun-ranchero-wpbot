<?php
namespace Ranchero\Wpbot\Action;

use Ranchero\Wpbot\Action;
use Ranchero\Wpbot\Menu\MainMenu;
use Ranchero\Wpbot\Action\DocumentoAction;
class WelcomeAction extends BaseAction{

    function firsttime()
    {
        // $this->isfirsttime = true;
        $this->next=new DocumentoAction();
        $this->next->parent=$this;
        // var_dump([$this->menu->msg,$this->next->exec()]);
        return array_merge([$this->menu->msg],$this->next->exec());
    }
}
