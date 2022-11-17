<?php
namespace Ranchero\Wpbot\Menu;

class BaseMenu
{
    function __construct($definitions = null)
    {   
        if(!$definitions){
            $class_name=get_class($this);
            $topname=explode("\\",$class_name);
            $class_name=array_pop($topname);                    
            $definitions=$class_name;
        }
        $this->name = $definitions;
        if (!file_exists(MENU_DEF_DIR . $this->name . ".json"))
            throw new \Exception('File: ' . MENU_DEF_DIR . $this->name . '.json not foud');
        $def = file_get_contents(MENU_DEF_DIR . $this->name . ".json");
        if (!$this->menu = json_decode($def))
            throw new \Exception('File: ' . MENU_DEF_DIR . $this->name . '.json not valid json');
        $menu = json_decode($def, 1);
        if (key_exists('options', $menu) and is_countable($menu['options'])) {
            $this->totalopts = count($menu['options']);
        } else {
            $this->totalopts = 0;
        }
    }

    function exec($option = null)
    {
        echo $this->name . "\n";
        $msg = $this->menu->msg . "\n";
        if ($this->menu->type == "Action") {
            $this->next = $this;
            if ($option === null) {
                return [$this->menu->error];
            } else {
                if ($option == "A") {
                    $this->next = $this->parent;
                    return [$this->parent->exec(null)];
                }
            }
            return [$msg];
        }
        /* NO manejo opciones definidas en $this->menu->options */
        if (!$this->menu->options) {
            return $this->magia();
            return [$msg, $this->parent->exec()];
        }

        // SI manejo opciones pero no me llego ningun
        if ($option === null) {
            $this->next = $this;
            if ($this->menu->options) {
                foreach ($this->menu->options as $opt => $rst) {
                    $msg .= $opt . " - " . $rst->name . "\n";
                }
            }
            return [$msg];
        }

        // limpio las opciones, longitud maxima 5;
        $opt = mb_strtoupper(mb_substr($option, 0, 5));
        if (is_object($this->menu->options) and property_exists($this->menu->options, $opt)) {

            // Menu anterior 
            if ($this->menu->options->$opt->action == 'back') {
                $this->next = $this->parent;
                return $this->next->exec();
            }
            $obj = $this->menu->options->$opt->type;
            $this->next = new $obj($this->menu->options->$opt->action);

            // $obj = get_class($this);
            // $this->next = new $obj($this->menu->options->$opt->action);
            $this->next->parent = $this;
            return $this->next->exec();
        } else { //accion por defecto
            return [$this->menu->error];
        }
    }

    function magia($data = null)
    {
        return ["Hice magia", $this->parent->exec()];
    }

    function validate($option = null)
    {
        if (!is_numeric($option))
            return false;
        if ($option <= 0 or $option > $this->totalopts + 1)
            return false;
    }

    function __set($name, $value)
    {
        switch ($name) {
            case "parent":
                $this->$name = $value;
                if (isset($value->bagage))
                    $this->bagage = $value->bagage;
                break;
            default:
                $this->$name = $value;
        }
    }
}
