<?php

class TrelloFacade {
	private $trello;
	private static $instance;

	public static function Instance() {
		if (self::$instance == null) {
			self::$instance = new TrelloFacade();
		}
		return self::$instance;
	} 
	
	private function __construct() {
		$key = '553ef6b7eebc133460fd6c12464f04a6';
		$token = '41ecc33ab317a2aec3a6d6cc8f13c1ba732231a05c37b3e2d78086b4c7acbdc6';
		$this->trello = new TrelloApi($key, $token);
	}
	
	function getLists() { 
		$boardId = 'fSK4OUwS';
		$results = $this->trello->request('GET', 'board/' . $boardId . '/lists');
		
		foreach ($results as $value) {
			$result[] = $value;
		}
		return $result;
	}
	
	function getListIdByName($name) {
		$lists = $this->getLists();

		foreach ($lists as $value) {
			if ($value['name'] == $name) {
				return $value['id'];
			}
		}
	}
	
	function getCards() {
		$boardId = 'fSK4OUwS';
		$results = $this->trello->request('GET', 'board/' . $boardId . '/cards');
		
		foreach ($results as $value) {
			$result[] = $value;
		}
		return $result;
	}
	
	function getCardIdByName($name) {
		$cards = $this->getCards();
		
		foreach ($cards as $value) {
			if ($value['name'] == $name) {
				return $value['id'];
			}
		}
	}
	
	function getCardsDueDate() {
		$boardId = 'fSK4OUwS';
		
		$results = $this->trello->request('GET', 'board/' . $boardId . '/cards?fields=id,name,due&members=true&member_fields=fullName,username');
		return $results;
	}
	
	// Need it for specific Trello user who executes the command on Slack
	function getCardsDueSoon() {
		$dateNow = date('Y-m-d\Th:i:s.Z\Z');
		$dateSoon = date('Y-m-d\Th:i:s.Z\Z', strtotime('+1 week'));
		$cards = $this->getCardsDueDate();
		$results = [];
		
		foreach ($cards as $card) {
			if ($card['due'] >= $dateNow && $card['due'] <= $dateSoon) {
				$results[] = $card;
			}
		}
		
		return $results;
	}
	
	// Will get the cards with a duedate within 24 hours of now
	// Problems with date comparing due to being strings
	function getCardsDueToday() {
		$dateNow = date('Y-m-d\Th:i:s.Z\Z');
		$dateTomorrow = date('Y-m-d\Th:i:s.Z\Z', strtotime('+1 day', strtotime($dateNow)));
		$cards = $this->getCardsDueDate();
		$results = [];
		
		foreach ($cards as $card) {
			
			if ($card['due'] >= $dateNow && $card['due'] <= $dateTomorrow) {
				$results[] = $card;
			}
		}
		return $results;
	}
	
	function addCard($listName, $cardName) {
		$listId = $this->getListIdByName($listName);
		$this->trello->request('POST', 'cards', ['name'=>$cardName,'idList'=>$listId]);
	}
	
	// To archive set the card the field 'closed' to true
	// flaw: archives first card in first list of that name DOES NOT WORK WELL WITH DUPLICATE CARD NAMES
	function archiveCard($cardName) {
		$cardId = $this->getCardIdByName($cardName);
		$this->trello->request('PUT', 'cards/' . $cardId . '?closed=true');
	}
	
	// flaw: DOES NOT WORK WELL WITH DUPLICATE CARD NAMES
	function moveCard($cardName, $listName) {
		$cardId = $this->getCardIdByName($cardName);
		$listId = $this->getListIdByName($listName);
		$this->trello->request('PUT', 'cards/' . $cardId . '?idList=' . $listId);
	}
}
