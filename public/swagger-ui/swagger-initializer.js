window.onload = function() {
    window.ui = SwaggerUIBundle({
        url:          documentUrl, // defined in layout file
        dom_id:       "#swagger-ui",
        deepLinking:  true,
        validatorUrl: "none",
        presets: [
            SwaggerUIBundle.presets.apis,
            SwaggerUIStandalonePreset,
        ],
        plugins: [
            SwaggerUIBundle.plugins.DownloadUrl,
        ],
        layout: "StandaloneLayout",

        // users using the application are authenticated via a
        // session which expects a CSRF token with each request;
        // add it automatically using an interceptor
        requestInterceptor: function(request) {
            request.headers['X-CSRF-TOKEN'] = csrfToken; // defined in layout file

            return request;
        },
    });
};