<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class ViewLiveSite extends Widget
{
    protected static string $view = 'filament.widgets.view-live-site';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = -1;
}
