<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FilesFoldersList extends Model
{
   protected $table = 'files_folders_list';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['resource_name', 'resource_path', 'resource_type', 'user_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at'];
}
