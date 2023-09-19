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
        return $res;
    }

    public function storeToUser(Request $req)
    {
        $res = $this->gantiAkunRepo->storeTouser($req->user());
        return $res;
    }
}
