<?php

namespace ReminderBot\Command;

class PingPongCommand extends \PhpSlackBot\Command\PingPongCommand {
	
	protected function configure() {
        $this->setName('!ping');
    }
}

