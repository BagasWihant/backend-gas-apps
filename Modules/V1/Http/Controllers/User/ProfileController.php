<?php

namespace Modules\V1\Http\Controllers\User;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Resources\Respons;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Marketplace\UserMarket;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{

    public function getData()
    {
        $user = Auth::user();

        $coll = collect([
            'name' => $user->name,
            'mail' => $user->email,
            'phone' => $user->phone,
            'jk' => $user->jenis_kelamin,
            'tgl' => $user->tgl_lahir,
        ]);

        return new Respons(true, '', $coll);
    }

    public function update(Request $req)
    {
        $user = Auth::user();
        $input = $req->only('name', 'mail', 'phone', 'jk', 'tgl');
        $validator = Validator::make($input, [
            'mail' => 'required|email:filter,spoof,dns|unique:users,email,' . $user->id
        ]);
        if ($validator->fails()) return new Respons(false, 'Validation Failed', $validator->errors());

        try {
            $updating = User::where('id', $user->id)->where('email', $user->email)->first();
            if ($input['mail']) $updating->email = $input['mail'];
            if ($input['name']) $updating->name = $input['name'];
            if ($input['phone']) $updating->phone = $input['phone'];
            if ($input['jk']) $updating->jenis_kelamin = Str::upper($input['jk']);
            if ($input['tgl']) $updating->tgl_lahir = $input['tgl'];
            $updating->save();

            return new Respons(true, 'Update sukses');
        } catch (\Throwable $th) {
            $debug = config('app.debug');
            if($debug) return new Respons(false, 'Update Gagal', $th);
            return new Respons(false, 'Update Gagal');
        }

    }
}
