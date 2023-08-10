<?php

namespace Modules\V021\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Resources\Respons;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Support\Renderable;
use Modules\V021\Http\Repositories\AddressRepo;

class AddressController extends Controller
{
    private $addressRepo;
    public function __construct(AddressRepo $addressRepo)
    {
        $this->addressRepo = $addressRepo;
    }

    public function index()
    {
        $data = $this->addressRepo->getAddressAll();
        return new Respons(true,'Daftar Alamat',$data);
    }

    public function store(Request $req)
    {
        $valid = $req->only('label','alamat','detail_alamat','pemilik','phone');
        $validator = Validator::make($valid,[
            'label' => 'required',
            'alamat' => 'required',
            'detail_alamat' => 'required',
            'pemilik' => 'required',
            'phone' => 'required',
        ]);

        if ($validator->fails()) return new Respons(false, 'Validation Failed', $validator->errors());

        $valid['user_id'] = Auth::user()->id;
        $create = $this->addressRepo->createAddress($valid);
    }

}
