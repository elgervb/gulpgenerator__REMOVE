<?php
namespace app;

use compact\IAppContext;
use compact\Context;
use compact\routing\Router;
use compact\mvvm\impl\ViewModel;
use gulp\GulpTasks;
use gulp;
use compact\handler\impl\json\JsonHandler;
use compact\handler\impl\download\Download;
use compact\handler\impl\download\DownloadHandler;

/**
 * The Application Context
 *
 * @author eaboxt
 */
class AppContext implements IAppContext
{
    /**
     * Regex to match a guid: 4ddb9da0-a641-471d-a926-221a7a33b0ec
     * @var string
     */
    const GUID_REGEX = "[a-zA-Z0-9]{8}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{12}";
    
    /**
     * (non-PHPdoc)
     *
     * @see \compact\IAppContext::routes()
     */
    public function routes(Router $router)
    {
        // Enable CORS preflight request
        $router->add('.*', function() {
            // allow CORS
            Context::get()->http()->getResponse()->setCORSHeaders();
            return " ";
        }, 'OPTIONS');
        
        /*
         * Get an existing gulpfile
         * 
         * url /gulpfile/2191B876-84A0-DB62-FBBD-8BD9D0584887
         */
        $router->add("^/gulpfile/(".self::GUID_REGEX.")$", function($guid){
            return \gulp\GulpfileController::instance()->get($guid);
        }, 'GET');
       
        /*
         * Add a new gulpfile
         */
        $router->add("^/gulpfile$", function(){
        	return \gulp\GulpfileController::instance()->post();
        }, 'POST');
        
        /*
         * Generate the gulp task
         * 
         * url /generate/2191B876-84A0-DB62-FBBD-8BD9D0584887
         */
        $router->add("^/generate/(".self::GUID_REGEX.")$", function($guid){
            return \gulp\GulpfileController::instance()->download($guid);
        }, 'GET');
        
        
        /*
         * Returns all predefined tasks
        */
        $router->add("^/tasks$", function(){
            return \gulp\GulpfileController::instance()->getTasks();
        }, 'GET');
        
        /*
         * Add a task to an existing gulp file
         * 
         * url: /tasks/25A5E4D5-B7B2-BF8B-28FE-854E8E56C4C4
        */
        $router->add("^/tasks/(".self::GUID_REGEX.")$", function($guid){
            return \gulp\GulpfileController::instance()->addtask($guid);
        }, 'PUT');
        
        /**
         * Errors
         */
        $router->add(404, function ()
        {
            return new ViewModel('404.html');
        });
        $router->add(500, function ()
        {
            return new ViewModel('500.html');
        });
    }

    /**
     * (non-PHPdoc)
     *
     * @see \compact\IAppContext::handlers()
     */
    public function handlers(Context $ctx)
    {
        // Handle all JSON responses
        $ctx->addHandler(new JsonHandler());
        
        // Handle downloads
        $ctx->addHandler(new DownloadHandler());
    }

    /**
     * (non-PHPdoc)
     *
     * @see \compact\IAppContext::services()
     */
    public function services(Context $ctx)
    {
        if ($ctx->isLocal()){
            $ctx->http()->getResponse()->setCORSHeaders();
        }
    }
}
