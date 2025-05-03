<?php
require_once __DIR__ . '/../bootstrap.php';

$router = new Router();

// Make results page the entry point
$router->addRoute('GET', '/', 'ElectionController@listeResultats');

// Authentication routes (public)
$router->addRoute('GET', '/auth/login', 'AuthController@login');
$router->addRoute('POST', '/auth/login', 'AuthController@login');
$router->addRoute('GET', '/auth/register', 'AuthController@showRegister');
$router->addRoute('POST', '/auth/register', 'AuthController@register');
$router->addRoute('GET', '/auth/logout', 'AuthController@logout');

// Dashboard routes (protected)
$router->addRoute('GET', '/dashboard', 'DashboardController@index'); // Main dashboard router
$router->addRoute('GET', '/dashboard/admin', 'DashboardController@adminDashboard');
$router->addRoute('GET', '/dashboard/user', 'ElecteurController@Dashboard');
$router->addRoute('GET', '/dashboard/electeur', 'ElecteurController@dashboard');

// Electeur routes (protected)
$router->addRoute('GET', '/elections/en-cours', 'ElecteurController@electionsEnCours');
$router->addRoute('GET', '/elections/a-venir', 'ElecteurController@electionsAVenir');
$router->addRoute('GET', '/mes-votes', 'ElecteurController@mesVotes');
$router->addRoute('GET', '/profile', 'ElecteurController@profile');
$router->addRoute('GET', '/notifications', 'ElecteurController@notifications');
$router->addRoute('GET', '/elections/voter/{id}', 'ElecteurController@afficherCandidats');
$router->addRoute('POST', '/elections/voter', 'ElecteurController@enregistrerVote');

// Profile routes
$router->addRoute('GET', '/profile/edit', 'ProfileController@edit');
$router->addRoute('POST', '/profile/update', 'ProfileController@update');

// Admin routes (protected)
$router->addRoute('GET', '/elections', 'ElectionController@index');
$router->addRoute('GET', '/elections/create', 'ElectionController@create');
$router->addRoute('POST', '/elections/store', 'ElectionController@store');
$router->addRoute('GET', '/elections/edit/{id}', 'ElectionController@edit');
$router->addRoute('POST', '/elections/update/{id}', 'ElectionController@update');
$router->addRoute('GET', '/elections/delete/{id}', 'ElectionController@delete');

// Election results routes
$router->addRoute('GET', '/elections/resultats/{id}', 'ElectionController@afficherResultats');
$router->addRoute('GET', '/elections/resultats', 'ElectionController@listeResultats');

$router->addRoute('GET', '/users', 'UserController@index');
$router->addRoute('GET', '/users/create', 'UserController@create');
$router->addRoute('POST', '/users/store', 'UserController@store');
$router->addRoute('GET', '/users/edit/{id}', 'UserController@edit');
$router->addRoute('POST', '/users/update/{id}', 'UserController@update');
$router->addRoute('GET', '/users/delete/{id}', 'UserController@delete');

$router->addRoute('GET', '/candidats', 'CandidatController@index');
$router->addRoute('GET', '/candidats/create', 'CandidatController@create');
$router->addRoute('POST', '/candidats/store', 'CandidatController@store');
$router->addRoute('GET', '/candidats/edit/{id}', 'CandidatController@edit');
$router->addRoute('POST', '/candidats/update/{id}', 'CandidatController@update');
$router->addRoute('GET', '/candidats/delete/{id}', 'CandidatController@delete');
$router->addRoute('GET', '/candidats/validate/{id}', 'CandidatController@validate');

$router->addRoute('GET', '/rapports', 'RapportController@index');

// Routes pour les paramètres
$router->addRoute('GET', '/parametres', 'ParametreController@index');
$router->addRoute('POST', '/parametres/update', 'ParametreController@update');

// Routes pour le vote
$router->addRoute('GET', '/election/{id}/candidats', 'ElectionController@showCandidats');
$router->addRoute('POST', '/vote/submit', 'VoteController@submit');

$router->dispatch();