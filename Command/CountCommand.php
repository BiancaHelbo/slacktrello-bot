<?php

namespace ReminderBot\Command;

class CountCommand extends \PhpSlackBot\Command\CountCommand {
	
	protected function configure() {
        $this->setName('!count');
    }
}

