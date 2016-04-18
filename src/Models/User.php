<?php
namespace Simon\Authl\Models;
use Illuminate\Database\Eloquent\Model;
class User extends Model
{
	
	protected $guarded = [];
	
	public function __construct(array $attributes = []) 
	{
		$this->setTable(config('authl.user_table'));
		parent::__construct($attributes);
	}
	
}