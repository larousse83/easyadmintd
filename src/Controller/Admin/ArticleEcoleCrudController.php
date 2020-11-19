<?php

namespace App\Controller\Admin;

use App\Entity\Ecole\ArticleEcole;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ArticleEcoleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ArticleEcole::class;
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
