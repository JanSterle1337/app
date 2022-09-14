<?php 
namespace App\Command;

use App\Repository\GameRoundRepository;
use App\Repository\TicketRepository;
use App\Service\Drawer;
use App\Service\DuplicateNumberChecker;
use App\Service\TicketToGameRoundCombinationMatcher;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;


#[AsCommand(name: 'app:launch-specific-game')]
class LaunchSpecificGameCommand extends Command 
{
    private GameRoundRepository $gameRoundRepository;
    private DuplicateNumberChecker $duplicateNumberChecker;
    private TicketToGameRoundCombinationMatcher $ticketToGameRoundCombinationMatcher;
    private TicketRepository $ticketRepository;

    public function __construct(
        GameRoundRepository $gameRoundRepository,
        DuplicateNumberChecker $duplicateNumberChecker,
        TicketToGameRoundCombinationMatcher $ticketToGameRoundCombinationMatcher,
        TicketRepository $ticketRepository
        )
    {
        parent::__construct();

        $this->gameRoundRepository = $gameRoundRepository;
        $this->duplicateNumberChecker = $duplicateNumberChecker;
        $this->ticketToGameRoundCombinationMatcher = $ticketToGameRoundCombinationMatcher;
        $this->ticketRepository = $ticketRepository;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$output instanceof ConsoleOutputInterface) {
            throw new \LogicException('This command accepts only an instance of "ConsoleOutputInterface".');
        }

        $output->writeln([
            '==============================================================',
            'Write down the specific game round name that you want to launch:'
        ]);

        $slug = $input->getArgument('slug');

        $gameRound = $this->gameRoundRepository->findNotPlayedYetBySlug($slug);

        if ($gameRound === null) {
            $errorSection = $output->section();
            $errorSection->writeln("There is no game round with that kind of game. Please try something else or try later.");
            
            return Command::FAILURE;
        }

        $drawer = new Drawer($this->duplicateNumberChecker);

        $gameCombination = $drawer->drawCombination($gameRound->getGame());
        $gameRound->setDrawnCombination($gameCombination);

        $this->gameRoundRepository->add($gameRound, true);
        $tickets = $this->ticketRepository->findByGameRound($gameRound);
        $this->ticketToGameRoundCombinationMatcher->createTicketResult($tickets);

        $section = $output->section();
        
        $section->writeln("Game round " . $slug . " has been successfully launched...");

        return Command::SUCCESS;
    }  
    
    protected function configure(): void
    {
        $this
        ->addArgument('slug', InputArgument::REQUIRED, 'The name of the game round')
        ;
    }

}