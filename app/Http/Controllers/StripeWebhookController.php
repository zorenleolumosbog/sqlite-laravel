<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Stripe\Stripe;
use Stripe\Event;
use Illuminate\Support\Facades\Mail;
use App\Mail\Registration;
use Carbon\Carbon;

class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        // Set the API key
        Stripe::setApiKey(config('services.stripe.secret_key'));

        // Verify the Stripe webhook signature
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $event = null;

        try {
            $event = Event::constructFrom(
                json_decode($payload, true),
                $sig_header,
                config('services.stripe.webhook_secret')
            );
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Handle the event based on its type
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;

                // Retrieve the customer object from the payment intent
                $customer = \Stripe\Customer::retrieve($paymentIntent->customer);

                // Get the customer's name and email
                $customer->name;
                $customer->email;

                $user = User::where('email', $customer->email)->first();
                if(!$user) {
                    $password = Str::random(12);
    
                    User::create([
                        'name' => $customer->name,
                        'email' => $customer->email,
                        'email_verified_at' => Carbon::now(),
                        'password' => Hash::make($password)
                    ]);

                    $new_user = [
                        'name' => $customer->name,
                        'email' => $customer->email,
                        'password' => $password
                    ];

                    Mail::to($customer->email)->send(new Registration($new_user));
                }

                break;
            case 'payment_intent.payment_failed':
                // Payment failed logic here
                break;
            // Handle other event types here
            default:
                // Unexpected event type
                return response()->json(['error' => 'Unexpected event type'], 400);
        }

        return response()->json(['success' => true]);
    }
}
