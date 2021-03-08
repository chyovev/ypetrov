<?php
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

abstract class AppController {

    public $smarty;
    private $entityManager;

    // which models to be loaded instantly to use across the controllers
    // (in this case these models are used in the preRender method)
    private $globalModels = ['Book', 'Video', 'PressArticle', 'Essay', 'Comment'];

    ///////////////////////////////////////////////////////////////////////////
    public function __construct(Smarty $smarty, EntityManager $entityManager) {
        $this->smarty = $smarty;
        $this->entityManager = $entityManager;
        $this->initiateModels();
    }

    ///////////////////////////////////////////////////////////////////////////
    private function initiateModels(): void {
        $globalModels = $this->globalModels ?? []; // used across all controllers
        $localModels  = $this->models       ?? []; // used per controller

        // merge both global and local models and filter out dublicates
        $allModels    = array_unique(array_merge($globalModels, $localModels));

        // initialize all models and store them in {$model} variables
        foreach ($allModels as $model) {
            $this->{$model} = $this->getRepository($model);
        }
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getEntityManager(): EntityManager {
        return $this->entityManager;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getRepository(string $repository): EntityRepository {
        return $this->entityManager->getRepository($repository);
    }

    ///////////////////////////////////////////////////////////////////////////
    // this method is called before rendering the page,
    // useful for fetching all-pages data
    public function preRender() {
        $bookRepository  = $this->getRepository('Book');
        $videoRepository = $this->getRepository('Video');
        $pressRepository = $this->getRepository('PressArticle');
        $essayRepository = $this->getRepository('Essay');

        $navigation = [
            'articles' => $this->PressArticle->findActive(),
            'books'    => $this->Book->findActive(),
            'essays'   => $this->Essay->findActive(),
            'videos'   => $this->Video->findActive(),
        ];

        $this->smarty->assign(['navigation' => $navigation]);
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getMetaImageData(string $image): array {
        return [
            'url'  => $image,
            'size' => $this->getImageDimensions($image),
        ];
    }

    ///////////////////////////////////////////////////////////////////////////////
    public function getImageDimensions(string $image): array {
        // if the address does not start with http(s),
        // treat it as a local one: strip WEBROOT and add public/
        if ( ! preg_match('/^https?:\/\//', $image)) {
            $relativeImage = preg_replace('/^('.preg_quote(WEBROOT, '/').')/i', '', $image);
            $image         = ROOT . '/public/' . $relativeImage;
        }
        
        $dimensions = @getimagesize($image);

        if ($dimensions) {
            return ['width' => $dimensions[0], 'height' => $dimensions[1]];
        }

        return [];
    }

    ///////////////////////////////////////////////////////////////////////////
    // add +1 to the read count of the entity
    protected function incrementReadCount($entity): void {
        $entity->incrementReadCount();
        $this->getEntityManager()->flush();
    }

    ///////////////////////////////////////////////////////////////////////////
    // generate a code, save it as a session and pass the image to smarty
    protected function loadCaptcha() {
        $code = $this->generateCaptcha();
        $this->smarty->assign('captchaImg', $code->getImage());
    }

    ///////////////////////////////////////////////////////////////////////////
    protected function generateCaptcha(): Captcha {
        return new Captcha();
    }

    ///////////////////////////////////////////////////////////////////////////
    protected function _throw404OnEmpty($item): void {
        if ( ! $item) {
            throw new Exception('Trying to fetch non-existing or inactive database record.');
        }
    }

}