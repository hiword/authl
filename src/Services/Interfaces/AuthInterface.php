<?php
namespace Simon\Authl\Services\Interfaces;
use Illuminate\Http\Request;
interface AuthInterface
{
	
	public function auth(array $data = []);
	
}