<?php
namespace ReminderBot\Command;

use TrelloFacade;

class NewCardCommand extends \ReminderBot\ReminderBaseCommand {

	protected function configure() {
		$this->setName('!addcard');
	}
	
	// Needs error handling
	// FLAW: arguments separated by space, makes it impossible to add a card with several words
	protected function execute($message, $context) {
		$args = $this->getArgs($message);
		$command = isset($args[1]) ? $args[1] : '';
		$listName = $args[1];
		$cardName = $args[2];
		
		if ($listName == null && $cardName == null) {
			$this->send($this->getCurrentChannel(), null, 'Use ' . $this->getName() . ' {listname} {cardname} to add a card');
		} else {
			TrelloFacade::Instance()->addCard($listName, $cardName);
			$this->sendSuccessMessage();
		}
    }
	
	public function sendSuccessMessage() {
		$this->send($this->getCurrentChannel(), null, 'Card was successfully added');
	}
}
