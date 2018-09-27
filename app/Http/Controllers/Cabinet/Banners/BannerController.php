<?php

namespace App\Http\Controllers\Cabinet\Banners;

use App\Entity\Advert\Advert;
use App\Entity\Banner;
use App\Http\Controllers\Controller;
use App\Http\Middleware\FilledProfileOnly;
use App\Http\Requests\Banner\EditRequest;
use App\Services\Banner\BannerService;
use Auth;
use DomainException;
use Gate;

class BannerController extends Controller
{
    /**
     * @var BannerService
     */
    private $service;

    public function __construct(BannerService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $banners = Banner::forUser(Auth::user())->orderByDesc('id')->paginate(20);

        return view('cabinet.banners.index', compact('banners'));
    }

    public function show(Banner $banner)
    {
        $this->checkAccess($banner);

        return view('cabinet.banners.show', compact('banner'));
    }

    public function editForm(Banner $banner)
    {
        $this->checkAccess($banner);

        if (!$banner->canBeChanged()) {
            return redirect()->route('cabinet.banners.show', $banner)->with('error', 'Unable to edit.');
        }

        return view('cabinet.banners.edit', compact('banner'));
    }

    public function edit(EditRequest $request, Banner $banner)
    {
        $this->checkAccess($banner);

        try {
            $this->service->ediByOwner($banner->id, $request);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('cabinet.banners.show', $banner);
    }

    public function send(Banner $banner)
    {
        $this->checkAccess($banner);

        try {
            $this->service->sendToModeration($banner->id);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('cabinet.banners.show', $banner);
    }

    public function cancel(Banner $banner)
    {
        $this->checkAccess($banner);

        try {
            $this->service->cancelModeration($banner->id);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('cabinet.banners.show', $banner);
    }

    public function order(Banner $banner)
    {
        $this->checkAccess($banner);

        try {
            $this->service->order($banner->id);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('cabinet.banners.show', $banner);
    }

    public function destroy(Banner $banner)
    {
        $this->checkAccess($banner);

        try {
            $this->service->removeByOwner($banner->id);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('cabinet.banners.show', $banner);
    }

    private function checkAccess(Banner $banner): void
    {
        if (!Gate::allows('manage-own-banner', $banner)) {
            abort(403);
        }
    }
}
