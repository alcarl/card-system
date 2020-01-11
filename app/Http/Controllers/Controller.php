<?php
namespace App\Http\Controllers; use App\System; use App\Library\Geetest; use Illuminate\Foundation\Bus\DispatchesJobs; use Illuminate\Http\Response; use Illuminate\Routing\Controller as BaseController; use Illuminate\Foundation\Validation\ValidatesRequests; use Illuminate\Foundation\Auth\Access\AuthorizesRequests; use Illuminate\Http\Request; use Illuminate\Support\Facades\Auth; class Controller extends BaseController { use AuthorizesRequests, DispatchesJobs, ValidatesRequests; public function getCaptcha() { $sp77f565 = System::_get('vcode_driver'); if ($sp77f565 === 'code') { return response(array()); } elseif ($sp77f565 === 'geetest') { return response(Geetest\API::get()); } elseif ($sp77f565 === 'recaptcha') { } return response(array(), Response::HTTP_NOT_IMPLEMENTED); } function validateCaptcha(Request $spa20801) { $sp77f565 = System::_get('vcode_driver'); if ($sp77f565 === 'code') { $this->validate($spa20801, array('captcha.key' => 'required|string', 'captcha.code' => 'required|captcha_api:' . $spa20801->input('captcha.key'))); } elseif ($sp77f565 === 'geetest') { $this->validate($spa20801, array('captcha.a' => 'required|string', 'captcha.b' => 'required|string', 'captcha.c' => 'required|string', 'captcha.d' => 'required|string')); if (!Geetest\API::verify($spa20801->input('captcha.a'), $spa20801->input('captcha.b'), $spa20801->input('captcha.c'), $spa20801->input('captcha.d'))) { throw \Illuminate\Validation\ValidationException::withMessages(array('captcha' => array(trans('validation.captcha')))); } } elseif ($sp77f565 === 'recaptcha') { $this->validate($spa20801, array('captcha.t' => 'required|string')); } } function authQuery(Request $spa20801, $spca39cb, $speb0e20 = 'user_id', $sp75e68b = 'user_id') { return $spca39cb::where($speb0e20, \Auth::id()); } protected function getUserId(Request $spa20801, $sp75e68b = 'user_id') { return \Auth::id(); } protected function getUserIdOrFail(Request $spa20801, $sp75e68b = 'user_id') { $sp6fe8b9 = self::getUserId($spa20801, $sp75e68b); if ($sp6fe8b9) { return $sp6fe8b9; } else { throw new \Exception('参数缺少 ' . $sp75e68b); } } protected function getUser(Request $spa20801) { return \Auth::getUser(); } protected function checkIsInMaintain() { if (System::_getInt('maintain') === 1) { $spa6c469 = System::_get('maintain_info'); echo view('message', array('title' => '维护中', 'message' => $spa6c469)); die; } } protected function msg($sp997a5c, $spdcbec0 = null, $spb2d24a = null) { return view('message', array('message' => $sp997a5c, 'title' => $spdcbec0, 'exception' => $spb2d24a)); } }