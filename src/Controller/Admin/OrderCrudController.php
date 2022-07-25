<?php

namespace App\Controller\Admin;

use App\Controller\OrderController;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;

use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use phpDocumentor\Reflection\DocBlock\Tags\Reference\Url;

class OrderCrudController extends AbstractCrudController
{
    private  $adminUrlGenerator;
    //private $crudUrlGenerator;
    private $entityManager ;//doctrine msaminha aka
    public function __construct(EntityManagerInterface  $entityManager,AdminUrlGenerator $adminUrlGenerator){
       // $this->crudUrlGenerator=$crudUrlGenerator;
        $this->entityManager=$entityManager;
        $this->adminUrlGenerator=$adminUrlGenerator;
    }

    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        //creer lien pour modifier le statue
        $updatePreparation = Action::new('updatePreparation','Préparation en cours')->linkToCrudAction('updatePreparation');
        $updateDelivery = Action::new('updateDelivery','Livraison en cours')->linkToCrudAction('updateDelivery');


        return $actions
            ->add('detail',$updatePreparation )
            ->add('detail',$updateDelivery)
            ->add('index','detail');


    }

    public function updatePreparation(AdminContext $context)
    {
        $order=$context->getEntity()->getInstance();
        $order->setState(1);
        $this->entityManager->flush();



        $this->addFlash('notice',"<span style='color:green;'><strong>La commande".$order->getReference()."est bien <u>en cours de préparation</u>.</strong></span>");

       $url=$this->adminUrlGenerator
            //->build()
            ->setController(OrderCrudController::class)
            ->setAction('index')
            ->generateUrl();

        return $this->redirect($url);
       // $routeBuilder=$this->get(AdminUrlGenerator::class);
       // return $this->redirect($routeBuilder->setController(OrderCrudController::class)->setAction('index')->generateUrl());
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setDefaultSort(['id'=>'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            //DateTimeField::new('createdAt','Passé le '),
            TextField::new('user.fullName','Client'),
            MoneyField::new('total')->setCurrency('TND'),
           // BooleanField::new('isPaid','payée'),
          //  ArrayField::new('orderDetails','produit commander')->hideOnIndex(),
            ChoiceField::new('state')->setChoices([
                'Commande effectué'=>0,
                'Prépartion en cours'=>1,
                'Livraison en cours'=>2
            ])
        ];
    }

}
