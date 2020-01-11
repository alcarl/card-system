<?php
namespace App\Http\Controllers\Merchant; use App\Library\Response; use Carbon\Carbon; use Illuminate\Http\Request; use App\Http\Controllers\Controller; class Coupon extends Controller { function get(Request $spa20801) { $sp6c3275 = $this->authQuery($spa20801, \App\Coupon::class)->with(array('category' => function ($sp6c3275) { $sp6c3275->select(array('id', 'name')); }))->with(array('product' => function ($sp6c3275) { $sp6c3275->select(array('id', 'name')); })); $sp50974d = $spa20801->input('search', false); $sp7a8e14 = $spa20801->input('val', false); if ($sp50974d && $sp7a8e14) { if ($sp50974d == 'id') { $sp6c3275->where('id', $sp7a8e14); } else { $sp6c3275->where($sp50974d, 'like', '%' . $sp7a8e14 . '%'); } } $sp664160 = (int) $spa20801->input('category_id'); $sp17d280 = $spa20801->input('product_id', -1); if ($sp664160 > 0) { if ($sp17d280 > 0) { $sp6c3275->where('product_id', $sp17d280); } else { $sp6c3275->where('category_id', $sp664160); } } $sp59ae99 = $spa20801->input('status'); if (strlen($sp59ae99)) { $sp6c3275->whereIn('status', explode(',', $sp59ae99)); } $spf46b4f = $spa20801->input('type'); if (strlen($spf46b4f)) { $sp6c3275->whereIn('type', explode(',', $spf46b4f)); } $sp6c3275->orderByRaw('expire_at DESC,category_id,product_id,type,status'); $sp2c377e = (int) $spa20801->input('current_page', 1); $sp896436 = (int) $spa20801->input('per_page', 20); $spa79e9a = $sp6c3275->paginate($sp896436, array('*'), 'page', $sp2c377e); return Response::success($spa79e9a); } function create(Request $spa20801) { $spb5d933 = $spa20801->post('count', 0); $spf46b4f = (int) $spa20801->post('type', \App\Coupon::TYPE_ONETIME); $sp079be6 = $spa20801->post('expire_at'); $spc5b2d8 = (int) $spa20801->post('discount_val'); $spa7d7a7 = (int) $spa20801->post('discount_type', \App\Coupon::DISCOUNT_TYPE_AMOUNT); $spaccaab = $spa20801->post('remark'); if ($spa7d7a7 === \App\Coupon::DISCOUNT_TYPE_AMOUNT) { if ($spc5b2d8 < 1 || $spc5b2d8 > 1000000000) { return Response::fail('优惠券面额需要在0.01-10000000之间'); } } if ($spa7d7a7 === \App\Coupon::DISCOUNT_TYPE_PERCENT) { if ($spc5b2d8 < 1 || $spc5b2d8 > 100) { return Response::fail('优惠券面额需要在1-100之间'); } } $sp664160 = (int) $spa20801->post('category_id', -1); $sp17d280 = (int) $spa20801->post('product_id', -1); if ($spf46b4f === \App\Coupon::TYPE_REPEAT) { $sp845919 = $spa20801->post('coupon'); if (!$sp845919) { $sp845919 = strtoupper(str_random()); } $spd64f45 = new \App\Coupon(); $spd64f45->user_id = $this->getUserIdOrFail($spa20801); $spd64f45->category_id = $sp664160; $spd64f45->product_id = $sp17d280; $spd64f45->coupon = $sp845919; $spd64f45->type = $spf46b4f; $spd64f45->discount_val = $spc5b2d8; $spd64f45->discount_type = $spa7d7a7; $spd64f45->count_all = (int) $spa20801->post('count_all', 1); if ($spd64f45->count_all < 1 || $spd64f45->count_all > 10000000) { return Response::fail('可用次数不能超过10000000'); } $spd64f45->expire_at = $sp079be6; $spd64f45->saveOrFail(); return Response::success(array($spd64f45->coupon)); } elseif ($spf46b4f === \App\Coupon::TYPE_ONETIME) { if (!$spb5d933) { return Response::forbidden('请输入生成数量'); } if ($spb5d933 > 100) { return Response::forbidden('每次生成不能大于100张'); } $sp57c3d1 = array(); $spfd992f = array(); $sp6fe8b9 = $this->getUserIdOrFail($spa20801); $sp54e637 = Carbon::now(); for ($spec1f96 = 0; $spec1f96 < $spb5d933; $spec1f96++) { $spd64f45 = strtoupper(str_random()); $spfd992f[] = $spd64f45; $sp57c3d1[] = array('user_id' => $sp6fe8b9, 'coupon' => $spd64f45, 'category_id' => $sp664160, 'product_id' => $sp17d280, 'type' => $spf46b4f, 'discount_val' => $spc5b2d8, 'discount_type' => $spa7d7a7, 'status' => \App\Coupon::STATUS_NORMAL, 'remark' => $spaccaab, 'created_at' => $sp54e637, 'expire_at' => $sp079be6); } \App\Coupon::insert($sp57c3d1); return Response::success($spfd992f); } else { return Response::forbidden('unknown type: ' . $spf46b4f); } } function edit(Request $spa20801) { $spbc2f9d = (int) $spa20801->post('id'); $sp845919 = $spa20801->post('coupon'); $sp664160 = (int) $spa20801->post('category_id', -1); $sp17d280 = (int) $spa20801->post('product_id', -1); $sp079be6 = $spa20801->post('expire_at', NULL); $sp59ae99 = (int) $spa20801->post('status', \App\Coupon::STATUS_NORMAL); $spf46b4f = (int) $spa20801->post('type', \App\Coupon::TYPE_ONETIME); $spc5b2d8 = (int) $spa20801->post('discount_val'); $spa7d7a7 = (int) $spa20801->post('discount_type', \App\Coupon::DISCOUNT_TYPE_AMOUNT); if ($spa7d7a7 === \App\Coupon::DISCOUNT_TYPE_AMOUNT) { if ($spc5b2d8 < 1 || $spc5b2d8 > 1000000000) { return Response::fail('优惠券面额需要在0.01-10000000之间'); } } if ($spa7d7a7 === \App\Coupon::DISCOUNT_TYPE_PERCENT) { if ($spc5b2d8 < 1 || $spc5b2d8 > 100) { return Response::fail('优惠券面额需要在1-100之间'); } } $spd64f45 = $this->authQuery($spa20801, \App\Coupon::class)->find($spbc2f9d); if ($spd64f45) { $spd64f45->coupon = $sp845919; $spd64f45->category_id = $sp664160; $spd64f45->product_id = $sp17d280; $spd64f45->status = $sp59ae99; $spd64f45->type = $spf46b4f; $spd64f45->discount_val = $spc5b2d8; $spd64f45->discount_type = $spa7d7a7; if ($spf46b4f === \App\Coupon::TYPE_REPEAT) { $spd64f45->count_all = (int) $spa20801->post('count_all', 1); if ($spd64f45->count_all < 1 || $spd64f45->count_all > 10000000) { return Response::fail('可用次数不能超过10000000'); } } if ($sp079be6) { $spd64f45->expire_at = $sp079be6; } $spd64f45->saveOrFail(); } else { $sp7b1339 = explode('
', $sp845919); for ($spec1f96 = 0; $spec1f96 < count($sp7b1339); $spec1f96++) { $spf9cdf0 = str_replace('', '', trim($sp7b1339[$spec1f96])); $spd64f45 = new \App\Coupon(); $spd64f45->coupon = $spf9cdf0; $spd64f45->category_id = $sp664160; $spd64f45->product_id = $sp17d280; $spd64f45->status = $sp59ae99; $spd64f45->type = $spf46b4f; $spd64f45->discount_val = $spc5b2d8; $spd64f45->discount_type = $spa7d7a7; $sp7b1339[$spec1f96] = $spd64f45; } \App\Product::find($sp17d280)->coupons()->saveMany($sp7b1339); } return Response::success(); } function enable(Request $spa20801) { $this->validate($spa20801, array('ids' => 'required|string', 'enabled' => 'required|integer|between:0,1')); $sp4a6f27 = $spa20801->post('ids'); $sp34b10a = (int) $spa20801->post('enabled'); $this->authQuery($spa20801, \App\Coupon::class)->whereIn('id', explode(',', $sp4a6f27))->update(array('enabled' => $sp34b10a)); return Response::success(); } function delete(Request $spa20801) { $this->validate($spa20801, array('ids' => 'required|string')); $sp4a6f27 = $spa20801->post('ids'); $this->authQuery($spa20801, \App\Coupon::class)->whereIn('id', explode(',', $sp4a6f27))->delete(); return Response::success(); } }