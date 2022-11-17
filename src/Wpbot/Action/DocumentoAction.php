<?php

namespace Ranchero\Wpbot\Action;
use Ranchero\Wpbot\Action;
use Ranchero\Wpbot\Menu\MainMenu;
use Ranchero\Wpbot\Model\FichaModel;

class DocumentoAction extends BaseAction
{

    function process($option)
    {
        $opt = mb_strtoupper(mb_substr($option, 0, 64));
        if (!is_numeric($opt)) {
            return [$this->menu->error, $this->menu->msg];
        }
        $ficha = new FichaModel($GLOBALS['datasource']['default']);
        $abonado = $ficha->getAbonadoByDni($option);
        if (!$abonado)
            return [$this->menu->error, $this->menu->msg];
        $rst = "Hola: " . $abonado[0]['nombre'] . " " . $abonado[0]['apellido'];
        $this->bagage['abonado'] = $abonado[0];
        $this->next = new MainMenu;
        $this->next->parent = $this;
        return array_merge([$rst], $this->next->exec());
    }
}
