<?php

namespace ben\BedrockCoins\commands;

use ben\BedrockCoins\BedrockCoins;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class PayCoinsCommand extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = []) {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!$sender instanceof Player) return;
        if (!isset($args[0]) || !isset($args[1])) {
            $sender->sendMessage(BedrockCoins::getInstance()->getMessage(BedrockCoins::getInstance()->language["commandusage"], null, null, "/paycoins <player> <coins>"));
            return;
        }
        if ($args[0] === $sender->getName()) {
            $sender->sendMessage(BedrockCoins::getInstance()->getMessage(BedrockCoins::getInstance()->language["commandusage"], null, null, "/paycoins <player> <coins>"));
            return;
        }
        if (!BedrockCoins::getInstance()->database->existsPlayer($args[0])) {
            $sender->sendMessage(BedrockCoins::getInstance()->getMessage(BedrockCoins::getInstance()->language["playernotexisting"], $args[0]));
            return;
        }
        $coins = $args[1];
        if (!is_numeric($coins)) {
            $sender->sendMessage(BedrockCoins::getInstance()->getMessage(BedrockCoins::getInstance()->language["commandusage"], null, null, "/paycoins <player> <coins>"));
            return;
        }
        if (!BedrockCoins::getInstance()->getCoins($sender->getName()) >= $coins) {
            $sender->sendMessage(BedrockCoins::getInstance()->getMessage(BedrockCoins::getInstance()->language["notenoughcoins"], null, $coins));
            return;
        }
        $sender->sendMessage(BedrockCoins::getInstance()->getMessage(BedrockCoins::getInstance()->language["paycoins"], $args[0], $coins));
        BedrockCoins::getInstance()->removeCoins($sender->getName(), $coins);
        BedrockCoins::getInstance()->addCoins($args[0], $coins);
        $player = BedrockCoins::getInstance()->getInstance()->getServer()->getPlayer($args[0]);
        if ($player != null && $player->isOnline()) {
            $player->sendMessage(BedrockCoins::getInstance()->getMessage(BedrockCoins::getInstance()->language["receivecoins"], $sender->getName(), $coins));
        }
    }

}
