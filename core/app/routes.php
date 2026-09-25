<?php
use App\Controller\HomeController;
use App\Controller\AuthController;
use App\Controller\ProfileController;
use App\Controller\EquipmentController;
use App\Controller\RentController;
use App\Controller\UserController;

/**
 * Definición centralizada de rutas para Ciberman 2.
 */
return function(FastRoute\RouteCollector $r) {
	// Raíz y Dashboard
	$r->addRoute('GET', '/', [HomeController::class, 'index']);
	$r->addRoute('GET', '/home', [HomeController::class, 'index']);

	// Autenticación
	$r->addRoute('GET', '/login', [AuthController::class, 'showLogin']);
	$r->addRoute('POST', '/login', [AuthController::class, 'processLogin']);
	$r->addRoute('GET', '/logout', [AuthController::class, 'logout']);

	// Perfil de Usuario
	$r->addRoute('GET', '/profile', [ProfileController::class, 'index']);
	$r->addRoute('POST', '/profile/change-password', [ProfileController::class, 'changePassword']);

	// Equipos y Cabinas
	$r->addRoute('GET', '/equipments', [EquipmentController::class, 'index']);
	$r->addRoute('GET', '/equipments/new', [EquipmentController::class, 'new']);
	$r->addRoute('POST', '/equipments/create', [EquipmentController::class, 'create']);
	$r->addRoute('GET', '/equipments/edit/{id:\d+}', [EquipmentController::class, 'edit']);
	$r->addRoute('POST', '/equipments/update/{id:\d+}', [EquipmentController::class, 'update']);
	$r->addRoute('GET', '/equipments/delete/{id:\d+}', [EquipmentController::class, 'delete']);

	// Rentas de Tiempo
	$r->addRoute('GET', '/rents', [RentController::class, 'index']);
	$r->addRoute('GET', '/rents/new', [RentController::class, 'new']);
	$r->addRoute('POST', '/rents/create', [RentController::class, 'create']);
	$r->addRoute('GET', '/rents/finish/{id:\d+}', [RentController::class, 'finishForm']);
	$r->addRoute('POST', '/rents/finish/{id:\d+}', [RentController::class, 'processFinish']);
	$r->addRoute('GET', '/rents/edit/{id:\d+}', [RentController::class, 'edit']);
	$r->addRoute('POST', '/rents/update/{id:\d+}', [RentController::class, 'update']);
	$r->addRoute('GET', '/rents/delete/{id:\d+}', [RentController::class, 'delete']);

	// Gestión de Usuarios
	$r->addRoute('GET', '/users', [UserController::class, 'index']);
	$r->addRoute('GET', '/users/new', [UserController::class, 'new']);
	$r->addRoute('POST', '/users/create', [UserController::class, 'create']);
	$r->addRoute('GET', '/users/edit/{id:\d+}', [UserController::class, 'edit']);
	$r->addRoute('POST', '/users/update/{id:\d+}', [UserController::class, 'update']);
	$r->addRoute('GET', '/users/delete/{id:\d+}', [UserController::class, 'delete']);
};
