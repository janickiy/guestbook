<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\Http\Controllers\Controller;
use App\Models\Users;
use App\Models\Messages;
use App\Services\MessagesService;
use App\Services\UsersService;

class HomeController extends Controller
{
	public function index(Request $request)
	{
		$UsersService = new UsersService();
		$MessagesService = new MessagesService();

		if ($request->auth == 'vk') {
			$id = $UsersService->VkAuther($request->code);

			if ($id) {
				Session::put('id', $id);
			}
		} elseif ($request->auth == 'fb') {
			$id = $UsersService->FbAuther($request->code);

			if ($id) {
				Session::put('id', $id);
			}
		}

		if ($request->msg && Session::has('id')) {
			$MessagesService->addMessage(Session::get('id'), $request->msg);
		}

		$data = [
			'title' => 'Гостевая книга',
			'pagetitle' => 'Гостевая книга',
			'messages' => Messages::orderBy('message.created_at', 'desc')->leftJoin('users', 'users.id', '=', 'message.user_id')->paginate(5),
			'count' => Messages::count(),
			'user' => Session::has('id') ? Users::where('id', Session::get('id'))->first() : '',
		];
	   
		return view('main', $data);
	}
}
