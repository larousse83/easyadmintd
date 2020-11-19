<?php

namespace App\Controller\Admin;

use App\Entity\Ecole\Ecole;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class EcoleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Ecole::class;
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
