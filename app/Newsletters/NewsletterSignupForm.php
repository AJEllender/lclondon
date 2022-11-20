<?php

namespace App\Newsletters;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Yadda\Enso\Newsletter\Contracts\NewsletterHandlerContract;
use Yadda\Enso\Newsletter\Traits\HasGenericFormData;
use Yadda\Enso\Newsletter\Traits\SendNewsletterToMailchimp;
use Yadda\Enso\Newsletter\Traits\WriteNewsletterToDatabase;

class NewsletterSignupForm implements NewsletterHandlerContract
{
    use HasGenericFormData,
        SendNewsletterToMailchimp,
        WriteNewsletterToDatabase;

    /**
     * Gets the important Form data for displaying on index pages
     *
     * @param Model $newsletter
     *
     * @return array
     */
    public static function getImportantFormData(Model $newsletter): array
    {
        return [
            'email' => $newsletter->email,
        ];
    }

    /**
     * Handle the request data
     *
     * @param array $request_data
     *
     * @return mixed
     */
    public function handle(array $request_data)
    {
        $this->writeToDatabase($request_data);

        if (!empty(Config::get('newsletter.apiKey'))) {
            $this->sendToMailchimp($request_data);
        }
    }

    /**
     * Validation messages
     *
     * @return array
     */
    public function messages(): array
    {
        return [];
    }

    /**
     * Validation rules
     *
     * @return array
     */
    public function rules(): array
    {
        return [];
    }
}
