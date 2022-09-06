<?php

namespace App\Controller\Admin;

use App\Entity\Game;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class GameCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Game::class;
    }
    
    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('slug')->setLabel('name');
        yield NumberField::new('minimumNumber')->setLabel('minimum number');
        yield NumberField::new('maximumNumber')->setLabel('maximum number');
        yield NumberField::new('howManyNumbers')->setLabel('how many numbers');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
        ->disable(Action::NEW, Action::DELETE)
        ->disable(Action::NEW, Action::EDIT)
        ;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
