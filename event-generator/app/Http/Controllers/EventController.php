<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Event;
use Spatie\IcalendarGenerator\Components\Timezone;

class EventController extends Controller
{
    public function artur(Request $request): string
    {
        $events = [];

        $calendar = Calendar::create('Artúr')
            ->timezone(Timezone::create('Europe/Budapest'));

        $birthDate = Carbon::createFromDate('2022', '09', '02')->startOfDay();

        foreach (range(1, 24) as $month) {
            $startsAt = $birthDate->clone()->addMonths($month);
            $alertAt = $startsAt->clone();

            $events[] = Event::create()
                ->name('👶🏻 Artúr ' . $month . ' hónapos')
                ->startsAt($startsAt->toDateTime())
                ->alertAt($alertAt->addHours(9))
                ->fullDay();
        }

        $calendar->event($events);

        return $this->makeCalendarResponse($calendar, 'artur.ics');
    }

    public function birthday()
    {
        $template = '🎂 Kitti %number%. születésnapja';

        $birthDate = Carbon::createFromDate('1998', '03', '25')->startOfDay();

        $today = Carbon::now()->startOfDay();

        $currentBirthday = $birthDate->clone()->setYear($today->year);

        $calendar = Calendar::create('Születésnapok')
            ->timezone(new Timezone('Europe/Budapest '));

        foreach (range(1, 10) as $year) {
            $nextBirthday = $currentBirthday->addYear();

            $number = $nextBirthday->diffInYears($birthDate);

            $calendar->event(
                Event::create(str_replace('%number%', $number, $template))
                    ->startsAt($nextBirthday->toDateTime())
                    ->fullDay()
            );
        }

        return $this->makeCalendarResponse($calendar, 'birthday.ics');
    }

    private function makeCalendarResponse(Calendar $calendar, string $name = 'calendar.ics'): \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\Response
    {
        return response($calendar->get(), 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $name . '"',
        ]);
    }
}
