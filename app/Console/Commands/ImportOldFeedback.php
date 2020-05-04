<?php

namespace App\Console\Commands;

use App\Domain\Models\Feedback;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

/**
 * Class ImportOldFeedback.
 */
class ImportOldFeedback extends Command
{
    /**
     * @var string
     */
    protected $signature = 'import:old-feedback';

    /**
     * @var string
     */
    protected $endpoint = 'https://www.serviceright-autos.nl/api/feedback.php?all=true';

    /**
     * @var array
     */
    protected $result = [
        'recently_created' => 0,
        'items_processed' => 0,
    ];

    /**
     * Handle all the processing.
     */
    public function handle()
    {
        $this->warn('Fetching data from: '.$this->endpoint);
        $client = (new Client());
        $response = $client->get($this->endpoint);
        $feedback = json_decode($response->getBody()->getContents(), true);

        $this->info('Found: '.count($feedback).' items');
        $this->warn('Updating items this can take a while......');

        // check if it already exists in the database
        foreach ($feedback as $review) {
            /** @var Carbon $created_at */
            $created_at = Carbon::createFromFormat('Y-m-d', $review['date']);
            $created_at->startOfDay();

            if ($review['branche'] === 'autos') {
                $branch = 'vehicles';
            } elseif ($review['branche'] === 'koeriers') {
                $branch = 'couriers';
            } else {
                $branch = 'unknown';
            }

            $reviewInformation = [
                'branch' => $branch,
                'order_id' => ($review['order_id'] > 0) ? $review['order_id'] : null,
                'company_id' => ($review['company_id'] > 0) ? $review['company_id'] : null,
                'rating' => $review['rating'],
                'content' => $review['text'],
                'order_information' => [
                    'name' => $review['name'],
                    'city' => $review['city'],
                    'feedback_id' => $review['id'],
                ],
                'created_at' => $created_at,
            ];



            if ($review['brand']) {
                $reviewInformation['tags']['brand'] = $review['brand'];
            }

            if($review['model']) {
                $reviewInformation['tags']['model'] = $review['model'];
            }

            if($review['bouwjaar']) {
                $reviewInformation['tags']['construction_year'] = $review['bouwjaar'];
            }

            if($review['brandstof']) {
                $reviewInformation['tags']['fuel_type'] = $review['brandstof'];
            }

            if ($review['accepted'] === 1) {
                $reviewInformation['accepted'] = Carbon::now();
            }

            $feedback = Feedback::query()->withTrashed()->firstOrUpdate([
                'order_information->feedback_id' => (int) $review['id'],
            ], $reviewInformation);

            if ($feedback->wasRecentlyCreated) {
                $this->result['recently_created']++;
            }

            $this->result['items_processed']++;

            if ($review['accepted'] === -1) { // not accepted delete the review
                $feedback->delete();
            }
        }

        // items that has been processed
        $this->info('Done processing: '.$this->result['items_processed']);

        // items that has been recently created
        if ($this->result['recently_created']) {
            $this->info($this->result['recently_created'].' added items');
        }
    }
}
