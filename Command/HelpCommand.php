<?php
namespace ReminderBot\Command;

class HelpCommand extends \PhpSlackBot\Command\BaseCommand {
	
	private $bot;
	
	protected function configure() {
		$this->setName('!help');
	}
	
	public function __construct($bot) {
		$this->bot = $bot;
	}
	
	protected function execute($message, $context) {
		$this->send($this->getCurrentChannel(), null, "Available Commands: " . "\n" . implode("\n", $this->bot->getCommands()));
	}
}
