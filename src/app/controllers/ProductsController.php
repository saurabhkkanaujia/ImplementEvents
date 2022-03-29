<?php

use Phalcon\Mvc\Controller;

class ProductsController extends Controller
{

    public function IndexAction()
    {
    }

    public function addProductAction()
    {
        $product = new Products();
        $obj = new App\Components\Myescaper();

        $inputData = array(
            'name' => $obj->sanitize($this->request->getPost('name')),
            'description' => $obj->sanitize($this->request->getPost('description')),
            'tags' => $obj->sanitize($this->request->getPost('tags')),
            'price' => $obj->sanitize($this->request->getPost('price')),
            'stock' => $obj->sanitize($this->request->getPost('stock'))
        );

        $product->assign(
            $inputData,
            [
                'name',
                'description',
                'tags',
                'price',
                'stock'
            ]
        );


        $success = $product->save();

        $this->view->success = $success;
        $id = json_decode(json_encode($product))->id;

        if ($success) {
            $eventsManager = $this->di->get('EventsManager');
            $eventsManager->fire('notifications:setDefaultZipcode', $this);

            $this->view->message = "Product added successfully";
        } else {
            $this->mainLogger->error("Product not added due to following reason: <br>" . implode("<br>", $product->getMessages()));
            $this->view->message = "Product not added due to following reason: <br>" . implode("<br>", $product->getMessages());
        }
    }

    public function listProductsAction() {
        $this->view->data = Products::find();
    }
}
