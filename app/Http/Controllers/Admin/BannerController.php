<?php

namespace App\Http\Controllers\Admin;

use App\Entity\Banner;
use App\Entity\Region;
use App\Entity\User;
use App\Http\Requests\Banner\EditRequest;
use App\Http\Requests\Banner\RejectRequest;
use App\Services\Banner\BannerService;
use DomainException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
        $this->middleware('can:manage-banners');
    }

    public function index(Request $request)
    {
        $query = Banner::orderByDesc('updated_at');

        if (!empty($value = $request->get('id'))) {
            $query->where('id', $value);
        }

        if (!empty($value = $request->get('user'))) {
            $query->where('user_id', $value);
        }

        if (!empty($value = $request->get('region'))) {
            $query->where('region_id', $value);
        }

        if (!empty($value = $request->get('category'))) {
            $query->where('category_id', $value);
        }

        if (!empty($value = $request->get('status'))) {
            $query->where('status', $value);
        }

        $banners = $query->paginate(20);

        $statuses = Banner::statusesList();

        $roles = User::rolesList();

        return view('admin.banners.index', compact('banners', 'statuses', 'roles'));
    }

    public function show(Banner $banner)
    {
        return view('admin.banners.show', compact('banner'));
    }

    public function editForm(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function edit(EditRequest $request, Banner $banner)
    {
        try {
            $this->service->editByAdmin($banner->id, $request);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.banners.edit', $banner);
    }

    public function moderate(Banner $banner)
    {
        try {
            $this->service->moderate($banner->id);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.banners.edit', $banner);
    }

    public function rejectForm(Banner $banner)
    {
        return view('admin.banners.reject', compact('banner'));
    }

    public function reject(RejectRequest $request, Banner $banner)
    {
        try {
            $this->service->reject($banner->id, $request);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.banners.show', $banner);
    }

    public function pay(Banner $banner)
    {
        try {
            $this->service->pay($banner->id);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.banners.show', $banner);
    }

    public function destroy(Banner $banner)
    {
        try {
            $this->service->removeByAdmin($banner->id);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.banners.show', $banner);
    }
}
