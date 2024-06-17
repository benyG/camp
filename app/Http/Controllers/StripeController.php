<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class StripeController extends Controller
{
    public function handleWebhook()
    {
        $stripe = new \Stripe\StripeClient('rk_test_51Oo7zYGO0bcnzZi7htomlQuVlBk2i2SsIov0weXy387DYDMm7pGPyP0q0bAkE7HU9tBnyCGdolwamY10F6oeg5b60007G7OsJ5');

        // This is your Stripe CLI webhook secret for testing your endpoint locally.
        $endpoint_secret = 'whsec_J9ZBsoHBh1iS1wZtCALUlxTOCsTQmaTw';

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
            http_response_code(400);
            exit();
          } catch(\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            http_response_code(400);
            report($e);
            exit();
          }

          // Handle the event
          echo 'Received unknown event type ' . $event->type;


        // Store the event data into a local file
        $this->storeEvent($event);

        // Respond with a 200 status to acknowledge receipt of the event
        return response()->json(['status' => 'success'], 200);
    }

    protected function storeEvent($event)
    {
        // Define the path to store the event data
        $path = storage_path('logs/stripe_webhook_events.log');

        // Convert event data to JSON format
        $eventData = json_encode($event, JSON_PRETTY_PRINT);

        // Append the event data to the file
        file_put_contents($path, $eventData . PHP_EOL, FILE_APPEND);
    }
}
