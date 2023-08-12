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

    public function index(Request $request)
    {
        $user = $request->user();
        $data = $this->addressRepo->getAddressAll($user);
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
        if(!$create) return new Respons(false,'Gagal menambahkan alamat',$create);

        return new Respons(true,'Berhasil menambahkan alamat',$create);
    }

    public function show($id,Request $req){
        $user = $req->user();
        $data = $this->addressRepo->getAddressDetail($id,$user);
        return new Respons(true,'Daftar Alamat',$data);
    }

    public function update($id,Request $req){
        $data = $req->only('label','alamat','detail_alamat','pemilik','phone','id','aksi');
        $user = $req->user();

        if (isset($data['aksi']) && $data['aksi'] === "SET_PRIMARY") {
            $set = $this->addressRepo->setPrimaryAddress($data,$user);
            if(!$set) return new Respons(false,'Gagal menjadikan alamat utama',$set);
            return new Respons(true,'Berhasil menjadikan alamat utama');
        }

        $validator = Validator::make($data,[
            'label' => 'required',
            'alamat' => 'required',
            'detail_alamat' => 'required',
            'pemilik' => 'required',
            'phone' => 'required',
        ]);
        if ($validator->fails()) return new Respons(false, 'Validation Failed', $validator->errors());

        $update = $this->addressRepo->updateAddress($data,$user);

        if(!$update[0])return new Respons(false,'Gagal mengubah alamat',$update[1]);

        return new Respons(true,'Berhasil mengubah alamat',$update[1]);
    }

}
