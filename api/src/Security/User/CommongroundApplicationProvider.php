<?php

// src/Security/User/CommongroundApplicationProvider.php
namespace App\Security\User;

use App\Security\User\CommongroundApplication;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class CommongroundApplicationProvider implements UserProviderInterface
{
	public function loadUserByUsername($username)
	{
		return $this->fetchUser($username);
	}
	
	public function refreshUser(UserInterface $user)
	{
		if (!$user instanceof CommongroundApplication) {
			throw new UnsupportedUserException(
					sprintf('Instances of "%s" are not supported.', get_class($user))
					);
		}
		
		$username = $user->getUsername();
		
		return $this->fetchUser($username);
	}
	
	public function supportsClass($class)
	{
		return CommongroundApplication::class === $class;
	}
	
	private function fetchUser($uuid)
	{
		// make a call to your webservice here
		// $userData = ...
		// pretend it returns an array on success, false if there is no user
		
		if ($userData) {
			$password = '...';
			
			// ...
			
			return new CommongroundApplication($username, $password, $salt, $roles);
		}
		
		throw new UsernameNotFoundException(
				sprintf('Application "%s" does not exist.', $uuid)
				);
	}
}