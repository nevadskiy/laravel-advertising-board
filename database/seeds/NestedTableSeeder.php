<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Database\Seeder;

class NestedTableSeeder extends Seeder
{
    /**
     * @var string
     */
    protected $model = Model::class;

    /**
     * @var string
     */
    protected $childRelation = 'children';

    /**
     * @var
     */
    protected $minLevelSize;

    /**
     * @var
     */
    protected $maxLevelSize;

    /**
     * @var
     */
    protected $firstLevelSize;

    /**
     * @param int $depth
     * @param int $minLevelSize
     * @param int|null $maxLevelSize
     */
    protected function seed(int $depth, int $minLevelSize, int $maxLevelSize = null)
    {
        $this->minLevelSize = $minLevelSize;
        $this->maxLevelSize = $maxLevelSize;

        $this->seedTree($depth);
    }

    /**
     * @param int $size
     * @return $this
     */
    protected function setFirstLevelSize(int $size)
    {
        $this->firstLevelSize = $size;

        return $this;
    }

    /**
     * Recursive seed a tree
     *
     * @param int $depthLeft
     * @param Collection|null $currentLevel
     * @return mixed
     */
    private function seedTree(int $depthLeft, Collection $currentLevel = null)
    {
        $level = $currentLevel ?: $this->seedLevel();

        if ($depthLeft === 1) {
            return $level;
        }

        return $level->each(function ($category) use ($depthLeft, $level) {
            if (!$category instanceof $this->model) {
                throw new LogicException('Wrong model is set for nested seeder');
            }

            $category->{$this->childRelation}()->saveMany(
                $level = $this->seedLevel()
            );

            $this->seedTree($depthLeft - 1, $level);
        });
    }

    /**
     * @return mixed
     */
    private function seedLevel()
    {
        $count = $this->maxLevelSize
            ? rand($this->minLevelSize, $this->maxLevelSize)
            : $this->minLevelSize;

        return factory($this->model, $count)->create();
    }
}
