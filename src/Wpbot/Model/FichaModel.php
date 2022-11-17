<?php
namespace Ranchero\Wpbot\Model;

class FichaModel extends DataBase
{

    public function getFichas($limit = 10)
    {
        // return $this->select("SHOW TABLES;");
        return $this->select("SELECT * FROM ficha ORDER BY abonado DESC LIMIT ?", ["i", [$limit]]);
    }

    function getAbonadoByDni($dni)
    {
        if (!(is_numeric($dni) or !is_int($dni)))
            return false;
            var_dump((string)$dni);
        return $this->select("SELECT *
         FROM ficha 
         WHERE tipo_doc = 'DNI' and
         documento = ?", ["s", [(string)$dni]]);
    }

    function getAbonadByiId($id)
    {
        if (!(is_numeric($id) or !is_int($id)))
            return false;
        return $this->select("SELECT *
         FROM ficha 
         WHERE 
         abonado = ?", ["s", [(string)$id]]);
    }
}
