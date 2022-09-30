<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Event;

class GenerateRecurringEvent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:recurring-event {--name=} {--starts-at=}  {--count=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $events = [];

        $eventName = $this->option('name');
        $startsAt = Carbon::createFromFormat('Y.m.d.', $this->option('starts-at'));

        foreach (range(1, $this->option('count')) as $item) {
            $events[] = Event::create()
                ->name(str_replace('%1', $item, $eventName))
                ->startsAt($startsAt->addMonths($item));
        }

        dd(Calendar::create()
            ->event($events)
            ->get());

        return Command::SUCCESS;
    }
}
