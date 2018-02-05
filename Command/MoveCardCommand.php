<?php
namespace ReminderBot\Command;

use TrelloFacade;

class MoveCardCommand extends \ReminderBot\ReminderBaseCommand {

	protected function configure() {
		$this->setName('!movecard');
	}
	
//  Needs error handling FLAW: seperated by space, makes it impossible to add a card with several words
	protected function execute($message, $context) {
        $args = $this->getArgs($message);
        $command = isset($args[1]) ? $args[1] : '';
		$cardName = $args[1];
		$listName = $args[2];
		
		if ($cardName == null && $listName == null) {
			$this->send($this->getCurrentChannel(), null, 'Use ' . $this->getName() . ' {cardname} {listname} to move a card');	
		} else {
			TrelloFacade::Instance()->moveCard($cardName, $listName);
			$this->sendSuccessMessage();
		}
    }
	
	public function sendSuccessMessage() {
		$this->send($this->getCurrentChannel(), null, 'Card was successfully moved');
	}
}
