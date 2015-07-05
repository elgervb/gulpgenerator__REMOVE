<?php
namespace gulp;

use compact\mvvm\impl\ViewModel;

class GulpTasks
{

    /**
     * Fetches a template for the gulp task
     *
     * @param string $taskname
     *            The name of the gulp task
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
        
        $json = json_decode(file_get_contents(__DIR__ . "/tasks/task.json"));
        $template = new ViewModel(__DIR__ . "/tasks/_template.txt");
        
        $result = "";
        foreach($json->task as $task){
            $t = clone $template;
            $t->function = $this->getTemplate($task->name);
            $t->name = $task->alias ? $task->alias : $task->name;
            $t->description = $task->description;
            $t->dependencies = is_array($task->dependencies) ? implode(", ", $task->dependencies) : ""; 
            
            $result .= $t->render();
            
        }
        
        // TODO implement
        
        return $result;
    }
}