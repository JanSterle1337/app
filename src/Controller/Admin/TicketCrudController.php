<?php

namespace App\Controller\Admin;

use App\Entity\Ticket;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TicketCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Ticket::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield NumberField::new('id')->setLabel('ticket ID');
        yield TextField::new('user.email')->setLabel('user');
        yield NumberField::new('gameRound.id')->setLabel('game round ID');
        yield TextField::new('gameRound.game.slug')->setLabel('game name');
        yield ArrayField::new('combination.numbers')->setLabel('played numbers');
        yield ArrayField::new('gameRound.drawnCombination.numbers')->setLabel('generated numbers');
        yield ArrayField::new('matchedCombination.numbers')->setLabel('matched numbers');  
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
        ->add('id')
        ;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud 
        ->setEntityLabelInPlural('Tickets')
        ->setPageTitle(CRUD::PAGE_INDEX, 'Tickets')
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
        ->disable(Action::EDIT);
    }
}
