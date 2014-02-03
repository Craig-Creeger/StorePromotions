<?php
class Product {
	
	public $productId;
	public $productName;
	public $unitPrice;
	
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
				$stmt = $db->prepare("Select * From products Where productId = ?;");
				$stmt->execute(array($primaryKey));
				if ($row = $stmt->fetchObject()) {
					$this->productId = $row->productId;
					$this->productName = $row->productName;
					$this->unitPrice = $row->unitPrice;
				}
				$stmt->closeCursor();
			} catch(PDOException $e) {
				debug('Product / load() / ' . $e->getMessage());
				return false;
			}
		}
		$stmt->closeCursor();
		return $this->productId;
	}
	
	static function getAll() {
		//Returns an array of productId objects sorted by key.
		global $db;
		$ga = array();
		try {
			$stmt = $db->query("Select productId From products Order by productId;");
			while ($row = $stmt->fetchObject()) {
				$ga[] = new Product($row->productId);
			}
		} catch (PDOException $e) {
			debug('Product / getAll() / ' . $e->getMessage());
		}
		$stmt->closeCursor();
		return $ga;
	}
}
?>