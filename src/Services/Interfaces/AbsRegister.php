<?php
namespace Simon\Authl\Services\Interfaces;
abstract class AbsRegister extends AbsAuth
{
	
	abstract protected function saveUser(array $data = []);
	
}