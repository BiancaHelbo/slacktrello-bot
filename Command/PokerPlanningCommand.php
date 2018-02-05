<?php

namespace ReminderBot\Command;

class PokerPlanningCommand extends \PhpSlackBot\Command\PokerPlanningCommand {
	
	protected function configure() {
        $this->setName('!pokerp');
    }
}
