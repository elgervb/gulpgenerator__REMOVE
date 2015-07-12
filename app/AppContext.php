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
         * Add a new gulpfile
         */
        $router->add("^/gulpfile$", function(){
        	return \gulp\GulpfileController::get()->post();
        }, 'POST');
        
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
