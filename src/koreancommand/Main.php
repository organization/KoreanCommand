<?php

namespace koreancommand;

use pocketmine\plugin\PluginBase;
use koreancommand\command\KoreanCommandMap;

class Main extends PluginBase {
	public function onLoad() {
		$this->initCommandMap();
		//$this->initCommandReader();
	}
	
	private function initCommandMap() {
		$server = $this->getServer();
		
		$serverCls = new \ReflectionClass($server);
		$commandMapProperty = $serverCls->getProperty("commandMap");
		$commandMapProperty->setAccessible(true);
		
		$currentCommandMap = $commandMapProperty->getValue($server);
		
		$krMap = new KoreanCommandMap($server);
		
		$mapCls = new \ReflectionClass($currentCommandMap);
		$commandsProperty = $mapCls->getProperty("knownCommands");
		$commandsProperty->setAccessible(true);
		$commandsProperty->setValue($krMap, $currentCommandMap->getCommands());
		
		$manager = $this->getServer()->getPluginManager();
		$managerCls = new \ReflectionClass($manager);
		$managerMapProperty = $managerCls->getProperty("commandMap");
		$managerMapProperty->setAccessible(true);
		
		$commandMapProperty->setValue($server, $krMap);
		$managerMapProperty->setValue($manager, $krMap);
	}
	
	private function initCommandReader() {
		$server = $this->getServer();
		
		$serverCls = new \ReflectionClass($server);
		$readerCls = $serverCls->getProperty("console");
		
		
	}
}

