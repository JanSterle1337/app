<?php

namespace App\Controller\Admin;

use App\Entity\GameRound;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;

class GameRoundCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return GameRound::class;
    }

    
    public function configureFields(string $pageName = 'Game rounds'): iterable
    {
        yield AssociationField::new('game')->setLabel('game name');
        yield TextField::new('name')->setLabel('event');
        yield DateTimeField::new('scheduledAt')->setFormTypeOptions([
            'html5' => true,
            'widget' => 'single_text'
        ])->setLabel('scheduled at');
        yield BooleanField::new('playedAlready')->setFormTypeOptions([
            'disabled' => true
        ])->setLabel('played already');
    }

    public function  configureCrud(Crud $crud): Crud
    {
        return $crud 
        ->setEntityLabelInSingular('game round')
        ->setPageTitle(CRUD::PAGE_INDEX,'Game rounds')
        ;
    }
    
}
