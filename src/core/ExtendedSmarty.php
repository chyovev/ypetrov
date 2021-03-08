<?php
class ExtendedSmarty extends Smarty {

    ///////////////////////////////////////////////////////////////////////////
    public function renderJSONContent($array): void {
        if (!is_array($array)) {
            $array = [$array];
        }

        header('Content-Type: application/json');
        print(json_encode($array));
        exit;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function showPage(string $contentFile, array $viewVars = []): void {
        $page = $this->loadPage($contentFile, $viewVars);
        print($page);
        die;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function loadPage(string $contentFile, array $viewVars = []): string {
        $this->assign($viewVars);

        $layout  = $this->fetch('layout/header.tpl');
        $layout .= $this->fetch($contentFile);
        $layout .= $this->fetch('layout/footer.tpl');

        return $layout;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function loadTemplate(string $contentFile, array $viewVars = []): string {
        $this->assign($viewVars);

        return $this->fetch($contentFile);
    }

    ///////////////////////////////////////////////////////////////////////////
    public function showTemplateAsPlainText(string $contentFile, array $viewVars = []): void {
        $text = $this->loadTemplate($contentFile, $viewVars);

        header('Content-Type: text/plain');
        print($text);
        die;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function showTemplateAsXml(string $contentFile, array $viewVars = []): void {
        $text = $this->loadTemplate($contentFile, $viewVars);

        header("Content-Type:text/xml");
        print($text);
        die;
    }

}
