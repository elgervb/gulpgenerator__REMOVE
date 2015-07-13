<?php
namespace app;

use compact\IAppContext;
use compact\Context;
use compact\routing\Router;
use compact\mvvm\impl\ViewModel;
use gulp\GulpTasks;
use gulp;
use compact\handler\impl\json\JsonHandler;

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
        
        /**
         * Get an existing gulpfile
         * 
         * url /gulpfile/2191B876-84A0-DB62-FBBD-8BD9D0584887
         */
        $router->add("^/gulpfile/(".self::GUID_REGEX.")$", function($guid){
            return \gulp\GulpfileController::instance()->get($guid);
        }, 'GET');
        
        /**
         * Add a new gulpfile
         */
        $router->add("^/gulpfile$", function(){
        	return \gulp\GulpfileController::instance()->post();
        }, 'POST');
        
        /**
         * Returns all predefined tasks
         */
        $router->add("^/tasks$", function(){
        	return \gulp\GulpfileController::instance()->getTasks();
        }, 'GET');
        
        $router->add("^/generator$", function(){
            $g = new GulpTasks();
        	return "<pre>".$g->generate()."</pre>";
        });
        
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
