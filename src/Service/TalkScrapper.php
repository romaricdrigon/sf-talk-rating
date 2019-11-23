<?php

namespace App\Service;

use App\Entity\Event;
use App\Entity\Talk;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Class TalkScrapper
 * Used to scrape schedule from a SymfonyCon / SymfonyLive website, so we don't have to do it manually.
 *
 * @author Romaric Drigon <romaric.drigon@gmail.com>
 */
class TalkScrapper
{
    private $entityManager;
    private $httpClient;

    public function __construct(EntityManagerInterface $entityManager, HttpClientInterface $httpClient)
    {
        $this->entityManager = $entityManager;
        $this->httpClient = $httpClient;
    }

    public function import(Event $event, string $scheduleUrl)
    {
        $this->removePreviousTalks($event);

        $response = $this->httpClient->request('GET', $scheduleUrl);

        $crawler = new Crawler($response->getContent(true)); // We want exceptions!

        $crawler->filter('div.speaker')->each(function (Crawler $crawler) use ($event) {
            $sessionCrawler = $crawler->filter('div.session');

            if (!count($sessionCrawler)) {
                return;
            }

            $title = $sessionCrawler->filter('p.name')->text();

            $descriptionCrawler = $sessionCrawler->children('p:not([class])');
            $description = count($descriptionCrawler) ? implode("\n", $descriptionCrawler->extract(['_text'])) : null;

            $speakerCrawler = $sessionCrawler->filter('p.speaker');

            if (!count($speakerCrawler)) {
                return;
            }

            $speakerName = $speakerCrawler->filter('a')->text();
            preg_match('#https://connect\.symfony\.com/api/alternates/([a-z0-9\-]+)#', $speakerCrawler->filter('a')->attr('href'), $matches);
            $speakerUuid = $matches[1];

            $talk = (new Talk())
                ->setEvent($event)
                ->setTitle($title)
                ->setDescription($description);
            $talk->getSpeaker()
                ->setName($speakerName)
                ->setUuid($speakerUuid);

            $this->entityManager->persist($talk);
        });

        // Finally, we flush, so everything happens in only one transaction
        $this->entityManager->flush();
    }

    private function removePreviousTalks(Event $event): void
    {
        foreach ($event->getTalks() as $talk) {
            $this->entityManager->remove($talk);
        }

        // We do not flush - we don't want to delete Talks if scraping fails
    }
}
