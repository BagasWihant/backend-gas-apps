<?php

namespace Modules\V021\Http\Controllers\Marketplace;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\V021\Http\Repositories\WishlistRepo;

class WishlistController extends Controller
{
    private $repo;
    public function __construct(WishlistRepo $repo)
    {
        $this->repo = $repo;
    }

    public function index(Request $req)
    {

        $user = $req->user();
        $idMarket = $user->idMarket->select('user_id_market')->first();

        return $this->repo->listProduk($idMarket->user_id_market);
    }

    public function store(Request $req)
    {

        $allowed = $req->only(
            'produk_id',
            'table',
        );

        $validator = Validator::make($allowed, [
            'produk_id' => 'required',
            'table' => 'required',
        ]);

        if ($validator->fails()) return response()->badRequest('Validasi Gagal', $validator->errors());

        $allowed['user'] = $req->user();
        return $this->repo->store($allowed);
    }

    public function destroy(Request $req,$id)
    {
        $user_id = $req->user()->idMarket->user_id_market;
        return  $this->repo->deleteItem($id,$user_id);
    }
}
