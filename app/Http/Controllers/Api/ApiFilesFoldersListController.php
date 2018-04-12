<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\FilesFoldersList;
use App\Services\EncodingService;
use App\Repositories\FilesFoldersListRepository;

class ApiFilesFoldersListController extends Controller
{
	public function __construct(FilesFoldersListRepository $files_folders_repo) {
		$this->files_folders_repo = $files_folders_repo;
	}
	
	public function viewFiles($id) {
		$encoder = new EncodingService();
		//$dir    = \Config::get('constants.RESOURCES_PATH').''.$id;
		//$result = $this->files_folders_repo->dirToArray($dir);
		
		$uploaded_resources = FilesFoldersList::where('user_id', $id)
               ->orderBy('resource_type', 'desc')
               ->get();
		$result = array();
		
		$folder_structure_array = array();
		foreach($uploaded_resources AS $uploaded_resource){
			$folder_structure_array[$uploaded_resource->id] = $uploaded_resource->resource_name;
		}
		
		$resource_path_arr = array();
		foreach($uploaded_resources AS $uploaded_resource){
			$resource_type = $uploaded_resource->resource_type;
			$resource_name = $uploaded_resource->resource_name;
			$resource_path = $uploaded_resource->resource_path;
			$resource_path_arr[] = $resource_path.'\\'.$resource_name.'|'.$resource_type;
		}
		if(count($resource_path_arr) > 0){
			$paths = [];
			foreach ($resource_path_arr as $pathString) {
				$pathParts = explode('\\', $pathString);
				$path = [array_pop($pathParts)];
				foreach (array_reverse($pathParts) as $pathPart) {
					$path = [$pathPart => $path];
				}
				$paths[] = $path;
			}
			$result = call_user_func_array('array_merge_recursive', $paths);
			return $encoder->successWrapper($result);
		}else{
			return $encoder->failureWrapper(['error' => 'No resources found.']);
		}
		
	}
	
	public function uploadFiles(Request $request){
		$encoder = new EncodingService();
		$user_id = $request->input('user_id');
		$user_file_local_path = $request->input('file_local_path');
		$user_selected_folder_path = 'site\resources\\user_'.$user_id;
		if($request->input('folder_path') != ''){
			$user_selected_folder_path .= '\\'.$request->input('folder_path');
		}
		$user_root_folder_path = \Config::get('constants.RESOURCES_PATH').''.$user_selected_folder_path;
		
		if (!file_exists($user_root_folder_path)) {
			return $encoder->failureWrapper(['error' => 'No such folder path. Please create folder first']); 
		}
		
		$upload_file = file_get_contents($user_file_local_path);
		file_put_contents ( $user_root_folder_path.'/'.basename ( $user_file_local_path ) , $upload_file );
		
		$data = new FilesFoldersList;
		$data->resource_name = basename ( $user_file_local_path );
		$data->resource_path = $user_selected_folder_path;
		$data->resource_type = 'file';
		$data->user_id = $user_id;
		if($data->save()) {
			return $this->viewFiles($user_id);
		}
		return $this->viewFiles($user_id);
	}
	
	public function createFolders(Request $request){
		$encoder = new EncodingService();
		$user_id = $request->input('user_id');
		$folder_name = $request->input('folder_name');
		$folder_path = 'site\resources\\user_'.$user_id;
		if($folder_path != ''){
			$folder_path .= '\\'.$request->input('folder_path');
		}
		$check_resource_exist = $this->files_folders_repo->check_resource_exist($user_id, $folder_name, $folder_path, 'folder');
		
		$user_root_folder_path = \Config::get('constants.RESOURCES_PATH').''.$folder_path.'\\'.$folder_name;
		
		if (file_exists($user_root_folder_path)) {
			return $encoder->failureWrapper(['error' => 'Folder with name '.$folder_name.' already exist']); 
		}
		
		$data = new FilesFoldersList;
		$data->resource_name = $folder_name;
		$data->resource_path = $folder_path;
		$data->resource_type = 'folder';
		$data->user_id = $user_id;
		if($data->save()) {
			$this->files_folders_repo->makeDirectory($user_root_folder_path);
			return $this->viewFiles($user_id);
		}
	}
}


