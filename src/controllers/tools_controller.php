<?php
class ToolsController extends AppController {

    public $models = ['BookContent'];

    ///////////////////////////////////////////////////////////////////////////
    public function robots() {
        $this->smarty->showTemplateAsPlainText('robots.tpl');
    }

    ///////////////////////////////////////////////////////////////////////////
    public function sitemap() {
        $booksPoems = $this->BookContent->getActiveBooksAndPoemsSlugs();

        $vars = [
            'booksPoems' => $booksPoems,
        ];

        $this->smarty->showTemplateAsXml('sitemap.tpl', $vars);
    }

}