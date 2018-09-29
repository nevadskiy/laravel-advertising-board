<?php

namespace App\Http\Controllers\Cabinet\Banners;

use App\Entity\Banner\Banner;
use App\Http\Controllers\Controller;
use App\Http\Requests\Banner\EditRequest;
use App\Http\Requests\Banner\FileRequest;
use App\Services\Banners\BannerService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class BannerController extends Controller
{
    /**
     * @var BannerService
     */
    private $service;

    /**
     * BannerController constructor.
     * @param BannerService $service
     */
    public function __construct(BannerService $service)
    {
        $this->service = $service;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $banners = Banner::forUser(Auth::user())->orderByDesc('id')->paginate(20);

        return view('cabinet.banners.index', compact('banners'));
    }

    /**
     * @param Banner $banner
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Banner $banner)
    {
        $this->checkAccess($banner);
        return view('cabinet.banners.show', compact('banner'));
    }

    /**
     * @param Banner $banner
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function editForm(Banner $banner)
    {
        $this->checkAccess($banner);
        if (!$banner->canBeChanged()) {
            return redirect()->route('cabinet.banners.show', $banner)->with('error', 'Unable to edit.');
        }
        return view('cabinet.banners.edit', compact('banner'));
    }

    /**
     * @param EditRequest $request
     * @param Banner $banner
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit(EditRequest $request, Banner $banner)
    {
        $this->checkAccess($banner);
        try {
            $this->service->editByOwner($banner->id, $request);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('cabinet.banners.show', $banner);
    }

    /**
     * @param Banner $banner
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function fileForm(Banner $banner)
    {
        $this->checkAccess($banner);
        if (!$banner->canBeChanged()) {
            return redirect()->route('cabinet.banners.show', $banner)->with('error', 'Unable to edit.');
        }
        $formats = Banner::formatsList();
        return view('cabinet.banners.file', compact('banner', 'formats'));
    }

    /**
     * @param FileRequest $request
     * @param Banner $banner
     * @return \Illuminate\Http\RedirectResponse
     */
    public function file(FileRequest $request, Banner $banner)
    {
        $this->checkAccess($banner);
        try {
            $this->service->changeFile($banner->id, $request);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('cabinet.banners.show', $banner);
    }

    /**
     * @param Banner $banner
     * @return \Illuminate\Http\RedirectResponse
     */
    public function send(Banner $banner)
    {
        $this->checkAccess($banner);
        try {
            $this->service->sendToModeration($banner->id);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('cabinet.banners.show', $banner);
    }

    /**
     * @param Banner $banner
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel(Banner $banner)
    {
        $this->checkAccess($banner);
        try {
            $this->service->cancelModeration($banner->id);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('cabinet.banners.show', $banner);
    }

    /**
     * @param Banner $banner
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function order(Banner $banner)
    {
        $this->checkAccess($banner);
        try {
            $banner = $this->service->order($banner->id);
            $url = $this->robokassa->generateRedirectUrl($banner->id, $banner->cost, 'banner');
            return redirect($url);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('cabinet.banners.show', $banner);
    }

    /**
     * @param Banner $banner
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Banner $banner)
    {
        $this->checkAccess($banner);
        try {
            $this->service->removeByOwner($banner->id);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('cabinet.banners.index');
    }

    /**
     * @param Banner $banner
     */
    private function checkAccess(Banner $banner): void
    {
        if (!Gate::allows('manage-own-banner', $banner)) {
            abort(403);
        }
    }
}
