<?php
class HomeController extends AppController {

    // which doctrine models are going to be used in any of the actions
    // NB! some of them are loaded in AppController.php, see $globalModels
    public $models = ['TextPage'];

    ///////////////////////////////////////////////////////////////////////////
    public function index() {
        $entity = $this->TextPage->findOneBy(['slug' => 'home']);
        $page   = $entity->getDetails();

        // add +1 to the read count of the page and fetch comments
        $entity->incrementReadCount();
        $this->getEntityManager()->flush();

        $vars = [
            'title' => $page['title'],
            'body'  => $page['body'],
            'nohr'  => true,
        ];

        $this->smarty->showPage('textpage.tpl', $vars);
    }

}