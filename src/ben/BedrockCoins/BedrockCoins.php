<?php

namespace ben\BedrockCoins;

use ben\BedrockCoins\commands\AddCoinsCommand;
use ben\BedrockCoins\commands\MyCoinsCommand;
use ben\BedrockCoins\commands\PayCoinsCommand;
use ben\BedrockCoins\commands\RemoveCoinsCommand;
use ben\BedrockCoins\commands\SeeCoinsCommand;
use ben\BedrockCoins\commands\SetCoinsCommand;
use ben\BedrockCoins\database\Database;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

class BedrockCoins extends PluginBase {

    private static $instance;

    public static $prefix = TextFormat::DARK_GRAY . "BedrockCoins" . TextFormat::BOLD . " »" . TextFormat::RESET;
    
    public $language = [];

    public $startcoins = 0;

    public $database;

    public function onLoad() {
        $this->getLogger()->info("Loading plugin");

        self::$instance = $this;

        $this->saveDefaultConfig();

        $this->loadLanguage();
        $this->loadPrefix();
        $this->loadStartcoins();
    }

    public function onEnable() {

        $this->getLogger()->info("BedrockCoins has been enabled");

        $this->database = new Database();

        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);

        $this->getServer()->getCommandMap()->register("mycoins", new MyCoinsCommand("mycoins", "Shows your coins"));
        $this->getServer()->getCommandMap()->register("seecoins", new SeeCoinsCommand("seecoins", "Shows coins of player"));
        $this->getServer()->getCommandMap()->register("addcoins", new AddCoinsCommand("addcoins", "Adds coins to a player"));
        $this->getServer()->getCommandMap()->register("removecoins", new RemoveCoinsCommand("removecoins", "Removes coins of a player"));
        $this->getServer()->getCommandMap()->register("setcoins", new SetCoinsCommand("setcoins", "Sets coins of a player"));
        $this->getServer()->getCommandMap()->register("paycoins", new PayCoinsCommand("paycoins", "Pays coins to a player"));

    }

    private function loadPrefix() {
        $config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
        self::$prefix = $config->get("prefix");
    }

    private function loadLanguage() {
        foreach ($this->getResources() as $resource) {
            if ($resource->isFile() && $resource->getFilename() === "language.json") {
                self::getInstance()->language = json_decode(file_get_contents($resource), true);
            }
        }
    }

    private function loadStartcoins() {
        $config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
        $coins = $config->get("startcoins");
        if (!is_numeric($coins)) {
            $this->getLogger()->error("Startcoins has to be an integer");
            $this->getServer()->getPluginManager()->disablePlugin($this);
            return;
        }
        self::getInstance()->startcoins = $coins;
    }

    public function getMessage($message, $name = null, $coins = null, $usage = null) {
        $message = str_replace("{prefix}", self::$prefix, $message);
        if ($name != null) {
            $message = str_replace("{playername}", $name, $message);
        }
        if ($coins != null) {
            $message = str_replace("{coins}", $coins, $message);
        }
        if ($usage != null) {
            $message = str_replace("{usage}", $usage, $message);
        }
        return $message;
    }

    public static function getCoins($name) : int {
        if (self::getInstance()->database->existsPlayer($name)) {
            return self::getInstance()->database->getCoins($name)["p_coins"];
        }
        return null;
    }

    public function addCoins($name, int $coins) {
        if (self::getInstance()->database->existsPlayer($name)) {
            self::getInstance()->database->addCoins($name, $coins);
        }
    }

    public function removeCoins($name, int $coins) {
        if (self::getInstance()->database->existsPlayer($name)) {
            self::getInstance()->database->removeCoins($name, $coins);
        }
    }

    public function setCoins($name, int $coins) {
        if (self::getInstance()->database->existsPlayer($name)) {
            self::getInstance()->database->setCoins($name, $coins);
        }
    }

    public static function getInstance() : self {
        return BedrockCoins::$instance;
    }

}
