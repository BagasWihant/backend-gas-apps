<?php

namespace Modules\V021\Http\Controllers\Utility;

use App\Http\Resources\Respons;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\V021\Http\Repositories\GantiAkunRepo;

class GantiAkunController extends Controller
{
    private $gantiAkunRepo;
    public function __construct(GantiAkunRepo $gantiAkunRepo)
    {
        $this->gantiAkunRepo = $gantiAkunRepo;
    }
    public function userToStore(Request $req)
    {
        $res = $this->gantiAkunRepo->userToStore($req->user());
        return new Respons(true,$res[1]);
    }

    public function storeToUser(Request $req)
    {
        $res = $this->gantiAkunRepo->storeTouser($req->user());
        if(!$res)  return new Respons(false,'Gagal berganti akun');
        return new Respons(true,'Sukses berganti akun');
    }
}
