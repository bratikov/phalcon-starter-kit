<?php

namespace App\Models;

use Phalcon\Mvc\ModelInterface;

abstract class Base extends \Phalcon\Mvc\Model implements ModelInterface
{
	abstract public function columnMap();

	abstract public function setUpdatedAt($updatedAt);

	abstract public function setCreatedAt($createdAt);

	/**
	 * Modification timestamp before save operation.
	 */
	public function beforeSave()
	{
		$this->setUpdatedAt((new \DateTime())->format('Y-m-d H:i:s'));
	}

	public function beforeCreate()
	{
		$this->setCreatedAt((new \DateTime())->format('Y-m-d H:i:s'));
	}

	public function getColumnMapForQuery(string $tableAlias = ''): string
	{
		$tableAlias = $tableAlias ? ($tableAlias.'.') : '';
		$columns = [];
		foreach ($this->columnMap() as $originName => $camelCaseName) {
			$originName = $tableAlias.$originName;
			$columns[] = $originName.' AS '.$camelCaseName;
		}

		return implode(',', $columns);
	}

	public function toArray($columns = null, $useGetter = true): array
	{
		$data = parent::toArray($columns, $useGetter);

		if (isset($data['id'])) {
			$data['id'] = (int) $data['id'];
		}

		return $data;
	}

	/**
	 * @return static
	 */
	public function fromArray(array $data)
	{
		foreach ($data as $key => $value) {
			$this->{$key} = $value;
		}

		return $this;
	}

	public function softDelete(): bool
	{
		if (
			!method_exists($this, 'getDeletedAt')
			|| !method_exists($this, 'setDeletedAt')
		) {
			throw new \RuntimeException('Incorrect softDelete()-usage');
		}

		if (null !== $this->getDeletedAt()) {
			return false;
		}

		$this->setDeletedAt((new \DateTime())->format('Y-m-d H:i:s'));

		return $this->update();
	}
}
