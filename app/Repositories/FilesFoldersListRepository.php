<?php

namespace App\Repositories;

class FilesFoldersListRepository {
	
	function check_resource_exist($user_id, $folder_name, $folder_path, $type_of_resource){
		$check_resource_exist = \App\FilesFoldersList::where('user_id', $user_id)
				->where('resource_name', $folder_name)
				->where('resource_path', $folder_path)
				->where('resource_type', $type_of_resource)
               ->count();
		return $check_resource_exist;
	}
    
    function dirToArray($dir) { 
        $result = array(); 
        if (!file_exists($dir)) {
            return 'No such folder exist';
        }

        $cdir = scandir($dir); 
        foreach ($cdir as $key => $value){ 
            if (!in_array($value,array(".",".."))){ 
                if (is_dir($dir . DIRECTORY_SEPARATOR . $value)){ 
                    $result['folders'][$value] = $this->dirToArray($dir . DIRECTORY_SEPARATOR . $value); 
                }else{ 
                    $result['files'][] = $value; 
                } 
            } 
        } 
        return $result; 
    }
	
	function makeDirectory($folder_path){
        mkdir($folder_path, 0700, true);
	}
}