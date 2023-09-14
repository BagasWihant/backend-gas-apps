<?php

namespace Modules\V021\Http\Controllers\Marketplace;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\V021\Http\Repositories\KeranjangRepo;

class KeranjangController extends Controller
{
    private $repo;
    public function __construct(KeranjangRepo $repo)
    {
        $this->repo = $repo;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $idMarket = $user->idMarket->user_id_market;

        return $this->repo->listProduk($idMarket);
    }

    public function show($keranjang)
    {
        dd($keranjang);
    }

    public function store(Request $request)
    {
        return $this->repo->store($request);

    }


    public function update(Request $request, $id)
    {
        //
    }



    public function destroy($id)
    {
        //
    }
}
