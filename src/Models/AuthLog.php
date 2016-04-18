<?php
namespace Simon\Authl\Models;
use Illuminate\Database\Eloquent\Model;
class AuthLog extends Model
{
	
	const TYPE_REGISTER = 1;
	
	const TYPE_LOGIN = 2;
	
	const STATUS_SUCCESS = 1;
	
	const STATUS_ERROR = 2;
	
	public function scopeClientIp($query,$ip) 
	{
		return $query->where('client_ip',ip_long($ip));
	}
	
	public function scopeType($query,$type) 
	{
		return $query->where('type',$type);
	}
	
	public function scopeStatus($query,$type) 
	{
		return $query->where('status',$type);
	}
}