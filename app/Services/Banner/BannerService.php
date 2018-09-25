<?php

namespace App\Services\Banner;

use App\Entity\Adverts\Category;
use App\Entity\Region;
use App\Entity\User;
use App\Http\Requests\Adverts\CreateRequest;
use App\Http\Requests\Banner\EditRequest;
use App\Http\Requests\Banner\RejectRequest;

class BannerService
{
    public function create(User $user, Category $category, Region $region, CreateRequest $request)
    {
        // TODO
    }

    public function sendToModeration($id)
    {
        // TODO
    }

    public function ediByOwner($id, EditRequest $request)
    {
        // TODO
    }

    public function cancelModeration($id)
    {
    }

    public function order($id)
    {
    }

    public function removeByOwner($id)
    {
    }

    public function editByAdmin($id, EditRequest $request)
    {
    }

    public function moderate($id)
    {
    }

    public function reject($id, RejectRequest $request)
    {
    }

    public function pay($id)
    {
    }

    public function removeByAdmin($id)
    {
    }
}
