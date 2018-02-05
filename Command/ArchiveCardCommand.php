<?php
namespace ReminderBot\Command;

use TrelloFacade;

class ArchiveCardCommand extends \ReminderBot\ReminderBaseCommand {

	protected function configure() {
		$this->setName('!archivecard');
	}
	
//  Needs error handling FLAW: seperated by space, makes it impossible to add a card with several words
	protected function execute($message, $context) {
        $args = $this->getArgs($message);
        $command = isset($args[1]) ? $args[1] : '';
		$cardName = $args[1];
		
		if ($cardName == null) {
			$this->send($this->getCurrentChannel(), null, 'Use ' . $this->getName() . ' {cardname} to archive a card');
		} else {
			TrelloFacade::Instance()->archiveCard($cardName);
			$this->sendSuccessMessage();
		}
    }
	
	public function sendSuccessMessage() {
		$this->send($this->getCurrentChannel(), null, 'Card was successfully archived');
	}
}
