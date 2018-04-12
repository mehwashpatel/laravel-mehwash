<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Repositories\FilesFoldersListRepository;

class FilesFoldersListTableSeeder extends Seeder{
    public function run(){
        $faker = Faker::create();
        // following line retrieve all the user_ids from DB
        $users = \App\User::all()->lists('id');
		$random_sub_folder_number = 25;
        foreach(range(1,25) as $key=>$index){
			$random_folder_names = $faker->word();                                             
            $folders = \App\FilesFoldersList::create([
                'resource_name' => $random_folder_names,
                'resource_path' => 'site\resources\\',
                'resource_type' => 'folder',
                'user_id' => $faker->randomElement($users)
            ]);
			$this->files_folders_repo->makeDirectory($user_root_folder_path);
        }
    }
}