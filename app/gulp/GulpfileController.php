<?php
namespace gulp;

use compact\Context;
use compact\utils\ModelUtils;
use compact\handler\impl\http\HttpStatus;
use compact\logging\Logger;
use compact\handler\impl\json\Json;
use compact\repository\json\JsonRepository;
use compact\repository\DefaultModelConfiguration;
use compact\utils\Random;
use compact\mvvm\impl\Model;
class GulpfileController
{
    private static $instance;
    
    /**
     * Returns the GulpfileController singleton
     * 
     * @return GulpfileController
     */
    public static function get(){
        if (self::$instance === null){
            self::$instance = new GulpfileController();
        }
        
        return self::$instance;
    }
    
    /**
     * Creates a new reporitory to store the models in
     * 
     * @return \compact\repository\IModelRepository
     */
    private function createDb($id){
        assert(strlen($id) > 10);
        
        return new JsonRepository(new DefaultModelConfiguration(), Context::get()->basePath('app/db/'.$id.".json"));
    }
    
    /**
     * Stores a new model
     *
     * @return HttpStatus 201 | 204 | 401 | 409 | 422 //
     *         201: created with a location header to the new /model/{id} containing the new ID,
     *         204 no content: when no post data available,
     *         401 not authorized when the user is not logged in
     *         409 conflict on double entry
     *         422 Unprocessable Entity on validation errors
     */
    public function post()
    {
        $guid = Random::guid();
        $db = $this->createDb($guid);
        
        // TODO implement 409
        $model = ModelUtils::getPostForDynamicModel(new Model());
$model->guid = $guid;
        $isEmpty = true;
        foreach ($model as $key => $value){
            $isEmpty = false;
            break;
        }
       
        if ($isEmpty) {
            return new HttpStatus(HttpStatus::STATUS_204_NO_CONTENT,array(
                "message" => 'test'
            ));
        }
        $model->guid = $guid;

        try {
            if ($db->save($model)) {
                return new HttpStatus(HttpStatus::STATUS_201_CREATED, new Json($model));
            }
        } catch (ValidationException $e) {
            return new HttpStatus(HttpStatus::STATUS_422_UNPROCESSABLE_ENTITY, array(
                "message" => $e->getMessage()
            ));
        }
        
        Logger::get()->logWarning("Could not save model " . get_class($model));
        return new HttpStatus(HttpStatus::STATUS_204_NO_CONTENT);
    }
}