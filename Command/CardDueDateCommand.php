<?php
namespace ReminderBot\Command;

use TrelloFacade;

class CardDueDateCommand extends \ReminderBot\ReminderBaseCommand {

	protected function configure() {
		$this->setName('!cardsdue');
	}
	
	protected function execute($message, $context) {		
		$cards = TrelloFacade::Instance()->getCardsDueSoon();
		$output = '';
		foreach ($cards as $card) {
			$output .= $card['name'] . ' on ' . $card['due'] . "\n";
		}
		$this->send($this->getCurrentChannel(), null, "Cards due this week: " . "\n" . $output);
    }
}
