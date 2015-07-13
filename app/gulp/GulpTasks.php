<?php
namespace gulp;

use compact\mvvm\impl\ViewModel;

class GulpTasks
{

    /**
     * Fetches a template for the gulp task
     *
     * @param string $taskname The name of the gulp task
     *            
     * @return \compact\mvvm\impl\ViewModel
     * 
     * @throws \compact\filesystem\exceptions\FileNotFoundException when the template path does not exist
     */
    private function getTemplate($taskname)
    {
        return new ViewModel(__DIR__ . "/tasks/" . $taskname . '.js');
    }

  
    /**
     * Generate the Gulp file
     * 
     * @param string $configuration
     */
    public function generate(){
        
        $json = self::getTasks();
        $template = new ViewModel(__DIR__ . "/tasks/_template.txt");
        $result = "";
        foreach($json->tasks as $task){
            $t = clone $template;
            
            self::copyToView($task, $t);
            $t->function = $this->getTemplate($task->type);
            self::copyToView($task, $t->function);
            $t->dependencies = is_array($task->dependencies) ? implode(", ", $task->dependencies) : ""; 
            
            $result .= $t->render();
        }
        
        return $result;
    }
    
    /**
     * Returns all tasks as models
     * 
     * @return array with models
     */
    public static function getTasks(){
        return self::convertToModel(json_decode(file_get_contents(__DIR__ . "/tasks/tasks.json")));
    }
    
    /**
     * Converts the \stdClass JSON into a Model. This is needed because Model can handle undefined values and will not crash.
     * 
     * @param \stdClass $json
     * 
     * @return \compact\mvvm\impl\Model
     */
    private static function convertToModel(\stdClass $json){
        $temp = serialize($json);
        
        $temp = preg_replace('@O:8:"stdClass":@','O:24:"\compact\mvvm\impl\Model":',$temp);
        
        return unserialize($temp);
    }
    /**
     * Copy all fields of the model into the view
     * 
     * @param IModel $model
     * @param IView $view
     */
    private static function copyToView(\compact\mvvm\IModel $model, \compact\mvvm\IView $view){
        $vars = get_object_vars($model);
   
        foreach ($vars as $key => $value){
            $view->$key = $value;
        }
    }
}