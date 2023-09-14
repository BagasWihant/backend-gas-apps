<?php

namespace Modules\V021\Http\Controllers\Explore;

use App\Http\Resources\Respons;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Support\Renderable;
use Modules\V021\Http\Repositories\ExplorePageRepo;

class ExploreController extends Controller
{
    private $explore;
    public function __construct(ExplorePageRepo $explore)
    {
        $this->explore = $explore;
    }
    public function temukanProduk(Request $req,$name)
    {
        $key = $req->only(['sort', 'kat', 'kondisi']);

        $binding = [];
        $filter = '';

        $nameSearch = "$name*";
        $binding[] = $nameSearch;

        if (isset($key['kat'])) {
            $filter .= "+$key[kat] ";
        }
        if (isset($key['kondisi'])) {
            $filter .= "+$key[kondisi] ";
        }

        $data = ['binding'=>$binding,'filter'=>$filter];
        $res = $this->explore->temukanProduk($data);
        return $res;
    }

    public function detailProduk($id){
        $res = $this->explore->detail($id);

        return new Respons(true,'',$res);
    }

}
