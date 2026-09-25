<?php
namespace App\Service;

/**
 * Servicio de control de rentas de computadoras y facturación de tiempo.
 */
class RentService {
	/**
	 * Retorna la lista de rentas, opcionalmente filtrada por fecha.
	 */
	public function getRents(?string $date = null): array {
		if ($date) {
			$rents = \RentData::getAllByDate($date);
		} else {
			$rents = \RentData::getAll();
		}

		foreach ($rents as $r) {
			$r->equipment = \EquipmentData::getById($r->equipment_id);
			$r->is_active = empty($r->finish_time);
		}
		return $rents;
	}

	/**
	 * Obtiene una renta por su identificador.
	 */
	public function getById($id) {
		$rent = \RentData::getById($id);
		if ($rent) {
			$rent->equipment = \EquipmentData::getById($rent->equipment_id);
			$rent->is_active = empty($rent->finish_time);
		}
		return $rent;
	}

	/**
	 * Inicia una nueva renta de equipo.
	 */
	public function startRent(array $data, int $userId): int {
		$rent = new \RentData();
		$rent->equipment_id = (int)($data['equipment_id'] ?? 0);
		$rent->person_name = trim($data['person_name'] ?? 'Público General');
		$rent->start_date = !empty($data['start_date']) ? $data['start_date'] : date('Y-m-d');
		$rent->start_time = !empty($data['start_time']) ? $data['start_time'] : date('H:i:s');
		$rent->bonus_mins = (int)($data['bonus_mins'] ?? 0);
		$rent->finish_time = !empty($data['finish_time']) ? $data['finish_time'] : null;
		$rent->finish_date = !empty($data['finish_date']) ? $data['finish_date'] : null;
		$rent->price = (float)($data['price'] ?? 0);
		$rent->user_id = $userId;
		$rent->add();
		return $rent->id;
	}

	/**
	 * Calcula el costo sugerido según el tiempo transcurrido y tarifas del equipo.
	 */
	public function calculateCost($rentId): array {
		$rent = \RentData::getById($rentId);
		if (!$rent) {
			return ['minutes' => 0, 'cost' => 0.0];
		}
		$equipment = \EquipmentData::getById($rent->equipment_id);
		$priceHour = $equipment ? (float)$equipment->price_hour : 15.0;
		$priceHalf = $equipment ? (float)$equipment->price_half : 8.0;

		$startDateTime = strtotime($rent->start_date . ' ' . $rent->start_time);
		$now = time();
		$diffMinutes = max(1, (int)floor(($now - $startDateTime) / 60));
		
		// Descontar minutos de bono si los hay
		$billableMinutes = max(1, $diffMinutes - (int)$rent->bonus_mins);

		// Calcular costo aproximado
		if ($billableMinutes <= 30) {
			$cost = $priceHalf > 0 ? $priceHalf : ($priceHour / 2);
		} else {
			$hours = ceil($billableMinutes / 60);
			$cost = $hours * $priceHour;
		}

		return [
			'minutes' => $diffMinutes,
			'billable_minutes' => $billableMinutes,
			'cost' => round($cost, 2),
			'now_time' => date('H:i:s'),
			'now_date' => date('Y-m-d')
		];
	}

	/**
	 * Finaliza la renta registrando fecha/hora de salida y monto cobrado.
	 */
	public function finishRent($id, array $data): void {
		$rent = \RentData::getById($id);
		if (!$rent) return;

		$finishDate = !empty($data['finish_date']) ? $data['finish_date'] : date('Y-m-d');
		$finishTime = !empty($data['finish_time']) ? $data['finish_time'] : date('H:i:s');
		$price = (float)($data['price'] ?? 0);

		$rent->finish($finishDate, $finishTime, $price);
	}

	/**
	 * Actualiza los datos de una renta existente.
	 */
	public function update($id, array $data): void {
		$rent = \RentData::getById($id);
		if (!$rent) return;

		$rent->equipment_id = (int)($data['equipment_id'] ?? $rent->equipment_id);
		$rent->person_name = trim($data['person_name'] ?? $rent->person_name);
		$rent->start_date = $data['start_date'] ?? $rent->start_date;
		$rent->start_time = $data['start_time'] ?? $rent->start_time;
		$rent->finish_date = !empty($data['finish_date']) ? $data['finish_date'] : $rent->finish_date;
		$rent->finish_time = !empty($data['finish_time']) ? $data['finish_time'] : $rent->finish_time;
		$rent->bonus_mins = isset($data['bonus_mins']) ? (int)$data['bonus_mins'] : $rent->bonus_mins;
		$rent->price = (float)($data['price'] ?? $rent->price);
		$rent->update();
	}

	/**
	 * Elimina una renta.
	 */
	public function delete($id): void {
		\RentData::delById($id);
	}
}
