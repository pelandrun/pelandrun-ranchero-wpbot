<?php
namespace Ranchero\Wpbot\Model;


class FacturaModel extends DataBase
{
    var $table = 'factura1';
    function test()
    {
        echo date("Y-m-d H:i:s");
    }
    public function getFichas($limit = 10)
    {
        // return $this->select("SHOW TABLES;");
        return $this->select("SELECT * FROM ficha ORDER BY abonado DESC LIMIT ?", ["i", $limit]);
    }

    function getVencidasByAbonado($abonado)
    {
        $date = date("Y-m-d H:i:s");
        
        // return $this->select("SELECT * FROM factura1 LIMIT 10");
        return $this->select("SELECT * FROM
         factura1
         WHERE
         (codigo='0' OR codigo BETWEEN '6' AND '9')
         AND activa='1' AND vence1<? AND abonado = ? ORDER BY fecha DESC LIMIT 1", ["si", [$date, $abonado]]);
    }
}
