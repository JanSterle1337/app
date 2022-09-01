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

    
    public function configureFields(string $pageName): iterable
    {
        yield AssociationField::new('gameID');
        yield TextField::new('name');
        yield DateTimeField::new('scheduledAt')->setFormTypeOptions([
            'html5' => true,
            'widget' => 'single_text'
        ]);
        yield BooleanField::new('playedAlready');
    }
    
}
