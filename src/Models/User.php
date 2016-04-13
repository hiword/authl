<?php
namespace Simon\Authl\Models;
use Illuminate\Database\Eloquent\Model;
class User extends Model
{
	
	public function __construct(array $attributes = []) 
	{
		$this->setTable(config('user_table'));
		parent::__construct($attributes);
	}
	
}