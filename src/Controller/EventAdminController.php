<?php

namespace App\Controller;

use App\Entity\Event;
use App\Service\TalkScrapper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class EventAdminController
 * Provides extra actions over Events, in the administration.
 *
 * @author Romaric Drigon <romaric.drigon@gmail.com>
 *
 * @Route("/admin/scrape")
 */
class EventAdminController extends AbstractController
{
    /**
     * @Route("/init", name="admin_event_scrape_init")
     */
    public function scrapeInit(Request $request): Response
    {
        $id = $request->query->get('id');

        if (!$event = $this->getDoctrine()->getRepository(Event::class)->find($id)) {
            throw $this->createNotFoundException('Unable to find Event');
        }

        $action = $this->generateUrl('admin_event_scrape_do', ['id' => $event->getId()]);

        $form = $this->createFormBuilder(null, ['action' => $action])
            ->add('url', UrlType::class, [
                'label' => 'Schedule URL',
            ])
            ->getForm()
        ;

        return $this->render('event_admin/init.html.twig', [
            'canScrape' => $event->canBeScraped(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id<\d+>}/do", name="admin_event_scrape_do", methods={"POST"})
     * @ParamConverter("event")
     */
    public function scrape(Event $event, Request $request, TalkScrapper $scrapper): Response
    {
        if (!$request->request->get('form')
            || !isset($request->request->get('form')['url'])
            || !$url = $request->request->get('form')['url']
        ) {
            throw $this->createNotFoundException('Missing URL');
        }

        if (!$event->canBeScraped()) {
            throw $this->createNotFoundException('Even can not be scraped');
        }

        $scrapper->import($event, $url);

        return $this->redirectToRoute('easyadmin', [
            'entity' => 'Talk',
            'action' => 'list',
        ]);
    }
}
