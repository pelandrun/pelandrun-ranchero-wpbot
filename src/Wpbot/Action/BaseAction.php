<?php
namespace Ranchero\Wpbot\Action;

class BaseAction
{

    var $isfirsttime = true;

    function __construct($definitions = null)
    {
        if (!$definitions) {
            $class_name = get_class($this);
            $topname = explode("\\", $class_name);
            $class_name = array_pop($topname);
            $definitions = $class_name;
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
        $this->next = $this;
        if ($this->isfirsttime) {
            return $this->firsttime();
        }
        if (!$option and $this->menu->needOption) {
            return [$this->menu->error, $this->menu->msg];
        }

        return $this->process($option);
    }

    function firsttime()
    {
        $this->isfirsttime = false;
        return [$this->menu->msg];
    }

    function process($option)
    {
        $opt = mb_strtoupper(mb_substr($option, 0, 64));
        if (!is_numeric($opt)) {
            return [$this->menu->error, $this->menu->msg];
        }
        if ($opt % 2 == 0) {
            $rst = "Es par!\n";
        } else {

            $rst = "Es Impar!\n";
        }
        $this->next = $this->parent;
        return array_merge([$rst], $this->parent->exec());
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
