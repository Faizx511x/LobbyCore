<?php

namespace NordMC;

// POCKETMINE

use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageByChildEntityEvent;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\player\Player;
use pocketmine\player\GameMode;
use pocketmine\event\EventPriority;
use pocketmine\event\entity\ProjectileHitEvent;
use pocketmine\entity\Living;
use pocketmine\item\ItemFactory;
use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use pocketmine\math\Vector3;
use pocketmine\world\Position;
use pocketmine\world\World;
use pocketmine\utils\TextFormat as MG;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\utils\Config;

//Events

use NordMC\Event\Protection;

//Commands

use NordMC\Commands\HubCommand;
use NordMC\Commands\ItemCommand;

// FORM

use Vecnavium\FormsUI\Form;
use Vecnavium\FormsUI\FormAPI;
use Vecnavium\FormsUI\SimpleForm;

//AlwaysDay

use LobbyCore\Task\AlwaysDay;


class LobbyCore extends PluginBase implements Listener {

    public function onEnable(): void {
        $this->getLogger()->info("§aEnabled PowrCore");
        $this->getScheduler()->scheduleRepeatingTask(new AlwaysDay(), 40);
        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new Protection(), $this);
        $this->saveResource("config.yml");
        $this->getServer()->getCommandMap()->register("/hub", new HubCommand());
        $this->getServer()->getCommandMap()->register("/item", new ItemCommand());
    }

    public function onDisable(): void {
        $this->getLogger()->info("§cDisabled LobbyCore");
    }
    public function getCosmetics(Player $player){
        $form = new SimpleForm(function(Player $player, int $data = null){
            if($data === null){
                return true;
            }
            switch($data){
                case 0:
                    if ($player->hasPermission("core.use.fly")){
                        $this->FlyForm($player);
                    } else {
                        $player->sendMessage($this->plugin->getConfig()->get("§cYou not have permissions to use cosmetics Fly"));
                    }
                break;

                case 1:
                    if ($player->hasPermission("core.use.size")){
                        $this->SizeForm($player);
                    } else {
                        $player->sendMessage($this->plugin->getConfig()->get("§cYou not have permissions to use cosmetics Size"));
                    }
                break;

                case 2:
                    $this->getServer()->getCommandMap()->dispatch($player, "nick");
                break;

                case 3:
                    if ($player->hasPermission("core.use.namecolor")){
                        $this->NameColorForm($player);
                    } else {
                        $player->sendMessage($this->plugin->getConfig()->get("§cYou not have permissions to use cosmetics NameColor"));
                    }
                break;

                case 4:
                    $this->getServer()->getCommandMap()->dispatch($player, "cape");
                break;

                case 5:
                   
                break;
            }
        });
        $form->setTitle(MG::YELLOW . $this->plugin->getConfig()->get("CosmeticTitle"));
        $form->setContent(MG::RED . $this->plugin->getConfig()->get("CosmeticInfo"));
        $form->addButton(MG::RED . $this->plugin->getConfig()->get("CosmeticForm1"));
        $form->addButton(MG::RED . $this->plugin->getConfig()->get("CosmeticForm2"));
        $form->addButton(MG::RED . $this->plugin->getConfig()->get("CosmeticForm3"));
        $form->addButton(MG::RED . $this->plugin->getConfig()->get("CosmeticForm4"));
        $form->addButton(MG::RED . $this->plugin->getConfig()->get("CosmeticForm5"));
        $form->addButton(MG::RED . "EXIT");
        $form->sendToPlayer($player);
    }

    public function FlyForm(Player $player){
        $form = new SimpleForm(function(Player $player, int $data = null){
            if($data === null){
                return true;
            }
            switch($data){
                case 0:
                    $player->setFlying(true);
                    $player->setAllowFlight(true);
                    $player->sendMessage("§aFLY §aON");
                    $player->sendTitle("§aFLY §aON");
                    break;
                case 1:
                    $player->setFlying(false);
                    $player->setAllowFlight(false);
                    $player->sendMessage("FLY OFF");
                    $player->sendTitle("FLY OFF");
                    break;
            }
        });
        $form->setTitle(MG::BLUE . $this->plugin->getConfig()->get("FlyTitle"));
        $form->setContent(MG::GRAY . $this->plugin->getConfig()->get("FlyInfo"));
        $form->addButton(MG::GREEN . $this->plugin->getConfig()->get("FlyForm1"));
        $form->addButton(MG::RED . $this->plugin->getConfig()->get("FlyForm2"));
        $form->addButton(MG::RED . "EXIT");
        $form->sendToPlayer($player);
    }

    public function SizeForm(Player $player){
        $form = new SimpleForm(function(Player $player, int $data = null){
            if($data === null){
                return true;
            }
            switch($data){
                case 0:
                    $player->setScale("0.5");
                    $player->sendMessage(MG::GREEN . $this->plugin->getConfig()->get("SizeMessageSmall"));
                    break;
                case 1:
                    $player->setScale("1.0");
                    $player->sendMessage(MG::GREEN . $this->plugin->getConfig()->get("SizeMessageNormal"));
                    break;
                case 2:
                    $player->setScale("1.5");
                    $player->sendMessage(MG::GREEN . $this->plugin->getConfig()->get("SizeMessageBig"));
                    break;
            }
        });
        $form->setTitle(MG::BLUE . $this->plugin->getConfig()->get("SizeTitle"));
        $form->setContent(MG::GRAY . $this->plugin->getConfig()->get("SizeInfo"));
        $form->addButton(MG::GREEN . $this->plugin->getConfig()->get("SizeForm1"));
        $form->addButton(MG::GREEN . $this->plugin->getConfig()->get("SizeForm2"));
        $form->addButton(MG::GREEN . $this->plugin->getConfig()->get("SizeForm3"));
        $form->addButton(MG::RED . "EXIT");
        $form->sendToPlayer($player);
    }

    public function NameColorForm(Player $player){
        $form = new SimpleForm(function (Player $player, $data = null){
            if($data === null){
                return true;
            }
            switch($data){
                case 0:
                    $player->setDisplayName("§f" . $player->getName() . "§f");
                    $player->setNameTag("§f" . $player->getName() . "§f");
                    $player->sendMessage("§aYour nickname color has been changed to §fWhite!");
                break;
                case 1:
                    $player->setDisplayName("§c" . $player->getName() . "§f");
                    $player->setNameTag("§c" . $player->getName() . "§f");
                    $player->sendMessage("§aYour nickname color has been changed to §cRed!");
                break;
                case 2:
                    $player->setDisplayName("§b" . $player->getName() . "§f");
                    $player->setNameTag("§b" . $player->getName() . "§f");
                    $player->sendMessage("§aYour nickname color has been changed to §bBlue!");
                break;
                case 3:
                    $player->setDisplayName("§e" . $player->getName() . "§f");
                    $player->setNameTag("§e" . $player->getName() . "§f");
                    $player->sendMessage("§aYour nickname color has been changed to §eYellow!");
                break;
                case 4:
                    $player->setDisplayName("§6" . $player->getName() . "§f");
                    $player->setNameTag("§6" . $player->getName() . "§f");
                    $player->sendMessage("§aYour nickname color has been changed to §6Orange!");
                break;
                case 5:
                    $player->setDisplayName("§d" . $player->getName() . "§f");
                    $player->setNameTag("§d" . $player->getName() . "§f");
                    $player->sendMessage("§aYour nickname color has been changed to §dPurple!");
                break;
                case 6:
                    $player->setDisplayName("§0" . $player->getName() . "§f");
                    $player->setNameTag("§0" . $player->getName() . "§f");
                    $player->sendMessage("§aYour nickname color has been changed to §0Black!");
                break;
                case 0:

                break;
            }
        });
        $form->setTitle("§bNameColors");
        $form->setContent("§fSelect your color you prefer to your name!");
        $form->addButton("§fWhite");
        $form->addButton("§cRed");
        $form->addButton("§bBlue");
        $form->addButton("§eYellow");
        $form->addButton("§6Orange");
        $form->addButton("§dPurple");
        $form->addButton("§0Black");
        $form->addButton("§0Black");
        $form->addButton(MG::RED . "EXIT");
        $form->sendToPlayer($player);
    }

    public function getGames(Player $player){
        $form = new SimpleForm(function(Player $player, int $data = null){
            if($data === null){
                return true;
            }
            switch($data){
                case 0:
                    $this->plugin->getServer()->getCommandMap()->dispatch($player, $this->plugin->getConfig()->get("CommandForm1"));
                    break;
                case 1:
                    $this->plugin->getServer()->getCommandMap()->dispatch($player, $this->plugin->getConfig()->get("CommandForm2"));
                    break;
                case 2:
                    $this->plugin->getServer()->getCommandMap()->dispatch($player, $this->plugin->getConfig()->get("CommandForm3"));
                    break;
                case 3:
                    $this->plugin->getServer()->getCommandMap()->dispatch($player, $this->plugin->getConfig()->get("CommandForm4"));
                    break;
                case 4:
                    $this->plugin->getServer()->getCommandMap()->dispatch($player, $this->plugin->getConfig()->get("CommandForm5"));
                    break;
                case 5:
                    $this->plugin->getServer()->getCommandMap()->dispatch($player, $this->plugin->getConfig()->get("CommandForm6"));
                    break;
            }
        });
        $form->setTitle(MG::RED . $this->plugin->getConfig()->get("GameTitle"));
        $form->setContent(MG::RED . $this->plugin->getConfig()->get("GameInfo"));
        $form->addButton(MG::RED . $this->plugin->getConfig()->get("GameForm1"));
        $form->addButton(MG::RED . $this->plugin->getConfig()->get("GameForm2"));
        $form->addButton(MG::RED . $this->plugin->getConfig()->get("GameForm3"));
        $form->addButton(MG::RED . $this->plugin->getConfig()->get("GameForm4"));
        $form->addButton(MG::RED . $this->plugin->getConfig()->get("GameForm5"));
        $form->addButton(MG::RED . $this->plugin->getConfig()->get("GameForm6"));
        $form->addButton(MG::RED . "EXIT");
        $form->sendToPlayer($player);
    }

    public function getSocialMenu(Player $player){
        $form = new SimpleForm(function(Player $player, int $data = null){
            if($data === null){
                return true;
            }
            switch($data){
                case 0:
                    $this->getServer()->getCommandMap()->dispatch($player, "party");
                    $player->sendMessage("§cSoon...");
                break;
                case 1:
                    $this->getServer()->getCommandMap()->dispatch($player, "friend");
                    $player->sendMessage("§cSoon...");
                break;

                case 2:
                    
                break;
            }
        });
        $form->setTitle("§bSocial Menu");
        $form->setContent("§cSoon..");
        $form->addButton("§eParty");
        $form->addButton("§eFriends");
        $form->addButton(MG::RED . "EXIT");
        $form->sendToPlayer($player);
    }

    private $plugin;

    public function onJoin(PlayerJoinEvent $event)
    {

        $player = $event->getPlayer();
        $name = $player->getName();

        $event->setJoinMessage("");
        $this->plugin = LobbyCore::getInstance();
        Server::getInstance()->broadcastMessage(str_replace(["{player}"], [$player->getName()], $this->plugin->getConfig()->get("Join-Message")));
        $player->teleport($player->getServer()->getWorldManager()->getDefaultWorld()->getSafeSpawn());

        $item1 = ItemFactory::getInstance()->get(345, 0, 1)->setCustomName("§r§bCosmetics");
        $item2 = ItemFactory::getInstance()->get(54, 0, 1)->setCustomName("§r§cReport Player");
        $item3 = ItemFactory::getInstance()->get(340, 0, 1)->setCustomName("§r§aGames");
        $item4 = ItemFactory::getInstance()->get(345, 0, 1)->setCustomName("§r§dSocial Menu §7[Use]");
        $item5 = ItemFactory::getInstance()->get(54, 0, 1)->setCustomName("§r§5Lobby");

        $player->getInventory()->clearAll();
        $player->getInventory()->setItem(0, $item1);
        $player->getInventory()->setItem(1, $item2);
        $player->getInventory()->setItem(4, $item3);
        $player->getInventory()->setItem(7, $item4);
        $player->getInventory()->setItem(8, $item5);
    }

    public function onQuit(PlayerQuitEvent $event){

        $player = $event->getPlayer();
        $name = $player->getName();

        $event->setQuitMessage("");
        Server::getInstance()->broadcastMessage(str_replace(["{player}"], [$player->getName()], $this->plugin->getConfig()->get("Quit-Message")));
    }
    
    public function onClick(PlayerInteractEvent $event)
    {
        $player = $event->getPlayer();
        $itn = $player->getInventory()->getItemInHand()->getCustomName();
        if ($itn == "§r§bCosmetics") {
            LobbyCore::getInstance()->getUI()->getCosmetics($player);
        }
        if ($itn == "§r§cReport Player") {
            $this->getServer()->getCommandMap()->dispatch($player, "report");
        }
        if ($itn == "§r§aGames") {
            LobbyCore::getInstance()->getUI()->getGames($player);
        }
        if ($itn == "§r§dSocial Menu §7[Use]") {
            LobbyCore::getInstance()->getUI()->getSocialMenu($player);
        }
        if ($itn == "§r§5Lobby") {
            $this->getServer()->getCommandMap()->dispatch($player, "hub");
        }
    }
}
