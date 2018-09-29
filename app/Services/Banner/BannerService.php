<?php

namespace App\Services\Banners;

use App\Entity\Advert\Category;
use App\Entity\Banner\Banner;
use App\Entity\Region;
use App\Entity\User;
use App\Http\Requests\Banner\CreateRequest;
use App\Http\Requests\Banner\EditRequest;
use App\Http\Requests\Banner\FileRequest;
use App\Http\Requests\Banner\RejectRequest;
use App\Services\Banner\CostCalculator;
use Carbon\Carbon;
use Elasticsearch\Client;
use Illuminate\Support\Facades\Storage;

class BannerService
{
    /**
     * @var CostCalculator
     */
    private $calculator;
    /**
     * @var Client
     */
    private $client;

    /**
     * BannerService constructor.
     * @param CostCalculator $calculator
     * @param Client $client
     */
    public function __construct(CostCalculator $calculator, Client $client)
    {
        $this->calculator = $calculator;
        $this->client = $client;
    }

    /**
     * @param int|null $categoryId
     * @param int|null $regionId
     * @param $format
     * @return Banner|null
     */
    public function getRandomForView(?int $categoryId, ?int $regionId, $format): ?Banner
    {
        $response = $this->client->search([
            'index' => 'banners',
            'type' => 'banner',
            'body' => [
                '_source' => ['id'],
                'size' => 5,
                'sort' => [
                    '_script' => [
                        'type' => 'number',
                        'script' => 'Math.random() * 200000',
                        'order' => 'asc',
                    ],
                ],
                'query' => [
                    'bool' => [
                        'must' => [
                            ['term' => ['status' => Banner::STATUS_ACTIVE]],
                            ['term' => ['format' => $format ?: '']],
                            ['term' => ['categories' => [$categoryId, 0] ?: 0]],
                            ['term' => ['regions' => $regionId ?: 0]],
                        ],
                    ],
                ],
            ],
        ]);

        if (!$ids = array_column($response['hits']['hits'], '_id')) {
            return null;
        }

        $banner = Banner::active()
            ->with(['category', 'region'])
            ->whereIn('id', $ids)
            ->orderByRaw('FIELD(id,' . implode(',', $ids) . ')')
            ->first();

        if (!$banner) {
            return null;
        }

        $banner->view();
        return $banner;
    }

    /**
     * @param User $user
     * @param Category $category
     * @param Region|null $region
     * @param CreateRequest $request
     * @return Banner
     * @throws \Throwable
     */
    public function create(User $user, Category $category, ?Region $region, CreateRequest $request): Banner
    {
        /** @var Banner $banner */
        $banner = Banner::make([
            'name' => $request['name'],
            'limit' => $request['limit'],
            'url' => $request['url'],
            'format' => $request['format'],
            'file' => $request->file('file')->store('banners', 'public'),
            'status' => Banner::STATUS_DRAFT,
        ]);

        $banner->user()->associate($user);
        $banner->category()->associate($category);
        $banner->region()->associate($region);

        $banner->saveOrFail();

        return $banner;
    }

    /**
     * @param $id
     * @param FileRequest $request
     */
    public function changeFile($id, FileRequest $request): void
    {
        $banner = $this->getBanner($id);
        if (!$banner->canBeChanged()) {
            throw new \DomainException('Unable to edit the banner.');
        }
        Storage::delete('public/' . $banner->file);
        $banner->update([
            'format' => $request['format'],
            'file' => $request->file('file')->store('banners', 'public'),
        ]);
    }

    /**
     * @param $id
     * @param EditRequest $request
     */
    public function editByOwner($id, EditRequest $request): void
    {
        $banner = $this->getBanner($id);
        if (!$banner->canBeChanged()) {
            throw new \DomainException('Unable to edit the banner.');
        }
        $banner->update([
            'name' => $request['name'],
            'limit' => $request['limit'],
            'url' => $request['url'],
        ]);
    }

    /**
     * @param $id
     * @param EditRequest $request
     */
    public function editByAdmin($id, EditRequest $request): void
    {
        $banner = $this->getBanner($id);
        $banner->update([
            'name' => $request['name'],
            'limit' => $request['limit'],
            'url' => $request['url'],
        ]);
    }

    /**
     * @param $id
     */
    public function sendToModeration($id): void
    {
        $banner = $this->getBanner($id);
        $banner->sendToModeration();
    }

    /**
     * @param $id
     */
    public function cancelModeration($id): void
    {
        $banner = $this->getBanner($id);
        $banner->cancelModeration();
    }

    /**
     * @param $id
     */
    public function moderate($id): void
    {
        $banner = $this->getBanner($id);
        $banner->moderate();
    }

    /**
     * @param $id
     * @param RejectRequest $request
     */
    public function reject($id, RejectRequest $request): void
    {
        $banner = $this->getBanner($id);
        $banner->reject($request['reason']);
    }

    /**
     * @param $id
     * @return Banner
     */
    public function order($id): Banner
    {
        $banner = $this->getBanner($id);
        $cost = $this->calculator->calc($banner->limit);
        $banner->order($cost);
        return $banner;
    }

    /**
     * @param $id
     */
    public function pay($id): void
    {
        $banner = $this->getBanner($id);
        $banner->pay(Carbon::now());
    }

    /**
     * @param Banner $banner
     */
    public function click(Banner $banner): void
    {
        $banner->click();
    }

    /**
     * @param $id
     * @return Banner
     */
    private function getBanner($id): Banner
    {
        return Banner::findOrFail($id);
    }

    /**
     * @param $id
     * @throws \Exception
     */
    public function removeByOwner($id): void
    {
        $banner = $this->getBanner($id);
        if (!$banner->canBeRemoved()) {
            throw new \DomainException('Unable to remove the banner.');
        }
        $banner->delete();
        Storage::delete('public/' . $banner->file);
    }

    /**
     * @param $id
     * @throws \Exception
     */
    public function removeByAdmin($id): void
    {
        $banner = $this->getBanner($id);
        $banner->delete();
        Storage::delete('public/' . $banner->file);
    }
}
