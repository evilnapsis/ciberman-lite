<?php
namespace App\Controller;

use App\Service\RentService;
use App\Service\EquipmentService;
use ViewEngine;
use Req;

/**
 * Controlador de operaciones de renta, temporizador y cobro de tiempo.
 */
class RentController {
	private $rentService;
	private $equipmentService;
	private $baseFolder;

	public function __construct() {
		$this->rentService = new RentService();
		$this->equipmentService = new EquipmentService();
		$this->baseFolder = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
	}

	/**
	 * Listado de rentas con filtro por fecha.
	 */
	public function index() {
		$date = Req::get('date_at', date('Y-m-d'));
		$rents = $this->rentService->getRents($date);
		$totalDay = array_reduce($rents, fn($acc, $r) => $acc + (float)$r->price, 0.0);

		ViewEngine::render('rents/index.html.twig', [
			'rents' => $rents,
			'current_date' => $date,
			'total_day' => round($totalDay, 2)
		]);
	}

	/**
	 * Formulario para iniciar una nueva renta.
	 */
	public function new() {
		$selectedEquipment = Req::get('equipment_id', null);
		$availableEquipments = $this->equipmentService->getAvailable();

		ViewEngine::render('rents/new.html.twig', [
			'equipments' => $availableEquipments,
			'selected_equipment' => $selectedEquipment,
			'current_date' => date('Y-m-d'),
			'current_time' => date('H:i')
		]);
	}

	/**
	 * Registra el inicio de una renta de equipo.
	 */
	public function create() {
		$errors = Req::validate(['equipment_id' => 'required']);
		if (!empty($errors)) {
			ViewEngine::render('rents/new.html.twig', [
				'equipments' => $this->equipmentService->getAvailable(),
				'errors' => $errors,
				'old' => Req::post(),
				'current_date' => date('Y-m-d'),
				'current_time' => date('H:i')
			]);
			return;
		}

		$userId = $_SESSION['user_id'] ?? 1;
		$this->rentService->startRent(Req::post(), $userId);
		$_SESSION['success'] = 'Renta iniciada correctamente';
		header('Location: ' . $this->baseFolder . '/rents');
		exit;
	}

	/**
	 * Formulario para finalizar una renta activa y calcular el cobro sugerido.
	 */
	public function finishForm($vars) {
		$rent = $this->rentService->getById($vars['id']);
		if (!$rent) {
			header('Location: ' . $this->baseFolder . '/rents');
			exit;
		}

		$calculation = $this->rentService->calculateCost($vars['id']);

		ViewEngine::render('rents/finish.html.twig', [
			'rent' => $rent,
			'calc' => $calculation
		]);
	}

	/**
	 * Procesa la liquidación y fin de la renta.
	 */
	public function processFinish($vars) {
		$this->rentService->finishRent($vars['id'], Req::post());
		$_SESSION['success'] = 'Renta finalizada y cobrada con éxito';
		header('Location: ' . $this->baseFolder . '/rents');
		exit;
	}

	/**
	 * Formulario para editar una renta.
	 */
	public function edit($vars) {
		$rent = $this->rentService->getById($vars['id']);
		if (!$rent) {
			header('Location: ' . $this->baseFolder . '/rents');
			exit;
		}

		ViewEngine::render('rents/edit.html.twig', [
			'rent' => $rent,
			'equipments' => $this->equipmentService->getAllWithStatus()
		]);
	}

	/**
	 * Procesa la actualización de una renta.
	 */
	public function update($vars) {
		$this->rentService->update($vars['id'], Req::post());
		$_SESSION['updated'] = 'Renta actualizada correctamente';
		header('Location: ' . $this->baseFolder . '/rents');
		exit;
	}

	/**
	 * Elimina una renta.
	 */
	public function delete($vars) {
		$this->rentService->delete($vars['id']);
		$_SESSION['deleted'] = 'Renta eliminada correctamente';
		header('Location: ' . $this->baseFolder . '/rents');
		exit;
	}
}
