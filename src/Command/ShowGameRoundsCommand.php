<?php 
namespace App\Command;

use App\Entity\Game;
use App\Repository\GameRoundRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;


#[AsCommand(name: 'app:show-all-gameRounds')]
class ShowGameRoundsCommand extends Command
{
    private GameRoundRepository $gameRoundRepository;

    public function __construct(GameRoundRepository $gameRoundRepository)
    {
        parent::__construct();

        $this->gameRoundRepository = $gameRoundRepository;
    }

    public function execute(InputInterface $input, OutputInterface $output): int 
    {
        if (!$output instanceof ConsoleOutputInterface) {
            throw new \LogicException('This command accepts only an instance of "ConsoleOutputInterface".');
        }

        $playedAlready = (int)$input->getArgument('playedAlready');

        if ($playedAlready === 0 || $playedAlready === 1) {
            $gameRounds = $this->gameRoundRepository->findByIsPlayedYet($playedAlready);
           
            $table = new Table($output);
            $table
            ->setHeaders(['GameRound ID', 'Name', 'Drawn combination' ,'Scheduled at']);
            
            foreach ($gameRounds as $gameRound) {
                
                $numbers = "";

                if ($gameRound->getDrawnCombination()) {

                    foreach ($gameRound->getDrawnCombination()->getNumbers() as $number) {
                        $numbers .= $number . " ";
                    }

                }
            
                $table->addRow([$gameRound->getId(), $gameRound->getName(), $numbers ,$gameRound->getDateAndTime()]);

            }
            
            $table->render();
            return Command::SUCCESS;
        }

    
        return Command::FAILURE;

    }

    public function configure(): void 
    {
        $this
        ->addArgument('playedAlready', InputArgument::REQUIRED, 'type true from already played rounds and false for not played rounds');
    }
}