<?php

abstract class WikiaRegistry implements ArrayAccess {

	protected function validatePropertyName($propertyName) {
		if (empty($propertyName) || is_numeric($propertyName)) {
			throw new WikiaException("WikiaProperty - invalid or empty property name ($propertyName)");
		}
	}

	abstract public function get($propertyName);
	abstract public function set($propertyName, $value);
	abstract public function remove($propertyName);
	abstract public function has($propertyName);

	public function offsetExists($offset) {
		return $this->has($offset);
	}

	public function offsetGet($offset) {
		return $this->get($offset);
	}

	public function offsetSet($offset, $value) {
		$this->set($offset, $value);
	}

	public function offsetUnset($offset) {
		return $this->remove($offset);
	}

}