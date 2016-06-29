<?php

require_once "../vendor/autoload.php";
$container = new \Pimple\Container();

$container['data_file_address'] = "../data/contacts.json";

$container['logger'] = function ($c) {
    return new \Monolog\Logger('main');
};
$container['data_loader'] = function ($c) {
    return new app\json_loader($c, $c['logger']);
};
$container['contact_controller'] = function (\Pimple\Container $c) {
    return new app\controller($c);
};
$container['contact_view'] = function (\Pimple\Container $c) {
    return new app\view($c);
};
$container['view_helpers'] = function (\Pimple\Container $c) {
    return new app\view_helpers($c);
};

// Contacts Address Book
?>
    <h1>Contacts Address Book Interview Test</h1>
    <ul>
        <li>
            Create a data class to handle queries to the data source (JSON file /data/contacts.json)
            <br/>This can be split into a model and a controller for the different actions but isn&apos;t required for
            the purposes of the test
            <br/>Methods should be created for:
            <ul>
                <li>Retrieving a list of contacts</li>
                <li>Retrieving an individual contact</li>
                <li>Updating an individual contact</li>
                <li>Adding new data fields on a per contact basis and updating the label object</li>
                <li>Deleting data fields</li>
                <li>Deleting contacts</li>
            </ul>
        </li>
        <li>Create a view class that demonstrates the preparation of the data</li>
        <li>The code doesn&apos;t need to be completed in the time, but a clear structure of all methods would be good
            to demonstrate understanding of the task
        </li>
        <li>Unit testing is not required but comments to explain where tests, improvements and performance enhancements
            can be made will help
        </li>
        <li>The class structure has been started, you can refine these and create new items as required</li>
    </ul>

    <h1>List of Contacts</h1>

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
        }
        break;

    case 'update':
        if (!empty($_GET['id'])) {
            $container['contact_controller']->update_contact_action($_GET['id'], (!empty($_POST) ? $_POST : null));
        } else {
            header("HTTP/1.0 400 No Contact ID");
        }
        break;

    case 'delete':
        if (!empty($_GET['id'])) {
            $container['contact_controller']->delete_contact_action($_GET['id']);
        } else {
            header("HTTP/1.0 400 No Contact ID");
        }
        break;

    case 'label':
        if (empty($_POST)) {
            header("HTTP/1.0 400 No Contact ID");
        }
        $container['contact_controller']->set_label_action($_POST);

    default:
        header("HTTP/1.0 404 Not Found");
}
