<?php

namespace ben\BedrockCoins\commands;

use ben\BedrockCoins\BedrockCoins;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class SeeCoinsCommand extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = []) {
        $this->setPermission("bedrockcoins.command.seecoins");
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!$sender->hasPermission("bedrockcoins.command.seecoins")) {
            $sender->sendMessage(BedrockCoins::getInstance()->getMessage(BedrockCoins::getInstance()->language["nopermission"]));
            return;
        }
        if (!isset($args[0])) {
            $sender->sendMessage(BedrockCoins::getInstance()->getMessage(BedrockCoins::getInstance()->language["commandusage"], null, null, "/seecoins <player>"));
            return;
        }
        if (!BedrockCoins::getInstance()->database->existsPlayer($args[0])) {
            $sender->sendMessage(BedrockCoins::getInstance()->getMessage(BedrockCoins::getInstance()->language["playernotexisting"], $args[0]));
            return;
        }
        $sender->sendMessage(BedrockCoins::getInstance()->getMessage(BedrockCoins::getInstance()->language["seecoins"], $args[0], BedrockCoins::getInstance()->getCoins($args[0])));
    }

}