<?php
//
//use App\Code\Payments\GatewayFactory;
//use App\Code\Payments\PaymentGatewayInterface;
//use App\Code\Payments\PaymentGatewayProviders;
//use App\Models\Payment;
//use App\Models\PaymentItem;
//use App\Models\PaymentMethod;
//use App\Models\PaymentStatusOptions;
//use App\Models\PayoutStatusOptions;
//use App\Models\User;
//
//test( 'can collect and refund payment', function () {
//	$user = User::find( 1 );
//	expect( $user )->toBeInstanceOf( User::class );
//
//	$payment_method = $user->payment_methods()
//	                       ->where( 'type', '=', PaymentGatewayProviders::OpenPix )
//	                       ->where( 'is_complete', '=', true )
//	                       ->where( 'is_active', '=', true )
//	                       ->first();
//	expect( $payment_method )->toBeInstanceOf( PaymentMethod::class );
//
//	$gateway = GatewayFactory::create_from_payment_method( $payment_method );
//	expect( $gateway )->toBeInstanceOf( PaymentGatewayInterface::class );
//
//	$payment                             = new Payment();
//	$payment->user_id                    = $user->id;
//	$payment->type                       = $gateway->type();
//	$payment->payment_method_id          = $payment_method->id;
//	$payment->status                     = PaymentStatusOptions::Pending;
//	$payment->payment_method_description = $payment_method->meta->description ?? 'unknown';
//	$payment->save();
//
//	$item                  = new PaymentItem();
//	$item->payment_id      = $payment->id;
//	$item->currency_code   = 'BRL';
//	$item->amount          = 5.99;
//	$item->tax_amount      = 0.24;
//	$item->description     = 'Test Payment';
//	$item->payout_amount   = null;
//	$item->organization_id = null;
//	$item->payout_status   = PayoutStatusOptions::Complete;
//	$item->owner_type      = User::class;
//	$item->owner_id        = $user->id;
//	$item->save();
//
//	$collect_result = $gateway->collect_payment( $payment );
//
//	$payment->delete();
//
//	expect( $collect_result->is_error )->toBeFalse();
//
//	$total_cents = $payment->total_amount_cents();
//
//	$data = $collect_result->data;
//	expect( $data )->toHaveProperty( 'charge' )
//	               ->and( $data->charge->value )->toBe( $total_cents )
//	               ->and( $data->charge->comment )->toBe( $payment->description )
//	               ->and( $data->charge->correlationID )->toBe( $payment->hash )
//	               ->and( $data->charge->paymentLinkUrl )->not()->toBeNull();
//} );
