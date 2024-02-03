<?php

namespace LC\event;

use LC\api\ItemManager;
use LC\block\RegisterBlocks;
use pocketmine\block\Block;
use pocketmine\block\BlockTypeIds;
use pocketmine\block\VanillaBlocks;
use pocketmine\data\bedrock\item\BlockItemIdMap;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemTypeIds;
use pocketmine\item\VanillaItems;
use pocketmine\Server;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat as MG;
use pocketmine\event\player\PlayerInteractEvent;

use Vecnavium\FormsUI\Form;
use Vecnavium\FormsUI\FormAPI;
use Vecnavium\FormsUI\SimpleForm;
use LC\LobbyCore;

class EventListener implements Listener
{

    private $plugin;

    public function onJoin(PlayerJoinEvent $event)
    {

        $player = $event->getPlayer();
        $name = $player->getName();

        $event->setJoinMessage("");
        $this->plugin = LobbyCore::getInstance();
        Server::getInstance()->broadcastMessage(str_replace(["{player}"], [$player->getName()], "§8[§b+§8]§a{player}"));
        $player->teleport(Server::getInstance()->getWorldManager()->getDefaultWorld()->getSafeSpawn());
        $player->getInventory()->clearALL();
        $player->getArmorInventory()->clearALL();

        $item1 = VanillaBlocks::ENDER_CHEST()->asItem();
        $item1->setCustomName("§l§f>>§bCosmetics§f<<§r");
        
        $item2 = VanillaBlocks::ANVIL()->asItem();
        $item2->setCustomName("§l§f>>§cReport Player§f<<§r");

        $item3 = VanillaItems::COMPASS();
        $item3->setCustomName("§l§f>>§aTeleporter§f<<§r");

        $item4 = VanillaItems::BOOK();
        $item4->setCustomName("§l§f>>§dInformacion§f<<§r");

        $item5 = VanillaItems::NETHER_STAR();
        $item5->setCustomName("§l§f>>§5Lobby§f<<§r");


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
        Server::getInstance()->broadcastMessage(str_replace(["{player}"], [$player->getName()], "§8[§c-§8]§c{player}"));
    }
	
    public function onClick(PlayerInteractEvent $event)
    {
        $player = $event->getPlayer();
        $itn = $player->getInventory()->getItemInHand()->getCustomName();
        if ($itn == "§l§f>>§bCosmetics§f<<§r") {
            LobbyCore::getInstance()->getUI()->getCosmetics($player);
        }
        if ($itn == "§l§f>>§cReport Player§f<<§r"){
            $this->plugin->getServer()->getCommandMap()->dispatch($player, "report");
        }
        if ($itn == "§l§f>>§aTeleporter§f<<§r") {
            LobbyCore::getInstance()->getUI()->getGames($player);
        }
        if ($itn == "§l§f>>§dInformacion§f<<§r") {
            LobbyCore::getInstance()->getUI()->getInfo($player);
        }
        if ($itn == "§l§f>>§5Lobby§f<<§r"){
            $this->plugin->getServer()->getCommandMap()->dispatch($player, "hub");
        }
    }
}
