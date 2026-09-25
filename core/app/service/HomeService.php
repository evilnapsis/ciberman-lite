<?php
namespace App\Service;

/**
 * Servicio del panel principal y métricas operativas del ciber café.
 */
class HomeService {
	/**
	 * Obtiene métricas y estado en tiempo real del ciber.
	 */
	public function getDashboardData(): array {
		$equipmentService = new EquipmentService();
		$equipments = $equipmentService->getAllWithStatus();

		$totalEquipments = count($equipments);
		$occupiedCount = count(array_filter($equipments, fn($e) => $e->is_occupied));
		$availableCount = $totalEquipments - $occupiedCount;

		$today = date('Y-m-d');
		$todayRents = \RentData::getAllByDate($today);
		$totalRentsToday = count($todayRents);
		$incomeToday = array_reduce($todayRents, fn($acc, $r) => $acc + (float)$r->price, 0.0);

		// Rentas activas
		$allRents = \RentData::getAll();
		$activeRents = [];
		foreach ($allRents as $r) {
			if (empty($r->finish_time)) {
				$r->equipment = \EquipmentData::getById($r->equipment_id);
				$activeRents[] = $r;
			}
		}

		return [
			'equipments' => $equipments,
			'total_equipments' => $totalEquipments,
			'occupied_count' => $occupiedCount,
			'available_count' => $availableCount,
			'total_rents_today' => $totalRentsToday,
			'income_today' => round($incomeToday, 2),
			'active_rents' => $activeRents,
		];
	}
}
