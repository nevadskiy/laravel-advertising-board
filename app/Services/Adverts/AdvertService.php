<?php

namespace App\Services\Adverts;

use App\Entity\Advert\Advert;
use App\Entity\Advert\Category;
use App\Entity\Region;
use App\Entity\User\User;
use App\Http\Requests\Adverts\AttributesRequest;
use App\Http\Requests\Adverts\CreateRequest;
use App\Http\Requests\Adverts\EditRequest;
use App\Http\Requests\Adverts\PhotosRequest;
use App\Http\Requests\Adverts\RejectRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdvertService
{
    /**
     * @param $userId
     * @param $categoryId
     * @param $regionId
     * @param CreateRequest $request
     * @return Advert
     * @throws \Throwable
     */
    public function create($userId, $categoryId, $regionId, CreateRequest $request): Advert
    {
        /** @var User $user */
        $user = User::findOrFail($userId);
        /** @var Category $category */
        $category = Category::findOrFail($categoryId);
        /** @var Region $region */
        $region = $regionId ? Region::findOrFail($regionId) : null;

        return DB::transaction(function () use ($request, $user, $category, $region) {

            /** @var Advert $advert */
            $advert = Advert::make([
                'title' => $request['title'],
                'content' => $request['content'],
                'price' => $request['price'],
                'address' => $request['address'],
                'status' => Advert::STATUS_DRAFT,
            ]);

            $advert->user()->associate($user);
            $advert->category()->associate($category);
            $advert->region()->associate($region);

            $advert->saveOrFail();

            foreach ($category->allAttributes() as $attribute) {
                $value = $request['attributes'][$attribute->id] ?? null;
                if (!empty($value)) {
                    $advert->values()->create([
                        'attribute_id' => $attribute->id,
                        'value' => $value,
                    ]);
                }
            }

            return $advert;
        });
    }

    /**
     * @param $id
     * @param PhotosRequest $request
     * @throws \Throwable
     */
    public function addPhotos($id, PhotosRequest $request): void
    {
        $advert = $this->getAdvert($id);

        DB::transaction(function () use ($request, $advert) {
            foreach ($request['files'] as $file) {
                $advert->photos()->create([
                    'file' => $file->store('adverts')
                ]);
            }
            $advert->update();
        });
    }

    /**
     * @param $id
     * @param EditRequest $request
     */
    public function edit($id, EditRequest $request): void
    {
        $advert = $this->getAdvert($id);
        $advert->update($request->only([
            'title',
            'content',
            'price',
            'address',
        ]));
    }

    /**
     * @param $id
     */
    public function sendToModeration($id): void
    {
        $advert = $this->getAdvert($id);
        $advert->sendToModeration();
    }

    /**
     * @param $id
     */
    public function moderate($id): void
    {
        $advert = $this->getAdvert($id);
        $advert->moderate(Carbon::now());
    }

    /**
     * @param $id
     * @param RejectRequest $request
     */
    public function reject($id, RejectRequest $request): void
    {
        $advert = $this->getAdvert($id);
        $advert->reject($request['reason']);
    }

    /**
     * @param $id
     * @param AttributesRequest $request
     * @throws \Throwable
     */
    public function editAttributes($id, AttributesRequest $request): void
    {
        $advert = $this->getAdvert($id);

        DB::transaction(function () use ($request, $advert) {
            $advert->values()->delete();
            foreach ($advert->category->allAttributes() as $attribute) {
                $value = $request['attributes'][$attribute->id] ?? null;
                if (!empty($value)) {
                    $advert->values()->create([
                        'attribute_id' => $attribute->id,
                        'value' => $value,
                    ]);
                }
            }
            $advert->update();
        });
    }

    /**
     * @param Advert $advert
     */
    public function expire(Advert $advert): void
    {
        $advert->expire();
    }

    /**
     * @param $id
     */
    public function close($id): void
    {
        $advert = $this->getAdvert($id);
        $advert->close();
    }

    /**
     * @param $id
     * @throws \Exception
     */
    public function remove($id): void
    {
        $advert = $this->getAdvert($id);
        $advert->delete();
    }

    /**
     * @param $id
     * @return Advert
     */
    private function getAdvert($id): Advert
    {
        return Advert::findOrFail($id);
    }
}
