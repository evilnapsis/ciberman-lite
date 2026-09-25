<?php
namespace App\Service;

/**
 * Servicio de gestión de equipos y cabinas de cómputo.
 */
class EquipmentService {
	/**
	 * Retorna todos los equipos con su estado actual calculado (disponible u ocupado).
	 */
	public function getAllWithStatus(): array {
		$equipments = \EquipmentData::getAll();
		foreach ($equipments as $eq) {
			$activeRent = \RentData::getActiveByEquipment($eq->id);
			$eq->is_occupied = ($activeRent !== null);
			$eq->current_rent = $activeRent;
		}
		return $equipments;
	}

	/**
	 * Retorna solo los equipos disponibles para una nueva renta.
	 */
	public function getAvailable(): array {
		$all = $this->getAllWithStatus();
		return array_values(array_filter($all, fn($eq) => !$eq->is_occupied));
	}

	/**
	 * Obtiene un equipo por ID.
	 */
	public function getById($id) {
		return \EquipmentData::getById($id);
	}

	/**
	 * Registra un nuevo equipo.
	 */
	public function create(array $data): void {
		$eq = new \EquipmentData();
		$eq->code = trim($data['code'] ?? '');
		$eq->name = trim($data['name'] ?? '');
		$eq->description = trim($data['description'] ?? '');
		$eq->price_hour = (float)($data['price_hour'] ?? 0);
		$eq->price_half = (float)($data['price_half'] ?? 0);
		$eq->add();
	}

	/**
	 * Actualiza los datos de un equipo.
	 */
	public function update($id, array $data): void {
		$eq = \EquipmentData::getById($id);
		if (!$eq) return;

		$eq->code = trim($data['code'] ?? $eq->code);
		$eq->name = trim($data['name'] ?? $eq->name);
		$eq->description = trim($data['description'] ?? $eq->description);
		$eq->price_hour = (float)($data['price_hour'] ?? $eq->price_hour);
		$eq->price_half = (float)($data['price_half'] ?? $eq->price_half);
		$eq->update();
	}

	/**
	 * Elimina un equipo si no tiene rentas asociadas activas.
	 */
	public function delete($id): bool {
		$active = \RentData::getActiveByEquipment($id);
		if ($active) {
			return false;
		}
		\EquipmentData::delById($id);
		return true;
	}
}
