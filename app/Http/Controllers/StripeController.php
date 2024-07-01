<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Carbon;
use Illuminate\Contracts\Encryption\DecryptException;

class StripeController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $ix = cache()->rememberForever('settings', function () {
            return \App\Models\Info::findOrFail(1);
          });
         try {
        $sc = Crypt::decryptString($ix->spk);$es = Crypt::decryptString($ix->whk);
        } catch (DecryptException $e) {
            report($e);
            return response()->json(['status' => 'key encryption error'], 400);
        }

        $stripe = new \Stripe\StripeClient($sc);
        $endpoint_secret = $es;

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
              $payload, $sig_header, $endpoint_secret
            );
          } catch(\UnexpectedValueException $e) {
            // Invalid payload
            report($e);
            return response()->json(['status' => 'UnexpectedValueException'], 400);
            exit();
          } catch(\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return response()->json(['status' => 'SignatureVerificationException'], 400);
            report($e);
            exit();
          }
       $this->storeEvent($event);

        return response()->json(['status' => 'success'], 200);
    }

    protected function storeEvent($event)
    {
      $ix = cache()->rememberForever('settings', function () {
        return \App\Models\Info::findOrFail(1);
      });
      if($event->type=="checkout.session.completed" && $event->data->object->payment_status="paid"){
        $ty=3;$url=null;

        if($event->data->object->payment_link==$ix->iac1_id ||
        $event->data->object->payment_link==$ix->iac2_id||
        $event->data->object->payment_link==$ix->iac3_id) $ty=1;
        if($event->data->object->payment_link==$ix->eca_id) $ty=2;

        $ul=\App\Models\Chx::where('pli',$event->data->object->payment_intent)->get();
        if($ul->count()>0){
            $ob=$ul->first();
            $url=$ob->rli;
            $ob->delete();
        }

        $us=\App\Models\User::where('email',$event->data->object->customer_details->email)->get();
          $or=new \App\Models\Order;
          $or->pbi=$event->data->object->payment_link;
          $or->sid=$event->data->object->id;
          $or->amount=$event->data->object->amount_total/100;
          $or->cus=$event->data->object->payment_intent;
          $or->qte=1;
          $or->type=$ty;
          $or->ili=$url;
          $or->user=$us->count()>0?$us->first()->id:null;
          $or->save();
          if($us->count()>0 && $ty==1){
            $qt=0;
            switch ($event->data->object->payment_link) {
                case $ix->iac1_id:
                    $qt=$ix->iac1_qt;
                    break;
                case $ix->iac2_id:
                        $qt=$ix->iac2_qt;
                        break;
                case $ix->iac3_id:
                            $qt=$ix->iac3_qt;
                            break;
                default: break;
            }
            \App\Models\User::where('id', $us->first()->id)->increment('ix2', $qt);
          }
      }
      if($event->type=="charge.succeeded" && $event->data->object->paid=="true"){
        $ul=\App\Models\Order::where('cus',$event->data->object->payment_intent)->get();
        if($ul->count()>0){
            $ob=$ul->first();
            $ob->ili=$event->data->object->receipt_url;
            $ob->save();
        }else {
          \App\Models\Chx::create([
            "pli"=>$event->data->object->payment_intent,
            "rli"=>$event->data->object->receipt_url,
            "sid"=>$event->data->object->id,
            "i1"=>$event->data->object->receipt_email,
            "i2"=>$event->data->object->amount,
          ]);
        }
      }
      if($event->type=="invoice.paid" && $event->data->object->paid){
        $us=\App\Models\User::where('email',$event->data->object->customer_email)->get();
          $or=new \App\Models\Order;
          $or->pbi=$event->data->object->lines->data[0]->plan->product;
          $or->sid=$event->data->object->id;
          $or->amount=$event->data->object->amount_paid/100;
          $or->cus=$event->data->object->payment_intent;
          $or->qte=1;
          $or->type=0;
          $or->ili=$event->data->object->hosted_invoice_url;
          $or->exp=Carbon::createFromTimestampUTC($event->data->object->lines->data[1]->period->end);
          $or->user=$us->count()>0?$us->first()->id:null;
          $or->save();
      }
    }
    public function handle2()
    {
       // $this->storeEvent($this->getDt2());
        return response()->json(['status' => 'success'], 200);
    }

    public function getDt() : string {
      return '{
          "id": "evt_1PSndZGO0bcnzZi7dtwu735l",
          "object": "event",
          "api_version": "2024-04-10",
          "created": 1718661772,
          "data": {
              "object": {
                  "id": "in_1PSndWGO0bcnzZi7UBMx9ilP",
                  "object": "invoice",
                  "account_country": "CA",
                  "account_name": "ExamBoot",
                  "account_tax_ids": null,
                  "amount_due": 496,
                  "amount_paid": 496,
                  "amount_remaining": 0,
                  "amount_shipping": 0,
                  "application": null,
                  "application_fee_amount": null,
                  "attempt_count": 1,
                  "attempted": true,
                  "auto_advance": false,
                  "automatic_tax": {
                      "enabled": true,
                      "liability": {
                          "type": "self"
                      },
                      "status": "complete"
                  },
                  "billing_reason": "subscription_update",
                  "charge": "ch_3PSndXGO0bcnzZi71CBrsygA",
                  "collection_method": "charge_automatically",
                  "created": 1718661770,
                  "currency": "usd",
                  "custom_fields": null,
                  "customer": "cus_QJLVnbXhSkTb1J",
                  "customer_address": {
                      "city": null,
                      "country": "CA",
                      "line1": null,
                      "line2": null,
                      "postal_code": "G1T 0H9",
                      "state": null
                  },
                  "customer_email": "cpt1mbh@yahoo.fr",
                  "customer_name": "Mike Payer",
                  "customer_phone": null,
                  "customer_shipping": null,
                  "customer_tax_exempt": "none",
                  "customer_tax_ids": [],
                  "default_payment_method": null,
                  "default_source": null,
                  "default_tax_rates": [],
                  "description": null,
                  "discount": null,
                  "discounts": [],
                  "due_date": null,
                  "effective_at": 1718661770,
                  "ending_balance": 0,
                  "footer": null,
                  "from_invoice": null,
                  "hosted_invoice_url": "https:\/\/invoice.stripe.com\/i\/acct_1Oo7zYGO0bcnzZi7\/test_YWNjdF8xT283ellHTzBiY256Wmk3LF9RSlFVY1VqdVRFZTY2VVJuOXJtc0dQbGpKd0k4b1ozLDEwOTIwMjU3Mw0200cOn7hYOH?s=ap",
                  "invoice_pdf": "https:\/\/pay.stripe.com\/invoice\/acct_1Oo7zYGO0bcnzZi7\/test_YWNjdF8xT283ellHTzBiY256Wmk3LF9RSlFVY1VqdVRFZTY2VVJuOXJtc0dQbGpKd0k4b1ozLDEwOTIwMjU3Mw0200cOn7hYOH\/pdf?s=ap",
                  "issuer": {
                      "type": "self"
                  },
                  "last_finalization_error": null,
                  "latest_revision": null,
                  "lines": {
                      "object": "list",
                      "data": [
                          {
                              "id": "il_1PSndWGO0bcnzZi7cNh2G29S",
                              "object": "line_item",
                              "amount": -1986,
                              "amount_excluding_tax": -1986,
                              "currency": "usd",
                              "description": "Unused time on Basic after 17 Jun 2024",
                              "discount_amounts": [],
                              "discountable": false,
                              "discounts": [],
                              "invoice": "in_1PSndWGO0bcnzZi7UBMx9ilP",
                              "invoice_item": "ii_1PSndWGO0bcnzZi71c1L9cBD",
                              "livemode": false,
                              "metadata": [],
                              "period": {
                                  "end": 1721235234,
                                  "start": 1718661676
                              },
                              "plan": {
                                  "id": "price_1Oo99VGO0bcnzZi7oSWIsArI",
                                  "object": "plan",
                                  "active": true,
                                  "aggregate_usage": null,
                                  "amount": 2000,
                                  "amount_decimal": "2000",
                                  "billing_scheme": "per_unit",
                                  "created": 1708973029,
                                  "currency": "usd",
                                  "interval": "month",
                                  "interval_count": 1,
                                  "livemode": false,
                                  "metadata": [],
                                  "meter": null,
                                  "nickname": null,
                                  "product": "prod_PdPzT69nAc938p",
                                  "tiers_mode": null,
                                  "transform_usage": null,
                                  "trial_period_days": null,
                                  "usage_type": "licensed"
                              },
                              "price": {
                                  "id": "price_1Oo99VGO0bcnzZi7oSWIsArI",
                                  "object": "price",
                                  "active": true,
                                  "billing_scheme": "per_unit",
                                  "created": 1708973029,
                                  "currency": "usd",
                                  "custom_unit_amount": null,
                                  "livemode": false,
                                  "lookup_key": null,
                                  "metadata": [],
                                  "nickname": null,
                                  "product": "prod_PdPzT69nAc938p",
                                  "recurring": {
                                      "aggregate_usage": null,
                                      "interval": "month",
                                      "interval_count": 1,
                                      "meter": null,
                                      "trial_period_days": null,
                                      "usage_type": "licensed"
                                  },
                                  "tax_behavior": "unspecified",
                                  "tiers_mode": null,
                                  "transform_quantity": null,
                                  "type": "recurring",
                                  "unit_amount": 2000,
                                  "unit_amount_decimal": "2000"
                              },
                              "proration": true,
                              "proration_details": {
                                  "credited_items": {
                                      "invoice": "in_1PSioYGO0bcnzZi7bxCqTWeI",
                                      "invoice_line_items": [
                                          "il_1PSioYGO0bcnzZi7RfvJPv9m"
                                      ]
                                  }
                              },
                              "quantity": 1,
                              "subscription": "sub_1PSioYGO0bcnzZi7Fuz2U5h7",
                              "subscription_item": "si_QJLVTctUNCPfV2",
                              "tax_amounts": [
                                  {
                                      "amount": 0,
                                      "inclusive": false,
                                      "tax_rate": "txr_1OpMhJGO0bcnzZi772TcyRH6",
                                      "taxability_reason": "not_collecting",
                                      "taxable_amount": 0
                                  },
                                  {
                                      "amount": 0,
                                      "inclusive": false,
                                      "tax_rate": "txr_1OpMhJGO0bcnzZi7HrOmMUDs",
                                      "taxability_reason": "not_collecting",
                                      "taxable_amount": 0
                                  }
                              ],
                              "tax_rates": [],
                              "type": "invoiceitem",
                              "unit_amount_excluding_tax": "-1986"
                          },
                          {
                              "id": "il_1PSndXGO0bcnzZi7exaSMIiO",
                              "object": "line_item",
                              "amount": 2482,
                              "amount_excluding_tax": 2482,
                              "currency": "usd",
                              "description": "Remaining time on Standard after 17 Jun 2024",
                              "discount_amounts": [],
                              "discountable": false,
                              "discounts": [],
                              "invoice": "in_1PSndWGO0bcnzZi7UBMx9ilP",
                              "invoice_item": "ii_1PSndWGO0bcnzZi7H0hNsgDq",
                              "livemode": false,
                              "metadata": [],
                              "period": {
                                  "end": 1721235234,
                                  "start": 1718661676
                              },
                              "plan": {
                                  "id": "price_1OoA83GO0bcnzZi7RYSdrARw",
                                  "object": "plan",
                                  "active": true,
                                  "aggregate_usage": null,
                                  "amount": 2500,
                                  "amount_decimal": "2500",
                                  "billing_scheme": "per_unit",
                                  "created": 1708976783,
                                  "currency": "usd",
                                  "interval": "month",
                                  "interval_count": 1,
                                  "livemode": false,
                                  "metadata": [],
                                  "meter": null,
                                  "nickname": null,
                                  "product": "prod_PdR0ZWsuXMHsrL",
                                  "tiers_mode": null,
                                  "transform_usage": null,
                                  "trial_period_days": null,
                                  "usage_type": "licensed"
                              },
                              "price": {
                                  "id": "price_1OoA83GO0bcnzZi7RYSdrARw",
                                  "object": "price",
                                  "active": true,
                                  "billing_scheme": "per_unit",
                                  "created": 1708976783,
                                  "currency": "usd",
                                  "custom_unit_amount": null,
                                  "livemode": false,
                                  "lookup_key": null,
                                  "metadata": [],
                                  "nickname": null,
                                  "product": "prod_PdR0ZWsuXMHsrL",
                                  "recurring": {
                                      "aggregate_usage": null,
                                      "interval": "month",
                                      "interval_count": 1,
                                      "meter": null,
                                      "trial_period_days": null,
                                      "usage_type": "licensed"
                                  },
                                  "tax_behavior": "unspecified",
                                  "tiers_mode": null,
                                  "transform_quantity": null,
                                  "type": "recurring",
                                  "unit_amount": 2500,
                                  "unit_amount_decimal": "2500"
                              },
                              "proration": true,
                              "proration_details": {
                                  "credited_items": null
                              },
                              "quantity": 1,
                              "subscription": "sub_1PSioYGO0bcnzZi7Fuz2U5h7",
                              "subscription_item": "si_QJLVTctUNCPfV2",
                              "tax_amounts": [
                                  {
                                      "amount": 0,
                                      "inclusive": false,
                                      "tax_rate": "txr_1OpMhJGO0bcnzZi772TcyRH6",
                                      "taxability_reason": "not_collecting",
                                      "taxable_amount": 0
                                  },
                                  {
                                      "amount": 0,
                                      "inclusive": false,
                                      "tax_rate": "txr_1OpMhJGO0bcnzZi7HrOmMUDs",
                                      "taxability_reason": "not_collecting",
                                      "taxable_amount": 0
                                  }
                              ],
                              "tax_rates": [],
                              "type": "invoiceitem",
                              "unit_amount_excluding_tax": "2482"
                          }
                      ],
                      "has_more": false,
                      "total_count": 2,
                      "url": "\/v1\/invoices\/in_1PSndWGO0bcnzZi7UBMx9ilP\/lines"
                  },
                  "livemode": false,
                  "metadata": [],
                  "next_payment_attempt": null,
                  "number": "DEAEE296-0002",
                  "on_behalf_of": null,
                  "paid": true,
                  "paid_out_of_band": false,
                  "payment_intent": "pi_3PSndXGO0bcnzZi71BoQsptJ",
                  "payment_settings": {
                      "default_mandate": null,
                      "payment_method_options": {
                          "acss_debit": null,
                          "bancontact": null,
                          "card": {
                              "request_three_d_secure": "automatic"
                          },
                          "customer_balance": null,
                          "konbini": null,
                          "sepa_debit": null,
                          "us_bank_account": null
                      },
                      "payment_method_types": null
                  },
                  "period_end": 1718661770,
                  "period_start": 1718643234,
                  "post_payment_credit_notes_amount": 0,
                  "pre_payment_credit_notes_amount": 0,
                  "quote": null,
                  "receipt_number": null,
                  "rendering": null,
                  "shipping_cost": null,
                  "shipping_details": null,
                  "starting_balance": 0,
                  "statement_descriptor": null,
                  "status": "paid",
                  "status_transitions": {
                      "finalized_at": 1718661770,
                      "marked_uncollectible_at": null,
                      "paid_at": 1718661770,
                      "voided_at": null
                  },
                  "subscription": "sub_1PSioYGO0bcnzZi7Fuz2U5h7",
                  "subscription_details": {
                      "metadata": []
                  },
                  "subtotal": 496,
                  "subtotal_excluding_tax": 496,
                  "tax": 0,
                  "test_clock": null,
                  "total": 496,
                  "total_discount_amounts": [],
                  "total_excluding_tax": 496,
                  "total_tax_amounts": [
                      {
                          "amount": 0,
                          "inclusive": false,
                          "tax_rate": "txr_1OpMhJGO0bcnzZi772TcyRH6",
                          "taxability_reason": "not_collecting",
                          "taxable_amount": 0
                      },
                      {
                          "amount": 0,
                          "inclusive": false,
                          "tax_rate": "txr_1OpMhJGO0bcnzZi7HrOmMUDs",
                          "taxability_reason": "not_collecting",
                          "taxable_amount": 0
                      }
                  ],
                  "transfer_data": null,
                  "webhooks_delivered_at": 1718661770
              }
          },
          "livemode": false,
          "pending_webhooks": 2,
          "request": {
              "id": null,
              "idempotency_key": "e6bacfe6-4d40-44da-8fc4-0715bacd0abc"
          },
          "type": "invoice.paid"
      }';
    }
    public function getDt2() : string {
      return '{
            "id": "evt_1PSnPvGO0bcnzZi7MYbk5jId",
            "object": "event",
            "api_version": "2024-04-10",
            "created": 1718660927,
            "data": {
                "object": {
                    "id": "cs_test_a1A4ZTUY3TT9LlBAG9hRzz1jlR1I5Mj6tbk5EPim4r3jNesoAf3kk6veXs",
                    "object": "checkout.session",
                    "after_expiration": null,
                    "allow_promotion_codes": false,
                    "amount_subtotal": 2000,
                    "amount_total": 2000,
                    "automatic_tax": {
                        "enabled": true,
                        "liability": {
                            "type": "self"
                        },
                        "status": "complete"
                    },
                    "billing_address_collection": "auto",
                    "cancel_url": "https:\/\/stripe.com",
                    "client_reference_id": null,
                    "client_secret": null,
                    "consent": null,
                    "consent_collection": {
                        "payment_method_reuse_agreement": null,
                        "promotions": "none",
                        "terms_of_service": "none"
                    },
                    "created": 1718660894,
                    "currency": "usd",
                    "currency_conversion": null,
                    "custom_fields": [],
                    "custom_text": {
                        "after_submit": null,
                        "shipping_address": null,
                        "submit": null,
                        "terms_of_service_acceptance": null
                    },
                    "customer": null,
                    "customer_creation": "if_required",
                    "customer_details": {
                        "address": {
                            "city": null,
                            "country": "CA",
                            "line1": null,
                            "line2": null,
                            "postal_code": "G1V 0J8",
                            "state": null
                        },
                        "email": "cptmbh@yahoo.fr",
                        "name": "Bogo gobo",
                        "phone": null,
                        "tax_exempt": "none",
                        "tax_ids": []
                    },
                    "customer_email": null,
                    "expires_at": 1718747294,
                    "invoice": null,
                    "invoice_creation": {
                        "enabled": false,
                        "invoice_data": {
                            "account_tax_ids": null,
                            "custom_fields": null,
                            "description": null,
                            "footer": null,
                            "issuer": null,
                            "metadata": [],
                            "rendering_options": null
                        }
                    },
                    "livemode": false,
                    "locale": "en",
                    "metadata": [],
                    "mode": "payment",
                    "payment_intent": "pi_3PSnPtGO0bcnzZi71LG8I8x1",
                    "payment_link": "plink_1PEDjIGO0bcnzZi7sObqnN9X",
                    "payment_method_collection": "if_required",
                    "payment_method_configuration_details": {
                        "id": "pmc_1OoAkdGO0bcnzZi7eSoKNBof",
                        "parent": null
                    },
                    "payment_method_options": {
                        "card": {
                            "request_three_d_secure": "automatic"
                        }
                    },
                    "payment_method_types": [
                        "card",
                        "link"
                    ],
                    "payment_status": "paid",
                    "phone_number_collection": {
                        "enabled": false
                    },
                    "recovered_from": null,
                    "saved_payment_method_options": null,
                    "setup_intent": null,
                    "shipping_address_collection": null,
                    "shipping_cost": null,
                    "shipping_details": null,
                    "shipping_options": [],
                    "status": "complete",
                    "submit_type": "auto",
                    "subscription": null,
                    "success_url": "https:\/\/stripe.com",
                    "total_details": {
                        "amount_discount": 0,
                        "amount_shipping": 0,
                        "amount_tax": 0
                    },
                    "ui_mode": "hosted",
                    "url": null
                }
            },
            "livemode": false,
            "pending_webhooks": 0,
            "request": {
                "id": null,
                "idempotency_key": null
            },
            "type": "checkout.session.completed"
        }';
    }
    public function getDt3() : string {
        return '{
                    "id": "evt_3PSnPtGO0bcnzZi7107Meo0R",
                    "object": "event",
                    "api_version": "2024-04-10",
                    "created": 1718660926,
                    "data": {
                        "object": {
                            "id": "ch_3PSnPtGO0bcnzZi71ySIWL0p",
                            "object": "charge",
                            "amount": 2000,
                            "amount_captured": 2000,
                            "amount_refunded": 0,
                            "application": null,
                            "application_fee": null,
                            "application_fee_amount": null,
                            "balance_transaction": null,
                            "billing_details": {
                                "address": {
                                    "city": null,
                                    "country": "CA",
                                    "line1": null,
                                    "line2": null,
                                    "postal_code": "G1V 0J8",
                                    "state": null
                                },
                                "email": "cisspbootcamp08@gmail.com",
                                "name": "Bogo gobo",
                                "phone": null
                            },
                            "calculated_statement_descriptor": "EXAMBOOT.NET",
                            "captured": true,
                            "created": 1718660926,
                            "currency": "usd",
                            "customer": null,
                            "description": null,
                            "destination": null,
                            "dispute": null,
                            "disputed": false,
                            "failure_balance_transaction": null,
                            "failure_code": null,
                            "failure_message": null,
                            "fraud_details": [],
                            "invoice": null,
                            "livemode": false,
                            "metadata": [],
                            "on_behalf_of": null,
                            "order": null,
                            "outcome": {
                                "network_status": "approved_by_network",
                                "reason": null,
                                "risk_level": "normal",
                                "risk_score": 30,
                                "seller_message": "Payment complete.",
                                "type": "authorized"
                            },
                            "paid": true,
                            "payment_intent": "pi_3PSnPtGO0bcnzZi71LG8I8x1",
                            "payment_method": "pm_1PSnPsGO0bcnzZi7GvOBdYrm",
                            "payment_method_details": {
                                "card": {
                                    "amount_authorized": 2000,
                                    "brand": "visa",
                                    "checks": {
                                        "address_line1_check": null,
                                        "address_postal_code_check": "pass",
                                        "cvc_check": "pass"
                                    },
                                    "country": "US",
                                    "exp_month": 11,
                                    "exp_year": 2027,
                                    "extended_authorization": {
                                        "status": "disabled"
                                    },
                                    "fingerprint": "5c3B6EvTEI9gMv3H",
                                    "funding": "credit",
                                    "incremental_authorization": {
                                        "status": "unavailable"
                                    },
                                    "installments": null,
                                    "last4": "4242",
                                    "mandate": null,
                                    "multicapture": {
                                        "status": "unavailable"
                                    },
                                    "network": "visa",
                                    "network_token": {
                                        "used": false
                                    },
                                    "overcapture": {
                                        "maximum_amount_capturable": 2000,
                                        "status": "unavailable"
                                    },
                                    "three_d_secure": null,
                                    "wallet": null
                                },
                                "type": "card"
                            },
                            "radar_options": [],
                            "receipt_email": "cisspbootcamp08@gmail.com",
                            "receipt_number": null,
                            "receipt_url": "https:\/\/pay.stripe.com\/receipts\/payment\/CAcaFwoVYWNjdF8xT283ellHTzBiY256Wmk3KL_ewrMGMgYdLMRNi3s6LBY3DlRR9RKiVT_9sugCjAse-U0o50bvgXBCl7410Oh6OMMc3OovqIK9FWsu",
                            "refunded": false,
                            "review": null,
                            "shipping": null,
                            "source": null,
                            "source_transfer": null,
                            "statement_descriptor": null,
                            "statement_descriptor_suffix": null,
                            "status": "succeeded",
                            "transfer_data": null,
                            "transfer_group": null
                        }
                    },
                    "livemode": false,
                    "pending_webhooks": 1,
                    "request": {
                        "id": "req_UMMBKaqI64tqzE",
                        "idempotency_key": "1378ff99-ea8f-4d18-bd67-a3c6acc8d694"
                    },
                    "type": "charge.succeeded"
                }';
      }

}
