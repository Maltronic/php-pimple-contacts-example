<?php
namespace app;

use app\view_helpers;
use Pimple\Container;

class view
{
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function view_all($data)
    {
        $outp = "<ul>";
        if ($data['contacts']) {
            foreach ($data['contacts'] as $id => $person) {
                $outp .= "<li><a href='?page=view&id={$id}'>View Contact</a></li>";
                $outp .= "<li>";

                foreach ($person as $field => $value) {
                    if (!is_object($value) && !is_array($value)) {
                        $outp .= "{$this->labeller($field, $data['labels'])}: $value<br />";
                    }
                }
                if (is_array($person->contacts) && count($person->contacts) > 0) {
                    $outp .= "<ul>";
                    foreach ($person->contacts as $contact) {
                        foreach ($contact as $title => $val) {
                            $outp .= "<li>" . $this->labeller($title, $data['labels']) . ": $val</li>";
                        }
                    }
                    $outp .= "</ul>";
                }
                $outp .= "</li>";
            }
        }
        $outp .= "</ul>";
        return $outp;
    }

    public function view_contact($data)
    {
        $person = $data['person'];
        $outp = '';
        foreach ($person as $field => $value) {
            if (!is_object($value) && !is_array($value)) {
                $outp .= "{$this->labeller($field, $data['labels'])}: $value<br />";
            }
        }
        if (is_array($person->contacts) && count($person->contacts) > 0) {
            $outp .= "<ul>";
            foreach ($person->contacts as $contact) {
                foreach ($contact as $title => $val) {
                    $outp .= "<li>" . $this->labeller($title, $data['labels']) . ": $val</li>";
                }
            }
            $outp .= "</ul><a href='/public/index.php?page=update&id={$data['id']}'>update</a> <a href='/public/index.php?page=delete&id={$data['id']}'>delete</a> <a href='/public/index.php'>back</a>";
        }
        return $outp;
    }

    public function update_contact($data)
    {
        $person = $data['person'];
        $outp = "<form action='/public/index.php?page=update&id={$data['id']}' method='POST'>";
        foreach ($person as $field => $value) {
            if (!is_object($value) && !is_array($value)) {
                $outp .= "{$this->labeller($field, $data['labels'])}: <input type='text' name='$field' value='$value'><br />";
            }
        }
        $outp .= "<input type='text' name='new_field' value=''> <input type='text' name='new_value' value=''>";
        $outp .= "<input type=\"submit\" value=\"save\"></form>";
        if (is_array($person->contacts) && count($person->contacts) > 0) {
            $outp .= "<ul>";
            foreach ($person->contacts as $contact) {
                foreach ($contact as $title => $val) {
                    $outp .= "<li>" . $this->labeller($title, $data['labels']) . ": $val</li>";
                }
            }
            $outp .= "</ul><a href='/public/index.php?page=view&id={$data['id']}'>view</a> <a href='/public/index.php'>back</a>";
        }
        return $outp;
    }

    public function label_set($data)
    {
        $outp = "<br /><br /><form action='/public/index.php?page=label' method='POST'>Set a label: name: <input type='text' name='name' value=''> value: <input type='text' name='value' value=''> <input type=\"submit\" value=\"save\"></form>";
        return $outp;
    }

    private function labeller($label, $labels)
    {
        return $this->container['view_helpers']->get_label($label, $labels);
    }
}

