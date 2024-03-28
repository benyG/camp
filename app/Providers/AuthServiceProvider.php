<?php

namespace App\Providers;

use App\Models\Answer;
use App\Models\Course;
use App\Models\Exam;
use App\Models\Info;
use App\Models\Module;
use App\Models\QuestAns;
use App\Models\Question;
use App\Models\Smail;
use App\Models\User;
use App\Models\UsersMail;
use App\Models\Vague;
use App\Policies\AnswerPolicy;
use App\Policies\CoursePolicy;
use App\Policies\ExamPolicy;
use App\Policies\InfoPolicy;
use App\Policies\ModulePolicy;
use App\Policies\QuestAnsPolicy;
use App\Policies\QuestionPolicy;
use App\Policies\UserPolicy;
use App\Policies\VaguePolicy;
// use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Notifications\Messages\MailMessage;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
        User::class => UserPolicy::class, Question::class => QuestionPolicy::class, Answer::class => AnswerPolicy::class,
        Module::class => ModulePolicy::class, Vague::class => VaguePolicy::class, Course::class => CoursePolicy::class,
        Info::class => InfoPolicy::class, Smail::class => SmailPolicy::class, QuestAns::class => QuestAnsPolicy::class,
        UsersMail::class => UsersMailPolicy::class, Exam::class => ExamPolicy::class,
        //     User::class => UserPolicy::class,
        // User::class => UserPolicy::class,

    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject('Please confirm your email address')
                ->view(
                    ['emails.mail3', 'emails.mail3-t'],
                    ['url' => $url]
                );
        });
    }
}
