<?php
namespace app;

class view_helpers
{
    public function get_label($label, $labels)
    {
        if (is_object($labels) && isset($labels->$label)) {
            return $labels->$label;
        }
        return $label;
    }
}