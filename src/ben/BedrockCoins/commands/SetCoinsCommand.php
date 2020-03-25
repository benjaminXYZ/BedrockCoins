<?php

namespace ben\BedrockCoins\commands;

use ben\BedrockCoins\BedrockCoins;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class SetCoinsCommand extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = []) {
        $this->setPermission("bedrockcoins.command.setcoins");
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!$sender->hasPermission("bedrockcoins.command.setcoins")) {
            $sender->sendMessage(BedrockCoins::getMessage(BedrockCoins::language["nopermission"]));
            return;
        }
        if (!isset($args[0]) || !isset($args[1])) {
            $sender->sendMessage(BedrockCoins::getInstance()->getMessage(BedrockCoins::getInstance()->language["commandusage"], null, null, "/setcoins <player> <coins>"));
            return;
        }
        if (!BedrockCoins::getInstance()->database->existsPlayer($args[0])) {
            $sender->sendMessage(BedrockCoins::getInstance()->getMessage(BedrockCoins::getInstance()->language["playernotexisting"], $args[0]));
            return;
        }
        $coins = $args[1];
        if (!is_numeric($coins)) {
            $sender->sendMessage(BedrockCoins::getInstance()->getMessage(BedrockCoins::getInstance()->language["commandusage"], null, null, "/setcoins <player> <coins>"));
            return;
        }
        $sender->sendMessage(BedrockCoins::getInstance()->getMessage(BedrockCoins::getInstance()->language["setcoins"], $args[0], $coins));
        BedrockCoins::getInstance()->setCoins($args[0], $coins);
    }

}