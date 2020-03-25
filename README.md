##BedrockCoins
BedrockCoins is a simple coin plugin for PocketMine-MP which only supports MySQL.

##Configuration
You can change the prefix, startcoins and your MySQL data in `plugin_data/BedrockCoins/config.yml`

##Language
You can edit the language easily in the language.json file in `BedrockCoins/resources/`

##Commands

| Default command | Parameter | Description | Permission |
| :-----: | :--------: | :---------: | :----------: |
| /mycoins | --- | Shows your coins | --- |
| /paycoins | `<player>` `<coins>` | Pays coins to a player | --- |
| /seecoins | `<player>` | Shows coins of player | bedrockcoins.command.seecoins |
| /addcoins | `<player>` `<coins>` | Adds coins to a player | bedrockcoins.command.addcoins |
| /removecoins | `<player>` `<coins>` | Removes coins of a player | bedrockcoins.command.removecoins |
| /setcoins | `<player>` `<coins>` | Sets coins of a player | bedrockcoins.command.setcoins |

##Developer Stuff

###Get coin
```php
BedrockCoins::getInstance()->getCoins($playername, $coins);
```

###Add coins

```php
BedrockCoins::getInstance()->addCoins($playername, $coins);
```

###Remove coins

```php
BedrockCoins::getInstance()->removeCoins($playername, $coins);
```

###Set coins

```php
BedrockCoins::getInstance()->setCoins($playername, $coins);
```
