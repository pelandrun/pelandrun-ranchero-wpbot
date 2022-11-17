<?php

namespace Ranchero\Wpbot\Action;

use Ranchero\Wpbot\Menu\MainMenu;
use Ranchero\Wpbot\Model\FacturaModel;

class DeudaAction extends BaseAction
{

    var $isfirsttime = false;

    function process($option)
    {
        $option=$this->bagage['abonado']['abonado'];
        $opt = mb_strtoupper(mb_substr($option, 0, 64));
        if (!is_numeric($opt)) {
            return [$this->menu->error, $this->menu->msg];
        }
        $facturas = new FacturaModel($GLOBALS['datasource']['default']);
        $facturavencida = $facturas->getVencidasByAbonado($opt);
        var_dump($facturavencida);
        if (!$facturavencida){
            $this->next = $this->parent;
            return array_merge([
                $this->bagage['abonado']['nombre']. ", no encotramos facturas vencidas"
            ],$this->next->exec());
        }
        $rst = $this->bagage['abonado']['nombre']. ", tenes al emnos una factura vencia el ". $facturavencida[0]['vence1'];
        $this->bagage['facturavencida']=$facturavencida[0];
        $this->next = $this->parent;
        return array_merge([$rst], $this->next->exec());
    }
}
