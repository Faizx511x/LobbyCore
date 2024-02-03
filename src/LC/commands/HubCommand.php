<?php

namespace LC\commands;

use pocketmine\entity\object\ItemEntity;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemTypeIds;
use pocketmine\item\VanillaItems;
use pocketmine\block\VanillaBlocks;
use pocketmine\player\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as MG;                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               
use pocketmine\Server;
use pocketmine\plugin\Plugin;
use pocketmine\item\Item;

use LC\LobbyCore;

class HubCommand extends Command
{
    private $plugin;

    public function __construct()
    {
        parent::__construct("hub", "hub command", null, ["spawn"]);
        $this->setPermission("lobbycore.command.hub");
    }

    public function execute(CommandSender $player, string $label, array $args)
    {
        if ($player instanceof Player) {
            if (!$player->hasPermission("lobbycore.command.hub")) {
                $player->sendMessage("No tienes permisos");
            } else {
                $this->plugin = LobbyCore::getInstance();
                $player->teleport($player->getServer()->getWorldManager()->getDefaultWorld()->getSafeSpawn());
                $player->getInventory()->clearALL();
                $player->getArmorInventory()->clearALL();
                $player->sendMessage(str_replace(["{player}"], [$player->getName()], $this->plugin->getConfig()->get("Hub-Message")));
                
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
        }
    }
}
