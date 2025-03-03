<?php

declare(strict_types=1);

namespace App\Services\Dummy;

use App\Models\Dummy as DummyModel;
use App\Schemas\Dummy\Index as DummyList;
use App\Schemas\Dummy\Request;
use App\Schemas\Dummy\Response as DummyResponse;
use App\Services\BadRequestException;

class Service
{
	public function list(): DummyList
	{
		$list = new DummyList();
		$models = DummyModel::find();
		/** @var DummyModel $model */
		foreach ($models as $model) {
			$list->addItems(new DummyResponse($model->toArray()));
		}

		return $list;
	}

	public function get(int $id): DummyResponse
	{
		/* @var DummyModel|null $model */
		$model = DummyModel::findFirst($id);
		if (null === $model) {
			throw new BadRequestException('Model not found');
		}

		return new DummyResponse($model->toArray());
	}

	public function delete(int $id): void
	{
		/* @var DummyModel|null $model */
		$model = DummyModel::findFirst($id);
		if (null === $model) {
			throw new BadRequestException('Model not found');
		}

		$model->delete();
	}

	public function create(Request $dummyRequestScheme): DummyResponse
	{
		/* @var DummyModel $model */
		$model = new DummyModel();
		$model
			->setName($dummyRequestScheme->getName())
			->save();

		return new DummyResponse($model->toArray());
	}

	public function update(int $id, Request $dummyRequestScheme): DummyResponse
	{
		/* @var DummyModel|null $model */
		$model = DummyModel::findFirst($id);
		if (null === $model) {
			throw new BadRequestException('Model not found');
		}

		$model
			->setName($dummyRequestScheme->getName())
			->update();

		return new DummyResponse($model->toArray());
	}
}
