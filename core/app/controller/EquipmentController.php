<?php
namespace App\Controller;

use App\Service\EquipmentService;
use ViewEngine;
use Req;

/**
 * Controlador para la administración del catálogo de equipos de cómputo.
 */
class EquipmentController {
	private $equipmentService;
	private $baseFolder;

	public function __construct() {
		$this->equipmentService = new EquipmentService();
		$this->baseFolder = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
	}

	/**
	 * Listado de equipos y su estado.
	 */
	public function index() {
		$equipments = $this->equipmentService->getAllWithStatus();
		ViewEngine::render('equipments/index.html.twig', [
			'equipments' => $equipments
		]);
	}

	/**
	 * Formulario para registrar un nuevo equipo.
	 */
	public function new() {
		ViewEngine::render('equipments/new.html.twig');
	}

	/**
	 * Procesa la creación de un nuevo equipo.
	 */
	public function create() {
		$errors = Req::validate(['name' => 'required']);
		if (!empty($errors)) {
			ViewEngine::render('equipments/new.html.twig', [
				'errors' => $errors,
				'old' => Req::post()
			]);
			return;
		}

		$this->equipmentService->create(Req::post());
		$_SESSION['success'] = 'Equipo agregado exitosamente';
		header('Location: ' . $this->baseFolder . '/equipments');
		exit;
	}

	/**
	 * Formulario para editar un equipo.
	 */
	public function edit($vars) {
		$equipment = $this->equipmentService->getById($vars['id']);
		if (!$equipment) {
			header('Location: ' . $this->baseFolder . '/equipments');
			exit;
		}

		ViewEngine::render('equipments/edit.html.twig', [
			'equipment' => $equipment
		]);
	}

	/**
	 * Procesa la actualización de un equipo.
	 */
	public function update($vars) {
		$errors = Req::validate(['name' => 'required']);
		if (!empty($errors)) {
			ViewEngine::render('equipments/edit.html.twig', [
				'equipment' => $this->equipmentService->getById($vars['id']),
				'errors' => $errors
			]);
			return;
		}

		$this->equipmentService->update($vars['id'], Req::post());
		$_SESSION['updated'] = 'Equipo actualizado exitosamente';
		header('Location: ' . $this->baseFolder . '/equipments');
		exit;
	}

	/**
	 * Elimina un equipo si está desocupado.
	 */
	public function delete($vars) {
		if ($this->equipmentService->delete($vars['id'])) {
			$_SESSION['deleted'] = 'Equipo eliminado correctamente';
		} else {
			$_SESSION['error'] = 'No se puede eliminar el equipo porque tiene una renta activa en curso.';
		}
		header('Location: ' . $this->baseFolder . '/equipments');
		exit;
	}
}
