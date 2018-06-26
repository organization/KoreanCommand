<?php

namespace koreancommand;

use pocketmine\plugin\PluginBase;
use koreancommand\command\KoreanCommandMap;

class Main extends PluginBase {
	public function onLoad() {
		$server = $this->getServer();
		
		$serverCls = new \ReflectionClass($server);
		$commandMapProperty = $serverCls->getProperty("commandMap");
		$commandMapProperty->setAccessible(true);
		
		
		$currentCommandMap = $commandMapProperty->getValue($server);
		
		$krMap = new KoreanCommandMap($server);
		
		$mapCls = new \ReflectionClass($currentCommandMap);
		$commandsProperty = $mapCls->getProperty("knownCommands");
		$commandsProperty->setAccessible(true);
		$commandsProperty->setValue($krMap, $commandsProperty->getValue($currentCommandMap));
		
		$commandMapProperty->setValue($server, $krMap);
	}
}

