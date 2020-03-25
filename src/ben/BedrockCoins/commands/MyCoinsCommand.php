<?php

namespace ben\BedrockCoins\commands;

use ben\BedrockCoins\BedrockCoins;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class MyCoinsCommand extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = []) {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!$sender instanceof Player) return;
        $sender->sendMessage(BedrockCoins::getInstance()->getMessage(BedrockCoins::getInstance()->language["yourcoins"], $sender->getName(), BedrockCoins::getInstance()->getCoins($sender->getName())));
    }

}