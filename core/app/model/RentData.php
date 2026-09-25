<?php
/**
 * Modelo para las rentas y alquileres de equipos.
 */
class RentData {
	public static $tablename = "rent";
	public $id;
	public $price;
	public $start_date;
	public $finish_date;
	public $bonus_mins;
	public $start_time;
	public $finish_time;
	public $person_name;
	public $equipment_id;
	public $user_id;
	public $created_at;

	// Propiedades de estado en tiempo de ejecución
	public $equipment = null;
	public $is_active = false;

	public function __construct(){
		$this->price = 0.0;
		$this->start_date = date("Y-m-d");
		$this->finish_date = null;
		$this->bonus_mins = 0;
		$this->start_time = date("H:i:s");
		$this->finish_time = null;
		$this->person_name = "";
		$this->equipment_id = 0;
		$this->user_id = 0;
		$this->created_at = date("Y-m-d H:i:s");
	}

	private static function db(): \PDO {
		return Database::getPdo();
	}

	/**
	 * Registra el inicio de una renta.
	 */
	public function add(){
		$stmt = self::db()->prepare(
			"INSERT INTO " . self::$tablename . " (price, start_date, finish_date, bonus_mins, start_time, finish_time, person_name, equipment_id, user_id, created_at) " .
			"VALUES (:price, :start_date, :finish_date, :bonus_mins, :start_time, :finish_time, :person_name, :equipment_id, :user_id, NOW())"
		);
		$stmt->execute([
			'price' => $this->price,
			'start_date' => $this->start_date,
			'finish_date' => $this->finish_date,
			'bonus_mins' => $this->bonus_mins ?: 0,
			'start_time' => $this->start_time,
			'finish_time' => $this->finish_time,
			'person_name' => $this->person_name,
			'equipment_id' => $this->equipment_id,
			'user_id' => $this->user_id,
		]);
		$this->id = self::db()->lastInsertId();
	}

	/**
	 * Actualiza los datos de una renta.
	 */
	public function update(){
		$stmt = self::db()->prepare(
			"UPDATE " . self::$tablename . " SET price = :price, start_date = :start_date, finish_date = :finish_date, " .
			"bonus_mins = :bonus_mins, start_time = :start_time, finish_time = :finish_time, " .
			"person_name = :person_name, equipment_id = :equipment_id WHERE id = :id"
		);
		$stmt->execute([
			'price' => $this->price,
			'start_date' => $this->start_date,
			'finish_date' => $this->finish_date,
			'bonus_mins' => $this->bonus_mins ?: 0,
			'start_time' => $this->start_time,
			'finish_time' => $this->finish_time,
			'person_name' => $this->person_name,
			'equipment_id' => $this->equipment_id,
			'id' => $this->id,
		]);
	}

	/**
	 * Finaliza la renta asignando hora/fecha de fin y precio liquidado.
	 */
	public function finish($finishDate, $finishTime, $price){
		$stmt = self::db()->prepare(
			"UPDATE " . self::$tablename . " SET finish_date = :finish_date, finish_time = :finish_time, price = :price WHERE id = :id"
		);
		$stmt->execute([
			'finish_date' => $finishDate,
			'finish_time' => $finishTime,
			'price' => $price,
			'id' => $this->id,
		]);
	}

	/**
	 * Elimina una renta por su ID.
	 */
	public static function delById($id){
		$stmt = self::db()->prepare("DELETE FROM " . self::$tablename . " WHERE id = :id");
		$stmt->execute(['id' => $id]);
	}

	public function del(){
		self::delById($this->id);
	}

	/**
	 * Obtiene una renta por su ID.
	 */
	public static function getById($id){
		$stmt = self::db()->prepare("SELECT * FROM " . self::$tablename . " WHERE id = :id");
		$stmt->execute(['id' => $id]);
		$stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, self::class);
		return $stmt->fetch() ?: null;
	}

	/**
	 * Obtiene todas las rentas ordenadas por id descendente.
	 */
	public static function getAll(){
		$stmt = self::db()->query("SELECT * FROM " . self::$tablename . " ORDER BY id DESC");
		return $stmt->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, self::class);
	}

	/**
	 * Obtiene las rentas filtradas por fecha de inicio.
	 */
	public static function getAllByDate($date){
		$stmt = self::db()->prepare("SELECT * FROM " . self::$tablename . " WHERE start_date = :d ORDER BY id DESC");
		$stmt->execute(['d' => $date]);
		return $stmt->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, self::class);
	}

	/**
	 * Obtiene la renta activa de un equipo determinado.
	 */
	public static function getActiveByEquipment($equipmentId){
		$stmt = self::db()->prepare(
			"SELECT * FROM " . self::$tablename . " WHERE equipment_id = :eq AND (finish_time IS NULL OR finish_time = '') ORDER BY id DESC LIMIT 1"
		);
		$stmt->execute(['eq' => $equipmentId]);
		$stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, self::class);
		return $stmt->fetch() ?: null;
	}

	/**
	 * Retorna el objeto equipo asociado a la renta.
	 */
	public function getEquipment(){
		return EquipmentData::getById($this->equipment_id);
	}

	/**
	 * Retorna el usuario que registró la renta.
	 */
	public function getUser(){
		return UserData::getById($this->user_id);
	}
}
?>
