<?php
class Promotion {
	
	public $promoId;
	public $promoDesc;
	public $promoType;
	public $conditionUnits;
	public $conditionCurrency;
	public $conditionProductId;
	public $discountPercent;
	public $discountCurrency;
	
	public function __construct($primaryKey = false) {
		if ($primaryKey) {
			$this->load($primaryKey);
		}
	}
	
	public function load($primaryKey = false) {
		global $db;
		if ($primaryKey) {
			//Get by Unique Key
			try {
				$stmt = $db->prepare("Select * From promotions Where promoId = ?;");
				$stmt->execute(array($primaryKey));
				if ($row = $stmt->fetchObject()) {
					$this->promoId = $row->promoId;
					$this->promoDesc = $row->promoDesc;
					$this->promoType = $row->promoType;
					$this->conditionUnits = $row->conditionUnits;
					$this->conditionCurrency = $row->conditionCurrency;
					$this->conditionProductId = $row->conditionProductId;
					$this->discountPercent = $row->discountPercent;
					$this->discountCurrency = $row->discountCurrency;
				}
				$stmt->closeCursor();
			} catch(PDOException $e) {
				debug('Promotion / load() / ' . $e->getMessage());
				return false;
			}
		}
		$stmt->closeCursor();
		return $this->promoId;
	}
	
	public function save() {
		global $db;
		if ($this->promoId) {
			//Do an update
			try {
				$stmt = $db->prepare("Update promotions Set promoDesc = ?, promoType = ?, conditionUnits = ?, conditionCurrency = ?, conditionProductId = ?, discountPercent = ?, discountCurrency = ? Where promoId = ?;");
				$stmt->execute(array($this->promoDesc, $this->promoType, $this->conditionUnits, $this->conditionCurrency, $this->conditionProductId, $this->discountPercent, $this->discountCurrency, $this->promoId));
			} catch(PDOException $e) {
				debug('Promotion / save() update / ' . $e->getMessage());
				return false;
			}
		} else {
			//Do an insert
			try {
				$stmt = $db->prepare("Insert promotions (promoDesc, promoType, conditionUnits, conditionCurrency, conditionProductId, discountPercent, discountCurrency) values (?, ?, ?, ?, ?, ?, ?);");
				if ($stmt->execute(array($this->promoDesc, $this->promoType, $this->conditionUnits, $this->conditionCurrency, $this->conditionProductId, $this->discountPercent, $this->discountCurrency))) {
					$this->promoId = $db->lastInsertId();
				}
				$stmt->closeCursor();
			} catch(PDOException $e) {
				debug('Promotion / save() insert / ' . $e->getMessage());
				return false;
			}
		}
		return $this->promoId;
	}

	static function delete($primaryKey) {
		global $db;
		
		try {
			$stmt = $db->prepare("Delete from promotions Where promoId = ?;");
			$stmt->execute(array($primaryKey));
		} catch(PDOException $e) {
			debug('Promotion / delete() / ' . $e->getMessage());
			return false;
		}
		return true;
	}
	
	static function getAll() {
		//Returns an array of Promotion objects sorted by key.
		global $db;
		$ga = array();
		try {
			$stmt = $db->query("Select promoId From promotions Order by promoId;");
			while ($row = $stmt->fetchObject()) {
				$ga[] = new Promotion($row->promoId);
			}
			$stmt->closeCursor();
		} catch (PDOException $e) {
			debug('Promotion / getAll() / ' . $e->getMessage());
		}
		return $ga;
	}
}
?>