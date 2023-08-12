<?php

namespace Modules\V021\Http\Repositories;

use Exception;
use App\Models\User\Address;
use App\Http\Resources\Respons;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Marketplace\UserMarket;

class AddressRepo
{

    public function getAddressAll($user)
    {
        $um = UserMarket::select('user_id_market')->where('user_id_main', $user->id)->first();
        $address = Address::select('id', 'label', 'alamat', 'detail_alamat', 'pemilik', 'phone', 'status')
            ->where('user_id_market', $um->user_id_market)->get();
        return $address;
    }

    public function createAddress($data)
    {
        try {
            DB::beginTransaction();
            $userMarket = UserMarket::select('user_id_market')->where('user_id_main', $data['user_id'])->first();

            $address = Address::create([
                'user_id_market' => $userMarket->user_id_market,
                'label' => $data['label'],
                'alamat' => $data['alamat'],
                'detail_alamat' => $data['detail_alamat'],
                'pemilik' => $data['pemilik'],
                'phone' => $data['phone'],
                'lon' => isset($data['lon']) ? $data['lon'] : '',
                'lat' => isset($data['lat']) ? $data['lat'] : '',
            ]);
            DB::commit();
            $collect = collect($address);
            $collect->forget(['user_id_market']);
            return $collect;
        } catch (\Throwable $th) {
            DB::rollBack();
            return false;
        }
    }

    public function getAddressDetail($id,$user)
    {
        $addr = Address::select('id', 'label', 'alamat', 'detail_alamat', 'pemilik', 'phone', 'status')
        ->where('id', $id)->where('user_id_market',$user->idMarket->user_id_market)->first();
        if(!$addr) return 'Bukan alamat milik anda';
        return collect($addr);
    }

    public function updateAddress($data,$user)
    {
        try {
            DB::beginTransaction();
            $addr = Address::find($data['id']);
            if ($user->idMarket->user_id_market !== $addr->user_id_market) throw new \Exception('Anda bukan pemilik alamat ini');

            $addr->label = $data['label'];
            $addr->alamat = $data['alamat'];
            $addr->detail_alamat = $data['detail_alamat'];
            $addr->pemilik = $data['pemilik'];
            $addr->phone = $data['phone'];
            $addr->save();

            DB::commit();
            $collect = collect($addr);
            $collect->forget(['user_id_market']);
            return [true,$collect];
        } catch (\Throwable $th) {
            DB::rollBack();
            return [false,$th->getMessage()];
        }
    }

    public function setPrimaryAddress($data,$user)
    {
        try {
            DB::beginTransaction();
            Address::where('user_id_market',$user->idMarket->user_id_market)->update(['status'=>0]);
            $adr = Address::where('id',$data['id'])->where('user_id_market',$user->idMarket->user_id_market)->first();
            if(!$adr) throw new \Throwable("Bukan Alamat Anda", 1);
            $adr->status = 1;
            $adr->save();
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            return false;
        }
    }
}
