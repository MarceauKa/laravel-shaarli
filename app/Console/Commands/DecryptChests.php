<?php

namespace App\Console\Commands;

use App\Models\Chest;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DecryptChests extends Command
{
    protected $signature = 'shaark:chests:decrypt';
    protected $description = 'This command will decrypt all encrypted chests';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $table = (new Chest())->getTable();
        $count = DB::table($table)->count();

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        DB::table($table)
            ->select('id', 'content')
            ->orderByDesc('created_at')
            ->chunk(10, function ($chests) use ($table, $bar) {
                foreach ($chests as $chest) {
                    if (empty($chest->content)) {
                        continue;
                    }

                    try {
                        $value = decrypt($chest->content, false);
                    } catch (\Exception $e) {
                        unset($e);
                    }

                    if (! empty($value)) {
                        $chest->content = $value;

                        DB::table($table)->where('id', $chest->id)->update(['content' => $chest->content]);
                    }
                }

                $bar->advance(10);
            });

        $bar->finish();
    }
}
