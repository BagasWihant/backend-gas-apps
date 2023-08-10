<?php

namespace Modules\V021\Http\Repositories;

use Illuminate\Support\Facades\Auth;
use App\Models\Marketplace\UserMarket;
use App\Models\User\Address;

class AddressRepo {
    public function getAddressAll(){

        $user = Auth::user();
        $um = UserMarket::select('user_id_market')->where('user_id_main',$user->id)->first();
        $address = Address::select('label','alamat','detail_alamat','pemilik','phone','status')
                    ->where('user_id_market',$um->user_id_market)->get();
        return $address;
    }

    public function createAddress($data){
        $userMarket = UserMarket::select('user_id_market')->where('user_id_main',$data['user_id'])->first();

        dd($data);
        // $address = Address::create([]);
    }
}
