<?php

namespace App\Nova\Dashboards;

use App\Nova\Metrics\NewUsers;
use App\Nova\Metrics\OrdersToday;
use App\Nova\Metrics\OrdersPerDay;
use Laravel\Nova\Dashboards\Main as Dashboard;

class Main extends Dashboard
{
    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        return [
            NewUsers::make(),
            OrdersPerDay::make(),
            OrdersToday::make(),
        ];
    }
}
