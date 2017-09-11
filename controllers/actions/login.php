<?php

// Action de Login

// Validation des parametres de la requete via Flourish
$login = fRequest::get('login','string?');
$passwd = fRequest::get('password','string?');

// Get user matching the login and password
$users = User::checkPassword($login,$passwd);

// Verifier qu'il n'y a qu'un utilisateur
// PS : inutile car login est Primary Key dans le SGBD
if ($users->count() == 1) {
	$user = $users[0];
	fAuthorization::setUserAuthLevel($user->prepareAuthLevel());
	fAuthorization::setUserToken($user->prepareLogin());
	// Redirect vers la page ayant mené au login
	// Si il n'y a pas de page 
	fURL::redirect(fAuthorization::getRequestedURL(TRUE, '/'));
} else {
	// Login failed
	// To Do ?
}

?>
