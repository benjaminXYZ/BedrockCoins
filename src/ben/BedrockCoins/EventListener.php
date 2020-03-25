<?php

namespace ben\BedrockCoins;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerLoginEvent;

class EventListener implements Listener {

    public function onLogin(PlayerLoginEvent $event) {
        $player = $event->getPlayer();
        if (!BedrockCoins::getInstance()->database->isPlayerInitialized($player)) {
            BedrockCoins::getInstance()->database->initializePlayer($player);
        }
    }

}