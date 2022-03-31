<?php

use Phalcon\Mvc\Controller;


class IndexController extends Controller
{
    public function indexAction()
    {
        
        $var = new SecureController();
        $var->BuildAction();
    }

    public function addRolesAction()
    {
        if ($this->request->isPost()) {
            $role = new Roles();
            $obj = new App\Components\Myescaper();

            $inputData = array(
                'role_field' => $obj->sanitize($this->request->getPost('role_field')),
            );

            $role->assign(
                $inputData,
                [
                    'role_field'
                ]
            );

            $success = $role->save();

            $this->view->success = $success;

            if ($success) {
                $this->view->message = "Role added successfully";
            } else {
                $this->mainLogger->error("Role not added due to following reason: <br>" . implode("<br>", $role->getMessages()));
                $this->view->message = "Role not added due to following reason: <br>" . implode("<br>", $role->getMessages());
            }
        }
    }

    public function addComponentsAction()
    {
        if ($this->request->isPost()) {
            $component = new Components();
            $obj = new App\Components\Myescaper();

            $inputData = array(
                'controller' => $obj->sanitize($this->request->getPost('controller')),
                'action' => $obj->sanitize($this->request->getPost('action'))
            );

            $component->assign(
                $inputData,
                [
                    'controller',
                    'action'
                ]
            );

            $success = $component->save();

            $this->view->success = $success;

            if ($success) {
                $this->view->message = "Component added successfully";
            } else {
                $this->mainLogger->error("Component not added due to following reason: <br>" . implode("<br>", $component->getMessages()));
                $this->view->message = "Component not added due to following reason: <br>" . implode("<br>", $component->getMessages());
            }
        }
    }

    public function ACLAction()
    {
        // if ($this->request->isPost()) {
        //     $arr = $this->request->getPost();
        //     $this->response->redirect('secure/build',$arr);
        //     // print_r($arr);
        //     // die;
        //     // $this->dispatcher->forward(
        //     //     [
        //     //         'controller' => 'Secure',
        //     //         'action' => 'Build',
        //     //         'params' => $arr
        //     //     ]
        //     // );
        // }
    }
}
