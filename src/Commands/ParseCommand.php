<?php

namespace App\Commands;

use App\Service\UpdateData;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ParseCommand extends Command
{
    protected static $defaultName = 'app:parse';

    private UpdateData $client;

    public function __construct(UpdateData $updateData)
    {
        $this->client = $updateData;
        parent::__construct();
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {


        // парсим категории
        $output->writeln('Начинаем парсинг');
        if ($this->client->parseShop()) {
            $output->writeln('Парсинг завершен');
        } else {
            $output->writeln('Ошибка при сохранении данных');
        }
        return 1;
    }


}
