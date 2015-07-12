<?php
namespace app;

use compact\IAppContext;
use compact\Context;
use compact\routing\Router;
use compact\mvvm\impl\ViewModel;
use gulp\GulpTasks;

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
        $router->add("^/$", function(){
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
        //
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
