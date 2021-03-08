<?php

// class should not initiateable, all methods are static
abstract class Router {

    private static $requestParams = [];

    private static $routes = [
        '/'                            => ['controller' => 'home',      'action' => 'index'],
        '/tvorchestvo/:book'           => ['controller' => 'books',     'action' => 'view'],
        '/tvorchestvo/:book/:poem'     => ['controller' => 'poems',     'action' => 'view'],
        '/video/:video'                => ['controller' => 'videos',    'action' => 'view'],
        '/galeriya'                    => ['controller' => 'gallery',   'action' => 'index'],
        '/tarsene'                     => ['controller' => 'search',    'action' => 'index'],
        '/presa/:press'                => ['controller' => 'press',     'action' => 'view'],
        '/za-yosif-petrov/:essay'      => ['controller' => 'essays',    'action' => 'view'],
        '/kontakt'                     => ['controller' => 'contact',   'action' => 'index'],
        '/captcha'                     => ['controller' => 'captcha',   'action' => 'index'],
        '/sitemap.xml'                 => ['controller' => 'tools',     'action' => 'sitemap'],
        '/robots.txt'                  => ['controller' => 'tools',     'action' => 'robots'],
        '/:textpage'                   => ['controller' => 'textpages', 'action' => 'view'],
    ];

    ///////////////////////////////////////////////////////////////////////////////
    /**
     * Tries to generate a URL out of parmeters
     *
     * @param  array $params Parameters for the url (controller, action, etc.)
     * @return string route URL
     */
    public static function url(array $params, bool $includeHost = false): string {
        // filter out empty paremeters
        $params = array_filter($params);
        $route  = self::findRouteMatchForParams($params);
        $url    = $route;

        // if there's no matching route specified, generate a default url
        if ( ! $route) {
            return self::generateNoRouteMatchUrl($params);
        }

        // which parameters does the root expect for a complete match
        $neededParams  = self::getNeededRouteParams($route);
        $missingParams = array_diff($neededParams, array_keys($params));
        $extraParams   = array_diff(array_keys($params), $neededParams);

        if ($missingParams) {
            throw new Exception('Missing parameters: ' . implode(', ', $missingParams));
        }

        // replace the provided parameters in the matched route
        foreach ($neededParams as $param) {
            $value   = $params[$param];
            $pattern = sprintf('/:%s/', preg_quote($param));
            $url     = preg_replace($pattern, $value, $url);
        }

        // remove left slash as ROOT provides it
        $url  = WEBROOT . ltrim($url, '/');

        // add any extra parameters as query params
        $url .= self::generateQueryParameters(array_intersect_key($params, array_flip($extraParams))); 

        return $includeHost ? HOST_URL . $url : $url;
    }

    ///////////////////////////////////////////////////////////////////////////////
    private static function findRouteMatchForParams(array $params): string {
        $essential = self::getEssentialParams($params);

        return array_search($essential, self::$routes);
    }

    ///////////////////////////////////////////////////////////////////////////////
    private static function getEssentialParams(array $params): array {
        if ( ! isset($params['controller']) || ! $params['controller']) {
            throw new Exception('No controller provided for URL generation.');
        }

        if ( ! isset($params['action']) || ! $params['action']) {
            throw new Exception('No action provided for URL generation.');
        }

        return [
            'controller' => $params['controller'],
            'action'     => $params['action'],
        ];
    }

    ///////////////////////////////////////////////////////////////////////////////
    private static function generateNoRouteMatchUrl(array $params): string {
        $essential    = self::getEssentialParams($params);
        $nonEssential = array_diff($params, $essential);
        $queryParams  = self::generateQueryParameters($nonEssential);

        // base url consist of just the controller        
        $url          = WEBROOT . $essential['controller'];

        // if the url is going to end on /index,
        // skip the action part and append just the query parameters
        $url .= ($essential['action'] === 'index')
                ? $queryParams
                : '/' . $essential['action'] . ltrim($queryParams, '/');

        return $url;
    }

    ///////////////////////////////////////////////////////////////////////////////
    private static function getNeededRouteParams(string $url): array {
        // controller and action are always needed
        $needed = ['controller', 'action'];

        // check for any additional params (:param) which need to be present
        preg_match_all('/:([^\/]+)/', $url, $params);

        return array_merge($needed, $params[1]);
    }

    ///////////////////////////////////////////////////////////////////////////////
    /**
     * Generates query URL paremeters from array.
     *
     * @param  array $params
     * @return string query: /?var1=hello&var2=world
     */
    public static function generateQueryParameters(array $params): string {
        if ( ! $params) {
            return '';
        }

        return '/?' . http_build_query($params);
    }


    ///////////////////////////////////////////////////////////////////////////////
    //                            REQUEST PROCESSING                             //
    ///////////////////////////////////////////////////////////////////////////////


    ///////////////////////////////////////////////////////////////////////////////
    public static function getCurrentRequestParams(): array {
        $url     = self::getCurrentRequestUrl();
        $request = self::getParamsByMatchingRoute($url);

        if ($request) {
            return $request;
        }

        // if there's no matching route,
        // provide default ones: /controller/action
        $urlParts = self::splitUrl($url);

        if (count($urlParts) <= 2) {
            return [
                'controller' => $urlParts[0] ?? 'home',
                'action'     => $urlParts[1] ?? 'index'
            ];
        }

        throw new Exception('Route is not declared');
    }

    ///////////////////////////////////////////////////////////////////////////////
    public static function getCurrentRequestUrl(bool $stripWebroot = true) {
        $request = $_SERVER['REQUEST_URI'];

        // replace WEBROOT with a single / slash
        if ($stripWebroot) {
            $request = preg_replace('/^(' . preg_quote(WEBROOT, '/') . ')/', '/', $request);
        }

        // remove query part
        return preg_replace('/(\/?\?.*)/', '', $request);
    }

    ///////////////////////////////////////////////////////////////////////////////
    // cycle through all defined routes and check if URL matches any of them
    public static function getParamsByMatchingRoute(string $url): ?array {
        foreach (self::$routes as $route => $params) {
            // base route params are part of the current request params
            self::$requestParams = $params;

            if (self::doesUrlMatchRoute($url, $route)) {
                return self::$requestParams;
            }
        }

        return NULL;
    }

    ///////////////////////////////////////////////////////////////////////////////
    public static function doesUrlMatchRoute(string $url, string $route): bool {
        // if there are no special parameters (:param)
        if ($url === $route) {
            return true;
        }

        $urlParts   = self::splitUrl($url);
        $routeParts = self::splitUrl($route);

        if (count($urlParts) != count($routeParts)) {
            return false;
        }

        // cycle through all parts of the url
        // and check if they correspond with the same parts of the route
        foreach ($urlParts as $pos => $value) {
            $isPartParameter = preg_match('/^:.+/', $routeParts[$pos]);

            // if the part is not a special parameter (:param),
            // but the url and route parts don't match,
            // then URL does not match route
            if ( ! $isPartParameter && $value !== $routeParts[$pos]) {
                return false;
            }

            // if the part is a special parameter,
            // add its value to the request params array
            elseif ($isPartParameter) {
                self::addRequestParam($routeParts[$pos], $value);
            }
        }

        return true;
    }


    ///////////////////////////////////////////////////////////////////////////////
    // remove any leading and trailing slashes, separate by / and filter out empty parts
    private static function splitUrl(string $url): array {
        $trimmedUrl = trim($url, '/');
        $parts      = explode('/', $trimmedUrl);

        return array_filter($parts);
    }

    ///////////////////////////////////////////////////////////////////////////////
    private static function addRequestParam(string $param, string $value): void {
        // remove double dots
        $param = preg_replace('/^(:)/', '', $param);

        self::$requestParams[$param] = $value;
    }

    ///////////////////////////////////////////////////////////////////////////////
    public static function getRequestParams(): array {
        return self::$requestParams;
    }

    ///////////////////////////////////////////////////////////////////////////////
    public static function getRequestParam(string $param): ?string {
        $requestParams = self::$requestParams;
        return $requestParams[$param] ?? NULL;
    }

    ///////////////////////////////////////////////////////////////////////////////
    public static function isRequest(string $type): bool {
        if (mb_strtolower($type, 'utf-8') === 'ajax') {
            return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
        }
        return (strtolower($_SERVER['REQUEST_METHOD']) === strtolower($type));
    }

    ///////////////////////////////////////////////////////////////////////////////
    public static function getQueryParams(array $vars = []): array {
        if ( ! $vars) {
            return $_GET;
        }

        $result = [];

        foreach ($_GET as $key => $value) {
            if (in_array($key, $vars)) {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    ///////////////////////////////////////////////////////////////////////////////
    public static function getQueryParam(string $param): ?string {
        $query = self::getQueryParams([$param]);
        return $query[$param] ?? NULL;
    }

    ///////////////////////////////////////////////////////////////////////////////
    public static function getPostParams(array $vars = []): array {
        if ( ! $vars) {
            return $_POST;
        }

        $result = [];

        foreach ($_POST as $key => $value) {
            if (in_array($key, $vars)) {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    ///////////////////////////////////////////////////////////////////////////////
    public static function getPostParam(string $param): ?string {
        $post = self::getPostParams([$param]);
        return $post[$param] ?? NULL;
    }

    ///////////////////////////////////////////////////////////////////////////////
    public static function processRequest(): void {
        $smarty   = Initializer::smarty();
        $doctrine = Initializer::doctrine();

        // try to process current request to get controller and action out of it
        // and then call the respective action method in controller class
        try {
            $params = self::getCurrentRequestParams();
            $controller      = $params['controller'];
            $action          = $params['action'];

            self::callControllerAction($controller, $action, $smarty, $doctrine);
        }
        catch (Exception $e) {
            $errorMessage = $e->getMessage();
        }

        // if the request has failed for some reason,
        // header should be 404
        header('HTTP/1.1 404 Not Found'); 

        // log the error on production
        // and show 404 page
        if (IS_PROD) {
            Logger::logError($errorMessage);
            $smarty->showPage('layout/error404.tpl');
        }

        // otherwise just display the error message
        else {
            die($errorMessage);
        }
    }

        ///////////////////////////////////////////////////////////////////////////////
    protected static function callControllerAction(string $controller, string $action, ExtendedSmarty $smarty, $doctrine): void {
        self::loadControllerFile($controller);
        self::checkControllerAndActionCallability($controller, $action);

        $controllerClass = self::getControllerClass($controller);

        $class = new $controllerClass($smarty, $doctrine);
        
        $smarty->assign('_controller', $controller);
        $smarty->assign('_action', $action);
        $smarty->assign('_url', self::getCurrentRequestUrl(false));
        $class->preRender();
        $class->{$action}();
    }

    ///////////////////////////////////////////////////////////////////////////////
    protected static function loadControllerFile(string $controller): void {
        $controllerFile     = $controller . '_controller.php';
        $controllerFullPath = ROOT . '/src/controllers/' . $controllerFile;

        if ( ! file_exists($controllerFullPath)) {
            throw new Exception('Controller file not found: ' . $controllerFile);
        }

        require_once($controllerFullPath);

    }

    ///////////////////////////////////////////////////////////////////////////////
    protected static function getControllerClass(string $controller): string {
        return ucfirst($controller) . 'Controller';
    }

    ///////////////////////////////////////////////////////////////////////////////
    protected static function checkControllerAndActionCallability(string $controller, string $action): void {
        $controllerClass = self::getControllerClass($controller);

        // check if the class exists
        if ( ! class_exists($controllerClass)) {
            throw new Exception('Controller class not declared: ' . $controllerClass);
        }

        // check if the method exists
        if ( ! method_exists($controllerClass, $action)) {
            throw new Exception(sprintf('Action method «%s» of controller «%s» is not declared', $action, $controllerClass));
        }

        // check if the method is declared as public
        $reflection = new ReflectionMethod($controllerClass, $action);
        if ( ! $reflection->isPublic()) {
            throw new Exception(sprintf('Action method «%s» of controller «%s» is not declared as public', $action, $controllerClass));
        }
    }



}
