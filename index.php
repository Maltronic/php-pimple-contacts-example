<?php

require_once "vendor/autoload.php";

use Pimple\Container;
use Monolog\Logger;

$container = new Container();

$container['data_file_address'] = "data/contacts.json";

$container['logger'] = function ($c) {
    return new Logger('main');
};
$container['data_loader'] = function ($c) {
    return new pContactsExample\services\json_loader($c, $c['logger']);
};
$container['contact_controller'] = function (Container $c) {
    return new pContactsExample\controller($c);
};
$container['contact_view'] = function (Container $c) {
    return new pContactsExample\view($c);
};
$container['view_helpers'] = function (Container $c) {
    return new pContactsExample\services\view_helpers($c);
};

?>
    <h1>Contacts Address Book</h1>
    <br/>
    <h2>List of Contacts</h2>
    <br/>
<?php
if (empty($_GET['page'])) {
    $_GET['page'] = 'view_all';
}
switch ($_GET['page']) {
    case 'view_all':
        $container['contact_controller']->view_all_contacts_action();
        break;

    case 'view':
        if (!empty($_GET['id'])) {
            $container['contact_controller']->view_contact_action($_GET['id']);
        } else {
            header("HTTP/1.0 400 No Contact ID");
            die('400 No Contact ID');
        }
        break;

    case 'update':
        if (!empty($_GET['id'])) {
            $container['contact_controller']->update_contact_action($_GET['id'], (!empty($_POST) ? $_POST : null));
        } else {
            header("HTTP/1.0 400 No Contact ID");
            die('400 No Contact ID');
        }
        break;

    case 'delete':
        if (!empty($_GET['id'])) {
            $container['contact_controller']->delete_contact_action($_GET['id']);
        } else {
            header("HTTP/1.0 400 No Contact ID");
            die('400 No Contact ID');
        }
        break;

    case 'label':
        if (empty($_POST)) {
            header("HTTP/1.0 400 No Contact ID");
            die('400 No Contact ID');
        }
        $container['contact_controller']->set_label_action($_POST);
        break;

    default:
        header("HTTP/1.0 404 Not Found");
        die('404 Not Found');
}
