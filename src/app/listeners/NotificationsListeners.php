<?php

namespace App\Listeners;

use Phalcon\Url;
use OrderController;
use Orders;
use Phalcon\Di\Injectable;
use Phalcon\Events\Event;
use Products;
use ProductsController;
use Settings;

class NotificationsListeners extends Injectable
{
    public function setDefault(
        Event $event,
        ProductsController $component,
        $id
    ) {
        
        // echo $data;
        // print_r(json_decode(json_encode($component)));
        // die();
        $product = Products::find([
            'conditions' => 'id= :id:',
            'bind' => [
                'id' => $id,
            ]
        ]);
        $settings = Settings::find();
        if ($settings[0]->title_optimization == 1) {

            $product[0]->name = $product[0]->name . $product[0]->tags;
            $product[0]->update();
        }

        if ($settings[0]->default_price != null && ($product[0]->price == null || $product[0]->price == 0)) {
            $product[0]->price = $settings[0]->default_price;
            $product[0]->update();
        }

        if ($settings[0]->default_stock != null && ($product[0]->stock == null || $product[0]->stock == 0)) {
            $product[0]->stock = $settings[0]->default_stock;
            $product[0]->update();
        }
    }

    public function setDefaultZipcode(
        Event $event,
        OrderController $component,
        $id
    ) {
        $settings = Settings::find();
        $order = Orders::findFirst($id);
        // print_r(json_decode(json_encode($order->zipcode)));
        // die();
        if ($settings[0]->default_zipcode != null && $order->zipcode == null) {
            $order->zipcode = $settings[0]->default_zipcode;
            $order->update();
        }
    }

    public function beforeHandleRequest(Event $event, \Phalcon\Mvc\Application $application)
    {
        $controller = ucwords($this->router->getControllerName());
        $action = $this->router->getActionName();

        $aclFile = APP_PATH . '/security/acl.cache';
        //Check whether ACL data already exist

        if (true === is_file($aclFile)) {

            $acl = unserialize(file_get_contents($aclFile));

            //Use ACL list as needed
            $role = $application->request->get("role")??'admin';
            if (!$role || true !== $acl->isAllowed($role, $controller, $action)) {
                echo $role;
                echo "Access Denied :(";
                die;
            }
        } else {
            $this->response->redirect('/secure/build');
            // echo "We don't find any ACL list. Try after some time";
            // die;
        }
        // $role = $application->request->get("role");
        // if (!$role || true !== $acl->isAllowed('admin', 'order', 'placeorder')) {
        //     echo "admin == prder=>placeorder granted:(";
        //     die;
        // }
    }
}
