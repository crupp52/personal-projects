<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use DateTimeZone;
use Illuminate\Http\Request;
use Spatie\IcalendarGenerator\Components\Alert;
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

        return response($calendar->get(), 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="artur.ics"',
        ]);
    }
}
