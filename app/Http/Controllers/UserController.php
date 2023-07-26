<?php

namespace App\Http\Controllers;

use App\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Role;

class UserController extends Controller
{

    public function index()
    {
        $title = 'Users';
        $listData = User::with('role')->get();
        $roleMap = User::roleUserName;
        $compact = ['title', 'listData', 'roleMap'];
        return view('user.index', compact($compact));
    }

    public function Login(){
        return view('authv2.login');
    }

    public function LoginProses(Request $request)
    {
        $this->validate($request, [
            'nik' => 'required',
            'password'  => 'required'
        ]);
        $credentials = $request->only('nik', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()->intended('home');
        }
        return redirect()->back()->withInput($request->only('nik', 'password'))->with('error', 'NIK dan Password yang anda masukan salah!');
    }

    public function Logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function AddUser(Request $request)
    {
        DB::beginTransaction();
        try {
            if ($request->aksi === "tambah"){
                // user data
                $saveUser = new User();
                $saveUser->nik          = $request->nik;   
                $saveUser->password     = Hash::make($request->nik);
                $saveUser->created_at   = date('Y-m-d H:i:s');
                // role data
                $saveRole = new Role();
                $saveRole->nik = $request->nik;
                $saveRole->role = $request->role_level;
            }else{
                // user data
                $saveUser = User::where('nik', $request->nik)->first();  
                $saveUser->updated_at   = date('Y-m-d H:i:s');
                // role data
                $saveRole = Role::where('nik', $request->nik)->first();
                $saveRole->role = $request->role_level;
            } 
                // user data
                $saveUser->name         = $request->nama_lengkap;  
                $saveUser->email        = $request->email;
                $saveUser->save();
                // role data
                $saveRole->save();
            DB::commit();
            return redirect()->route('user.index')->with('success', "{$request->aksi} data");
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('user.index')->with('error', "{$th->getMessage()} data");
        }
    }

    public function UniqNik(Request $request)
    {
        try {
            $result = User::where('nik', $request->nik)->count();
            if ($result > 0) {
                return false;
            }else {
                return true;
            }
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function GetNik(Request $request)
    {
        try {
            $result = User::with('role')->where('nik', $request->nik)->first();
            return response()->json($result);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }

   public function HapusUser(Request $request)
   {
        DB::beginTransaction();
        try {
            $role = Role::where('nik', $request->data_uniq)->delete();
            if ($role == true) {
                User::where('nik', $request->data_uniq)->delete();
            }
            DB::commit();
            return redirect()->route('user.index')->with('success', " Hapus Nik : {$request->data_uniq}");
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('user.index')->with('error', " Hapus Nik : {$request->data_uniq} ".$th->getMessage());
        }
   } 

   public function Profile($nik)
   {
        $user = User::where('nik', $nik)->first();
        $title = 'Profile';
        $compact = ['title', 'user'];
        return view('profile.profile', compact($compact));
   }

   public function UploadGambar(Request $request, $nik)
   {
        try {
            $file = $request->file('file');
            $fileOriginalName = $file->getClientOriginalName();
            $user = User::where('nik', $nik)->first();
            if ($fileOriginalName !== $user->images) {
                $extension = $file->getClientOriginalExtension();
                $fileName = $nik.'_'.date('His').'.'.$extension;
                $tujuan_upload = 'img_profile';
                $file->move($tujuan_upload,$fileName);
                $user->images = $fileName;
                $user->save();
            }
            return redirect()->route('profile', ['nik' => $nik])->with('success', 'Berhasil Upload Gambar');
        } catch (\Throwable $th) {
            return redirect()->route('profile', ['nik' => $nik])->with('error', $th->getMessage());
        }
   }

   public function UpdateProfile(Request $request, $nik)
   {
        DB::beginTransaction();
        try {
            // data user
            $user = User::where('nik', $nik)->first();
            $user->name = $request->userName;
            $user->email = $request->email;
            $user->save();

            // data karyawan
            $pegawai = Pegawai::where('nik', $nik)->first();
            if (empty($pegawai)) $pegawai = new Pegawai();
            $pegawai->nik = $nik;
            $pegawai->nama_pegawai = $request->nama;
            $pegawai->jenis_kelamin = $request->jenis_kelamin;
            $pegawai->tempat_lahir = $request->tempat_lahir;
            $pegawai->tanggal_lahir = $request->tanggal_lahir;
            $pegawai->pendidikan_terakhir = $request->pend_terakhir;
            $pegawai->alamat = $request->alamat;
            $pegawai->no_telpon = $request->no_tlpn;
            $pegawai->save();

            DB::commit();
            return redirect()->route('profile', ['nik' => $nik])->with('success', 'Berhasil Ubah Data');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('profile', ['nik' => $nik])->with('error', $th->getMessage());
        }
   }
    
}
