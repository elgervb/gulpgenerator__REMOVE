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
use compact\filesystem\exceptions\FileNotFoundException;
use compact\http\HttpSession;

class GulpfileController
{

    private static $instance;

    /**
     * Returns the GulpfileController singleton
     *
     * @return GulpfileController
     */
    public static function instance()
    {
        if (self::$instance === null) {
            self::$instance = new GulpfileController();
        }
        
        return self::$instance;
    }

    /**
     * Creates a new reporitory to store the models in
     *
     * @param $guid The
     *            guid of the gulpfile
     * @param
     *            createIfNotExists boolean to denote if the file should be created when it doesn't exists yet
     *            
     * @throws FileNotFoundException when createIfNotExists = false (by default) and the file does not exist
     *        
     * @return \compact\repository\IModelRepository
     */
    private function createDb($guid, $createIfNotExists = false)
    {
        assert(strlen($guid) > 10);
        
        $filepath = Context::get()->basePath('app/db/' . $guid . '.json');
        
        if (! $createIfNotExists && ! $filepath->isFile()) {
            throw new FileNotFoundException($filepath);
        }
        
        return new JsonRepository(new DefaultModelConfiguration(), $filepath);
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
        $db = $this->createDb($guid, true); // create new file
                                            
        // TODO implement 409
        $model = ModelUtils::getPostForDynamicModel(new Model());
        
        $isEmpty = true;
        foreach ($model as $key => $value) {
            $isEmpty = false;
            break;
        }
        
        if ($isEmpty) {
            return new HttpStatus(HttpStatus::STATUS_204_NO_CONTENT, array(
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
     * Add a task to the gulpfile 
     * 
     * @param string $guid
     * 
     * @return \compact\handler\impl\http\HttpStatus with the added task
     */
    public function addtask($guid)
    {
        $task = ModelUtils::getPostForDynamicModel(new Model());
        
        // no task present in request body...
        if ($task->isEmpty('type')){
            return new HttpStatus(HttpStatus::STATUS_204_NO_CONTENT);
        }
        
        $db = $this->createDb($guid);
        
        $sc = $db->createSearchCriteria();
        if ($guid) {
            $sc->where("guid", $guid);
        }
        
        $result = $db->search($sc);
        if ($result->count() > 0) {
            
            $gulpfile = $result->offsetGet(0); // get first model
            
            $tasks = $gulpfile->get('tasks');
            if (! is_array($tasks)) {
                $tasks = [];
            }
            
            // add task
            $tasks[] = $task;
            
            $gulpfile->set('tasks', $tasks);
            
            $db->save($gulpfile);
            
            return new HttpStatus(HttpStatus::STATUS_200_OK, new Json($task));
        }
        
        return new HttpStatus(HttpStatus::STATUS_204_NO_CONTENT);
    }

    /**
     * Returns all predefined tasks
     *
     * @return \compact\handler\impl\json\Json
     */
    public function getPredefinedTasks()
    {
        return new Json(json_decode(file_get_contents(__DIR__ . "/tasks/tasks.json")));
    }

    public function download($guid)
    {
        $g = new GulpTasks();
        
        return new Download($g->generate($guid), 'gulpfile.js', Download::DOWNLOAD_MIME_TYPE);
    }
}