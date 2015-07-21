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
use compact\handler\impl\download\Download;
class GulpfileController
{
    private static $instance;
    
    /**
     * Returns the GulpfileController singleton
     * 
     * @return GulpfileController
     */
    public static function instance(){
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
    private function createDb($guid){
        assert(strlen($guid) > 10);
        
        return new JsonRepository(new DefaultModelConfiguration(), Context::get()->basePath('app/db/'.$guid.".json"));
    }
    
    /**
     * Returns all links or just one when the GUID has been set
     *
     * @param $guid [optional]
     *            The guid of the link
     *
     * @return HttpStatus 200 | 204 //
     *         200 with JSON of one model when $guid not is null else it will return a resultset with models
     *         204 no content when there are no models in the database or the id is not known
     */
    public function get($guid)
    {
        $db = $this->createDb($guid);
        $sc = $db->createSearchCriteria();
    
        if ($guid) {
            $sc->where("guid", $guid);
        }
    
        $result = $db->search($sc);
        if ($result->count() > 0) {
            return new HttpStatus(HttpStatus::STATUS_200_OK, new Json($result));
        }
        return new HttpStatus(HttpStatus::STATUS_204_NO_CONTENT);
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
    
    /**
     * Returns all predefined tasks
     * 
     * @return \compact\handler\impl\json\Json
     */
    public function getTasks(){
        return new Json(json_decode(file_get_contents(__DIR__ . "/tasks/tasks.json")));
    }
    
    public function download($guid){
        $g = new GulpTasks();
        
        return new Download($g->generate(), 'gulpfile.js', Download::DOWNLOAD_MIME_TYPE);
    }
}