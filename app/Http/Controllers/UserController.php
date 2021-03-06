<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use App\User;
use Request;

class UserController extends BaseController
{

  public function get_json($username) {
    return $this->get($username, 'json');
  }

  public function get($username, $format=false) {

    $user = User::where('username', $username)->first();

    if(!$user) {
      return response()->json([
        'error' => 'not_found'
      ], 404);
    }

    // Switch on Accept header
    if((!$format && request()->wantsJson()) || $format == 'json') {
      $profile = $user->toActivityStreamsObject();

      if($user->external_domain) {
        // Add the Webfinger bits to this response
        $profile['---webfinger---'] = '---webfinger---';
        $profile['subject'] = 'acct:' . $user->username . '@' . $user->external_domain;
        $profile['links'] = [
          [
            'rel' => 'self',
            'type' => 'application/activity+json',
            'href' => 'https://' . $user->external_domain . '/.well-known/user.json',
          ]
        ];
      }

      return response()->json($profile)->header('Content-type', 'application/activity+json');
    } else {
      if($user->external_domain) {
        return redirect('https://' . $user->external_domain . '/');
      } else {
        return $this->get($username, 'json');
      }
    }

  }

}
