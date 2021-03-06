<?php
namespace App\Http\Controllers;
use App\User;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

use App\Http\Requests\UserRequest;

class UserController extends Controller
{
    /**
     * User list
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = User::query();
        $request_post = [];
        // Check isset searchUser
        if(isset($request['searchUser']))
        {
            //select user
            $query->Where("user", "LIKE", "%{$request['searchUser']}%");
            $request_post['searchUser'] = $request['searchUser'];
        }
        // Check isset searchUsername
        if(isset($request['searchUsername'])) {
            //select username
            $query->Where("username", "LIKE", "%{$request['searchUsername']}%");
            $request_post['searchUsername'] = $request['searchUsername'];
        }
        // Check isset searchEmail
        if(isset($request['searchEmail'])) {
            $query->Where("email", "LIKE", "%{$request['searchEmail']}%");
            $request_post['searchEmail'] = $request['searchEmail'];
        }
        // Check isset searchAddress
        if($request['searchAddress']){
            //select address
            $query->where("address", "LIKE", "%{$request['searchAddress']}%");
            $request_post['searchAddress'] = $request['searchAddress'];
        }
        $users = $query->orderBy('id','desc')->paginate(15);
//        foreach($request_post as $key => $val) {
//            echo $val.'<br>';
//        }

        //normal array
        //['test', 'test2']
        //hash array - key and value paired array
//        $append = [
//            'key1' => 'value1'
//        ];
//
//        $request_post = [
//            'searchUser' => $request['searchUser'],
//            'searchUsername' => $request['searchUsername'],
//
//        ];
//array_values()
        //array_keys()
        $users->appends($request_post);
        return view('user-list-view', ['users' => $users,'request_post' => $request_post]);
    }
    /** view insert page
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('user-insert-view');
    }
    /**
     * store data user
     * @param UserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    // public function store(UserRequest $request)
    // {
    //     DB::transaction(function () use ($request)
    //     {
    //         $user = new User;
    //         $user->user = $request->user;
    //         $user->username = $request->username;
    //         $user->email = $request->email;
    //         $user->address = $request->address;
    //         $user->save();
    //     });
    //     return response()->json(['success' => 'User Created']);
    // }
    public function insertandupdate(UserRequest $request)
    {  
        if(isset($request->id))
        {
            DB::transaction(function () use ($request)
            {
                $user = User::find($request->id);
                $user->id = $request->id;
                $user->user = $request->user;
                $user->username = $request->username;
                $user->email = $request->email;
                $user->address = $request->address;
                $user->update();
            });
            return response()->json(['success' => 'User Updated']);
            
        }
        else
        {
            DB::transaction(function () use ($request)
            {
                $user = new User;
                $user->user = $request->user;
                $user->username = $request->username;
                $user->email = $request->email;
                $user->address = $request->address;
                $user->save();
            });
            return response()->json(['success' => 'User Created']);
           
        }
}
    
    /**
     * View edit page with id
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    // public function edit($id)
    // {
    //     return view('user-edit-view', ['user' => User::findOrFail($id)]);
    // }
    /**
     * update data user
     * @param UserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    // public function update(UserRequest $request)
    // {
    //     DB::transaction(function () use ($request)
    //     {
    //         $user = User::find($request->id);
    //         $user->id = $request->id;
    //         $user->user = $request->user;
    //         $user->username = $request->username;
    //         $user->email = $request->email;
    //         $user->address = $request->address;
    //         $user->update();
    //     });
    //     return response()->json(['success' => 'User Updated']);
    // }
    /**
     * delete users
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        DB::transaction(function () use ($request)
        {
            $user = User::find($request->id);
            $user->delete();
        });
        return response()->json(['success' => 'Deleted']);
    }
}


