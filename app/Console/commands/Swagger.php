<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Swagger extends Command
{
    /**
     * @var string
     */
    protected $signature = 'swagger';

    /**
     * @var string
     */
    protected $description = 'This command generate a current swagger api documentation';

    /**
     * @return int
     */
    public function handle()
    {
        $openapi = \OpenApi\Generator::scan([config('swagger.sources')]);
        file_put_contents("public/api-documentation/swagger.json", $openapi->toJson());
        $this->info('Api documentation generated successfully!');
        return Command::SUCCESS;
    }
}
