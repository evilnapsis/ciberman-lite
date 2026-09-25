<?php
/**
 * Modelo de equipos y computadoras del ciber café.
 */
class EquipmentData {
	public static $tablename = "equipment";
	public $id;
	public $code;
	public $name;
	public $description;
	public $price_hour;
	public $price_half;
	public $created_at;

	// Propiedades de estado en tiempo de ejecución
	public $is_occupied = false;
	public $current_rent = null;

	public function __construct(){
		$this->code = "";
		$this->name = "";
		$this->description = "";
		$this->price_hour = 0.0;
		$this->price_half = 0.0;
		$this->created_at = date("Y-m-d H:i:s");
	}

	private static function db(): \PDO {
		return Database::getPdo();
	}

	/**
	 * Registra un nuevo equipo.
	 */
	public function add(){
		$stmt = self::db()->prepare(
			"INSERT INTO " . self::$tablename . " (code, name, description, price_hour, price_half, created_at) " .
			"VALUES (:code, :name, :description, :price_hour, :price_half, NOW())"
		);
		$stmt->execute([
			'code' => $this->code,
			'name' => $this->name,
			'description' => $this->description,
			'price_hour' => $this->price_hour,
			'price_half' => $this->price_half,
		]);
		$this->id = self::db()->lastInsertId();
	}

	/**
	 * Actualiza los datos de un equipo existente.
	 */
	public function update(){
		$stmt = self::db()->prepare(
			"UPDATE " . self::$tablename . " SET code = :code, name = :name, description = :description, " .
			"price_hour = :price_hour, price_half = :price_half WHERE id = :id"
		);
		$stmt->execute([
			'code' => $this->code,
			'name' => $this->name,
			'description' => $this->description,
			'price_hour' => $this->price_hour,
			'price_half' => $this->price_half,
			'id' => $this->id,
		]);
	}

	/**
	 * Elimina un equipo por su ID.
	 */
	public static function delById($id){
		$stmt = self::db()->prepare("DELETE FROM " . self::$tablename . " WHERE id = :id");
		$stmt->execute(['id' => $id]);
	}

	public function del(){
		self::delById($this->id);
	}

	/**
	 * Obtiene un equipo por su ID.
	 */
	public static function getById($id){
		$stmt = self::db()->prepare("SELECT * FROM " . self::$tablename . " WHERE id = :id");
		$stmt->execute(['id' => $id]);
		$stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, self::class);
		return $stmt->fetch() ?: null;
	}

	/**
	 * Obtiene todos los equipos registrados.
	 */
	public static function getAll(){
		$stmt = self::db()->query("SELECT * FROM " . self::$tablename . " ORDER BY id ASC");
		return $stmt->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, self::class);
	}

	/**
	 * Busca equipos por término de búsqueda.
	 */
	public static function getLike($q){
		$stmt = self::db()->prepare("SELECT * FROM " . self::$tablename . " WHERE name LIKE :q OR code LIKE :q");
		$stmt->execute(['q' => "%$q%"]);
		return $stmt->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, self::class);
	}
}
?>
