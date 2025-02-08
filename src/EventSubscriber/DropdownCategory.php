<?php

namespace App\EventSubscriber;

use App\Repository\Articles\CategoryRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Messenger\Envelope;
use Twig\Environment;

class DropdownCategory implements EventSubscriberInterface
{
    const ROUTES = ['app_articles_activities', 'app_articles_circuits', 'category.index'];

    public function __construct(
        private CategoryRepository $categoryRepository,
        private Environment $twig
    )
    {
        
    }
    
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'injectGlobalVariable',
        ];
    }

    public function injectGlobalVariable(RequestEvent $event): void
    {
        $route =$event->getRequest()->get('_route');
        if(in_array($route, DropdownCategory::ROUTES)){
            $categories = $this->categoryRepository->findAll();
            $this->twig->addGlobal('allCategories', $categories);
        }
    }
}
