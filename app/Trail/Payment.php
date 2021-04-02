<?php
namespace App\Trail;

trait Payment
{

    public function makePayment()
    {

        return true;
        //El código siguiente es de el proyecto viejo linea 434 a 489 de HiringController
        $sub = Subscription::find($request->radio_payments); // Change by Member

        $transaction_ref = null;

        if ($sub->price > 0) {
            //Create the payment Here Using Stripe
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            try {
                $tokenStripe = \Stripe\Token::create([
                    'card' => [
                        'number' => $request->card_number,
                        'exp_month' => $request->card_expire_month,
                        'exp_year' => $request->card_expire_year,
                        'cvc' => $request->card_cvc,
                    ],
                ]);
            } catch (\Stripe\Error\Base $e) {
                $broker->delete();
                return redirect()->back()->withErrors([$e->getMessage()]);
            }

            $dollarsInCents = bcmul($sub->price, 100); // Dollars to cents

            $charged = $broker->charge($dollarsInCents, [
                'source' => $tokenStripe,
                'receipt_email' => $broker->email,
                'description' => $sub->title,
                'metadata' => array('Number' => $broker->number_account),
            ]);

            if (!$charged) {
                $broker->delete();
                return redirect()->back()->withErrors(["Lo sentimos, el proceso del pago no se pudo realizar. Por favor intentelo otra vez."]);
            }

            $transaction_ref = $charged->id;
        }

        //Add the transaction to subscription_user
        $broker->memberships()->create(array(
            'subscription_id' => $sub->id,
            'paid_amount' => $sub->price,
            'duration' => $sub->duration,
            'paid_date' => date('Y-m-d H:i:s'),
            'transaction_ref' => $transaction_ref,
        ));

        $token = hash_hmac('sha256', str_random(40), config('app.key'));
        DB::table(config('auth.password.table'))->insert(['email' => $broker->email, 'token' => $token, 'created_at' => new Carbon]);
        $job = new SendWelcomeEmail($token, $broker);
        $this->dispatch($job->onQueue('emails')->delay(10));
    }
}