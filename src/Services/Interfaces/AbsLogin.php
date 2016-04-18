<?php
namespace Simon\Authl\Services\Interfaces;
abstract class AbsLogin extends AbsAuth
{
	
	abstract protected function getUser();
	
	abstract protected function checkPassword(); 

}