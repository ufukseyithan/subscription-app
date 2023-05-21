<?php

namespace App\Console\Commands;

use App\Mail\PostMail;
use App\Models\Website;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendPostEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:send-post {websiteId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $websiteId = $this->argument('websiteId');
        $website = Website::findOrFail($websiteId);

        $posts = $website->posts();
        $users = $website->users();

        foreach ($posts as $post) {
            foreach ($users as $user) {
                if (!$user->hasReceivedEmail($post->id)) {
                    Mail::to($user->email)->queue(new PostMail($post));
    
                    $user->markEmailAsReceived($post->id);
    
                    $this->info("Email sent to {$user->email}");
                }
            }
        }

        $this->info('Emails sent successfully.');
    }
}
