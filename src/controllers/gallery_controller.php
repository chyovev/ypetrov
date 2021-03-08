<?php
class GalleryController extends AppController {

    public $models = ['Gallery'];

    ///////////////////////////////////////////////////////////////////////////
    public function index() {
        $allGalleryImages  = $this->Gallery->getAllActiveGalleryImages();

        $metaTitle = 'Галерия';
        $metaDesc  = 'Снимки от живота на Йосиф Петров';

        $vars = [
            'metaTitle' => $metaTitle,
            'metaDesc'  => $metaDesc,
            'images'    => $allGalleryImages,
        ];

        $this->smarty->showPage('gallery.tpl', $vars);

    }
}
