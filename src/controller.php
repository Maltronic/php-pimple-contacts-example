<?php

namespace pContactsExample;

use Pimple\Container;

class controller
{
    protected $container;
    protected $data;
    protected $url;

    public function __construct(Container $container)
    {
        $this->container = $container;

        $this->url = $this->container['data_file_address'];
        $data = $this->container['data_loader']->load($this->url);
        if (is_object($data)) {
            $this->data = $data;
            $this->contacts = $data->contacts;
            $this->labels = $data->labels;
        }
    }

    public function view_all_contacts_action()
    {
        $view_data = array(
            'contacts' => $this->data->contacts,
            'labels' => $this->labels
        );
        $this->show('view_all', $view_data);
    }

    public function view_contact_action($id)
    {
        $view_data = array(
            'id' => $id,
            'person' => $this->data->contacts->$id,
            'labels' => $this->labels
        );
        $this->show('view_contact', $view_data);
    }

    public function update_contact_action($id, $newData = null)
    {
        $contact = $this->data->contacts->$id;

        if (is_array($newData)) {
            if (!empty($newData['new_field']) && !empty($newData['new_value'])) {
                $contact->$newData['new_field'] = $newData['new_value'];
                unset($newData['new_field']);
                unset($newData['new_value']);
            }
            foreach ($newData as $item => $value) {
                $contact->$item = $value;
            }
            $this->data->contacts->$id = $contact;
            $this->container['data_loader']->save($this->url, $this->data);
            header("Location: /index.php?page=view&id=$id");
            die();
        }
        $view_data = array(
            'id' => $id,
            'person' => $this->data->contacts->$id,
            'labels' => $this->labels
        );
        $this->show('update_contact', $view_data);
    }

    public function delete_contact_action($id)
    {
        if (!isset($this->data->contacts->$id)) {
            header("HTTP/1.0 404 Not Found");
            die('404 Not Found');
        }
        unset($this->data->contacts->$id);
        $this->container['data_loader']->save($this->url, $this->data);
        header("Location: /index.php");
        die();
    }

    public function set_label_action($newData)
    {
        if (empty($newData['name'] || empty($newData['value']))) {
            header("HTTP/1.0 400 Bad data");
            die('400 Bad Data');
        }
        $this->labels->$newData['name'] = $newData['value'];
        $this->container['data_loader']->save($this->url, $this->data);
        header("Location: /index.php");
        die();
    }

    private function show($template, $view_data)
    {
        print $this->container['contact_view']->$template($view_data);
        print $this->container['contact_view']->label_set($view_data);
    }
}
