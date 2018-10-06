<?php

namespace App\Http\Controllers\Admin\Adverts;

use App\Entity\Advert\Advert;
use App\Entity\User\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Adverts\AttributesRequest;
use App\Http\Requests\Adverts\EditRequest;
use App\Http\Requests\Adverts\PhotosRequest;
use App\Http\Requests\Adverts\RejectRequest;
use App\Services\Adverts\AdvertService;
use Illuminate\Http\Request;

class AdvertController extends Controller
{
    /**
     * @var AdvertService
     */
    private $service;

    /**
     * AdvertController constructor.
     * @param AdvertService $service
     */
    public function __construct(AdvertService $service)
    {
        $this->service = $service;
        $this->middleware('can:manage-adverts');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Advert::orderByDesc('updated_at');

        if (!empty($value = $request->get('id'))) {
            $query->where('id', $value);
        }

        if (!empty($value = $request->get('title'))) {
            $query->where('title', 'like', '%' . $value . '%');
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

        $adverts = $query->paginate(20);

        $statuses = Advert::statusesList();

        $roles = User::rolesList();

        return view('admin.adverts.adverts.index', compact('adverts', 'statuses', 'roles'));
    }

    /**
     * @param Advert $advert
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editForm(Advert $advert)
    {
        return view('adverts.edit.advert', compact('advert'));
    }

    /**
     * @param EditRequest $request
     * @param Advert $advert
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit(EditRequest $request, Advert $advert)
    {
        try {
            $this->service->edit($advert->id, $request);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('adverts.show', $advert);
    }

    /**
     * @param Advert $advert
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function attributesForm(Advert $advert)
    {
        return view('adverts.edit.attributes', compact('advert'));
    }

    /**
     * @param AttributesRequest $request
     * @param Advert $advert
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function attributes(AttributesRequest $request, Advert $advert)
    {
        try {
            $this->service->editAttributes($advert->id, $request);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('adverts.show', $advert);
    }

    /**
     * @param Advert $advert
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function photosForm(Advert $advert)
    {
        return view('adverts.edit.photos', compact('advert'));
    }

    /**
     * @param PhotosRequest $request
     * @param Advert $advert
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function photos(PhotosRequest $request, Advert $advert)
    {
        try {
            $this->service->addPhotos($advert->id, $request);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('adverts.show', $advert);
    }

    /**
     * @param Advert $advert
     * @return \Illuminate\Http\RedirectResponse
     */
    public function moderate(Advert $advert)
    {
        try {
            $this->service->moderate($advert->id);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('adverts.show', $advert);
    }

    /**
     * @param Advert $advert
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function rejectForm(Advert $advert)
    {
        return view('admin.adverts.adverts.reject', compact('advert'));
    }

    /**
     * @param RejectRequest $request
     * @param Advert $advert
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject(RejectRequest $request, Advert $advert)
    {
        try {
            $this->service->reject($advert->id, $request);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('adverts.show', $advert);
    }

    /**
     * @param Advert $advert
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Advert $advert)
    {
        try {
            $this->service->remove($advert->id);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.adverts.adverts.index');
    }
}
