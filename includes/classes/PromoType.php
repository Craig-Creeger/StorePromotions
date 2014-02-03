<?php

class PromoType {
	
	public $promoType;
	public $promotion;
	
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
				$stmt = $db->prepare("Select * From promoTypes Where promoType = ?;");
				$stmt->execute(array($primaryKey));
				if ($row = $stmt->fetchObject()) {
					$this->promoType = $row->promoType;
					$this->promotion = $row->promotion;
				}
				$stmt->closeCursor();
			} catch(PDOException $e) {
				debug('PromoType / load() / ' . $e->getMessage());
				return false;
			}
		}
		$stmt->closeCursor();
		return $this->promoType;
	}
	
	static function getAll() {
		//Returns an array of PromoType objects sorted by key.
		global $db;
		$ga = array();
		try {
			$stmt = $db->query("Select promoType From promoTypes Order by promoType;");
			while ($row = $stmt->fetchObject()) {
				$ga[] = new PromoType($row->promoType);
			}
		} catch (PDOException $e) {
			debug('PromoType / getAll() / ' . $e->getMessage());
		}
		$stmt->closeCursor();
		return $ga;
	}
}
?>