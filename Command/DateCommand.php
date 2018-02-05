<?php

namespace ReminderBot\Command;

class DateCommand extends \PhpSlackBot\Command\DateCommand {
	
	protected function configure() {
        $this->setName('!date');
    }
}

