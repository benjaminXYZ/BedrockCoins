<?php

namespace ben\BedrockCoins\database;

use ben\BedrockCoins\BedrockCoins;
use ben\BedrockCoins\database\Medoo;
use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

class Database {

    public static $self;

    public $medoo;

    public function __construct() {

        self::$self = $this;

        $cfg = new Config(BedrockCoins::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        $address = $cfg->get("mysql")["host"];
        $name = $cfg->get("mysql")["database"];
        $user = $cfg->get("mysql")["user"];
        $password = $cfg->get("mysql")["password"];
        $port = $cfg->get("mysql")["database"];

        $this->medoo = new Medoo([
            'database_type' => 'mysql',
            'database_name' => $name,
            'server' => $address,
            'port' => 3306,
            'username' => $user,
            "password" => $password
        ]);

        if (!$this->isTableInitialized()) {
            if (!$this->initializeTable()) {
                BedrockCoins::getInstance()->getServer()->getPluginManager()->disablePlugin(BedrockCoins::getInstance());
            }
        }

    }

    public function isTableInitialized(): bool {
        $query = $this->medoo->query('SELECT 1 FROM `bedrockcoins` LIMIT 1;')->errorCode();

        if ($query == "00000") {
            return true;
        }
        return false;
    }

    public function initializeTable(): bool {
        BedrockCoins::getInstance()->getLogger()->notice("Creating table");
        $query = $this->medoo->query('CREATE TABLE `bedrockcoins` (
	`uuid` CHAR(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`p_name` VARCHAR(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`p_coins` INT unsigned NOT NULL DEFAULT \'0\',
	UNIQUE KEY `uuid` (`uuid`) USING BTREE,
	PRIMARY KEY (`uuid`)
) ENGINE=InnoDB;')->errorCode();
        if ($query == "00000") {
            BedrockCoins::getInstance()->getLogger()->notice('Table has been created');
            return true;
        }
        BedrockCoins::getInstance()->getLogger()->error("Failed to create table. Error code " . TextFormat::RED . $query);
        return false;
    }

    public function existsPlayer(string $name) : bool {
        return $this->medoo->has("bedrockcoins", ["p_name" => $name]);
    }

    public function isPlayerInitialized(Player $player) : bool {
        return $this->medoo->has("bedrockcoins", ["p_name" => $player->getName()]);
    }

    public function initializePlayer(Player $player) : void {
        $name = $player->getName();
        $uuid = $player->getUniqueId()->toString();
        $coins = BedrockCoins::$startcoins;
        $this->medoo->insert("bedrockcoins", ["uuid" => $uuid, "p_name" => $name, "p_coins" => $coins]);
    }

    public function updatePlayer(Player $player) : void {
        $name = $player->getName();
        $this->medoo->update("bedrockcoins", ["p_name" => $name]);
    }

    public function addCoins(string $name, int $coins) : void {
        $this->medoo->update("bedrockcoins", ["p_coins[+]" => $coins], ["p_name" => $name]);
    }

    public function removeCoins(string $name, int $coins): void {
        $this->medoo->update("bedrockcoins", ["p_coins[-]" => $coins], ["p_name" => $name]);
    }

    public function setCoins(string $name, int $coins) : void {
        $this->medoo->update("bedrockcoins", ["p_coins" => $coins], ["p_name" => $name]);
    }

    public function getCoins(string $name) : array {
        return $this->medoo->get("bedrockcoins", ["p_coins"], ["p_name" => $name]);
    }

}