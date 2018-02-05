<?php
require "DbFacade.php";

class ReminderBot extends \PhpSlackBot\Bot {
	
	private $commands = array();
	private static $instance;

	public static function Instance() {
		if (self::$instance == null) {
			self::$instance = new ReminderBot();
		}
		return self::$instance;
	} 
	
	private function __construct() {
		
	}
	
	public function getCommands() {
		return $this->commands;
	}
	
	public function setCommandName($commandName) {
		$this->commands[$commandName] = $commandName;
	}

//	overriding the framework function to load my own commands.
	public function loadInternalCommands() {
//		$this->loadCommand(new \ReminderBot\Command\PingPongCommand);
//		$this->loadCommand(new \ReminderBot\Command\CountCommand);
//		$this->loadCommand(new \ReminderBot\Command\DateCommand);
		$this->loadCommand(new \ReminderBot\Command\PokerPlanningCommand);
		$this->loadCommand(new \ReminderBot\Command\HelpCommand($this));
		$this->loadCommand(new \ReminderBot\Command\NewCardCommand());
		$this->loadCommand(new \ReminderBot\Command\ArchiveCardCommand());
		$this->loadCommand(new \ReminderBot\Command\MoveCardCommand());	
		$this->loadCommand(new \ReminderBot\Command\CardDueDateCommand());
    }
	
	public function loadCommand($command) {
		parent::loadCommand($command);
		$this->setCommandName($command->getName());
	}
	
	public function sendMessage($channelOrUsername, $usernameForMention, $message) {
		$this->activeMessenger->sendMessage($channelOrUsername, $usernameForMention, $message);
	}
	
//	Builds the message for active messaging with the cards due.
	public static function cardReminder() {
		$cards = TrelloFacade::Instance()->getCardsDueToday();
		$users = ReminderBot\DbFacade::Instance()->getUsers();
		
		if (!empty($cards)) {
			foreach ($cards as $card) {
				$cardName = $card['name'];
				$message = 'The card ' . $cardName . ' is due soon!';
				
				foreach ($card['members'] as $member) {
					$username = $users[$member['username']];
					ReminderBot::Instance()->sendMessage('@' . $username, null, $message);
				}
			}
		}
		
		return [
			'channel' => null,
			'username' => null,
			'message' => null
		];
	}
}
