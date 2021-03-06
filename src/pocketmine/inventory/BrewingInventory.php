<?php

namespace pocketmine\inventory;

use pocketmine\item\Item;
use pocketmine\tile\BrewingStand;

class BrewingInventory extends ContainerInventory{

	public function __construct(BrewingStand $tile){
		parent::__construct($tile, InventoryType::get(InventoryType::BREWING_STAND));
	}

	/**
	 *
	 * @return BrewingStand
	 */
	public function getHolder(){
		return $this->holder;
	}

	public function setIngredient(Item $item){
		$this->setItem(0, $item);
	}

	/**
	 *
	 * @return Item
	 */
	public function getIngredient(){
		return $this->getItem(0);
	}

	public function onSlotChange($index, $before, $send){
		parent::onSlotChange($index, $before, $send);
		$this->getHolder()->scheduleUpdate();
		$this->getHolder()->updateSurface();
	}

	public function getResult(){
		if($brew = $this->getHolder()->getServer()->getCraftingManager()->matchBrewingRecipe($this->getIngredient())){
			$canbrew = ($brew instanceof BrewingRecipe);
			if($canbrew) return $brew->getResult();
		}
		return Item::get(Item::AIR, 0, 1);
	}
}