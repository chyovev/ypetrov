<?php

namespace App\API\Http\Controllers;

use File;
use App\Http\Controllers\Controller;

class SwaggerController extends Controller
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Endpoint to access Swagger-UI's interface locally.
     */
    public function swagger_interface() {
        return view('swagger-ui/index');
    }
    
    ///////////////////////////////////////////////////////////////////////////
    /**
     * Endpoint to access the project's Swagger YAML definition.
     */
    public function swagger_docs() {
        $jsonFile = storage_path('swagger/swagger.yaml');
        $content  = File::get($jsonFile);

        return response($content)->header('Content-Type', 'application/x-yaml');
    }

}
