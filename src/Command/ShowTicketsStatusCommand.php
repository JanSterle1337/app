<?php 
namespace App\Command;

use App\Entity\Ticket;
use App\Repository\TicketRepository;
use App\Utils\PrintArrayNumbersInString;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;


#[AsCommand(name: 'app:show-all-tickets')]
class ShowTicketsStatusCommand extends Command
{
    private TicketRepository $ticketRepository;
    private PrintArrayNumbersInString $printArrayNumbersInString;

    public function __construct(PrintArrayNumbersInString $printArrayNumbersInString,TicketRepository $ticketRepository)
    {
        parent::__construct();

        $this->printArrayNumbersInString = $printArrayNumbersInString;
        $this->ticketRepository = $ticketRepository;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$output instanceof ConsoleOutputInterface) {
            throw new \LogicException('This command accepts only an instance of "ConsoleOutputInterface".');
        }

        $tickets = $this->ticketRepository->findAll();

        $table = new Table($output);
        $table->setHeaders(['ticket ID', 'user', 'game round ID', 'game name', 'played numbers', 'generated numbers', 'matched numbers']);

        foreach ($tickets as $ticket) {

            //dd($ticket);
            
            $playedNumbers = $ticket->getCombination()?->getNumbers();
            $gameRoundNumbers = $ticket->getGameRound()->getDrawnCombination()?->getNumbers();
            $matchedNumbers = $ticket->getMatchedCombination()?->getNumbers();

            $playedNumbers = $this->printArrayNumbersInString->convert($playedNumbers);
            $gameRoundNumbers = $this->printArrayNumbersInString->convert($gameRoundNumbers);
            $matchedNumbers = $this->printArrayNumbersInString->convert($matchedNumbers);
            
           $table->addRow([$ticket->getId(), $ticket->getUser()->getEmail(), $ticket->getGameRound()->getId(), $ticket->getGameRound()->getName(), $playedNumbers, $gameRoundNumbers, $matchedNumbers]);
        }

        $table->render();
        return Command::SUCCESS;
    }
}