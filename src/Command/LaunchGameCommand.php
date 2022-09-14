<?Php 
namespace App\Command;

use App\Repository\GameRoundRepository;
use App\Service\GameLauncher;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Intriro\Clock\SystemClock;

#[AsCommand(name: 'app:launch-game')]
class LaunchGameCommand extends Command
{
    private SystemClock $clock;
    private GameRoundRepository $gameRoundRepository;
    private GameLauncher $gameLauncher;

    public function __construct(SystemClock $clock, GameRoundRepository $gameRoundRepository, GameLauncher $gameLauncher)
    {   
        parent::__construct();

        $this->clock = $clock;
        $this->gameRoundRepository = $gameRoundRepository;
        $this->gameLauncher = $gameLauncher;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$output instanceof ConsoleOutputInterface) {
            throw new \LogicException('This command accepts only an instance of "ConsoleOutputInterface".');
        }

        $gameRounds = $this->gameRoundRepository->findNotPlayedYet();

        if (empty($gameRounds)) {

            $section = $output->section();
            $section->writeln('There are no rounds you can launch. Please check later.');
            return Command::SUCCESS;
            
        }
    
        $section = $output->section();

        foreach ($gameRounds as $gameRound) {

            if ($gameRound->getScheduledAt() < $this->clock->now()) {
                $this->gameLauncher->launch($gameRound);
                $section->writeln($gameRound->getName() . " has been launched");
                $section->writeln("------------------------------------------->");
            }

        }

        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this->setHelp('This command allows you to launch all games that should have happend before current time');
    }
}