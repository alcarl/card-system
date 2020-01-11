<?php
use Illuminate\Support\Facades\Schema; use Illuminate\Database\Schema\Blueprint; use Illuminate\Database\Migrations\Migration; class CreateCouponsTable extends Migration { public function up() { Schema::create('coupons', function (Blueprint $sp6e715d) { $sp6e715d->increments('id'); $sp6e715d->integer('user_id')->index(); $sp6e715d->integer('category_id')->default(-1); $sp6e715d->integer('product_id')->default(-1); $sp6e715d->integer('type')->default(\App\Coupon::TYPE_REPEAT); $sp6e715d->integer('status')->default(\App\Coupon::STATUS_NORMAL); $sp6e715d->string('coupon', 100)->index(); $sp6e715d->integer('discount_type'); $sp6e715d->integer('discount_val'); $sp6e715d->integer('count_used')->default(0); $sp6e715d->integer('count_all')->default(1); $sp6e715d->string('remark')->nullable(); $sp6e715d->dateTime('expire_at')->nullable(); $sp6e715d->timestamps(); }); } public function down() { Schema::dropIfExists('coupons'); } }