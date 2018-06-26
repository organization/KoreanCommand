<?php

namespace koreancommand\command;

use pocketmine\command\SimpleCommandMap;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\command\CommandSender;
use pocketmine\lang\TranslationContainer;
use pocketmine\utils\TextFormat;

class KoreanCommandMap extends SimpleCommandMap {
	private function getServer() {
		$cls = new \ReflectionClass("pocketmine\command\SimpleCommandMap");
		$property = $cls->getProperty("server");
		$property->setAccessible(true);
		return $property->getValue($this);
	}
	
	public function dispatch(CommandSender $sender, string $commandLine) : bool{
		$args = explode(" ", $commandLine);
		$sentCommandLabel = "";
		$target = $this->matchCommand($sentCommandLabel, $args);
		
		if($target === null){
			return false;
		}
		
		$target->timings->startTiming();
		
		try{
			$target->execute($sender, $sentCommandLabel, $args);
		}catch(InvalidCommandSyntaxException $e){
			$sender->sendMessage($this->getServer()->getLanguage()->translateString("commands.generic.usage", [$target->getUsage()]));
		}catch(\Throwable $e){
			$sender->sendMessage(new TranslationContainer(TextFormat::RED . "%commands.generic.exception"));
			$this->server->getLogger()->critical($this->getServer()->getLanguage()->translateString("pocketmine.command.exception", [$commandLine, (string) $target, $e->getMessage()]));
			$sender->getServer()->getLogger()->logException($e);
		}
		
		$target->timings->stopTiming();
		
		return true;
	}
}

